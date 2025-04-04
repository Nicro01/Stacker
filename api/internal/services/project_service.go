package services

import (
	"bytes"
	"encoding/json"
	"errors"
	"fmt"
	"io"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"

	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
	"github.com/guifaraco/stacker-api/internal/dto/request"
)

var (
	ErrProjectExists    = errors.New("projeto já existe")
	ErrInvalidStack     = errors.New("stack não suportada")
	ErrCommandExecution = errors.New("erro na execução do comando")
)

type LaravelProjectService struct {
}

func NewLaravelProjectService() *LaravelProjectService {
	return &LaravelProjectService{}
}

func (s *LaravelProjectService) CreateProject(ctx *gin.Context, req request.CreateLaravelProjectRequest) error {
	// Verifica se o diretório já existe
	projectDir := filepath.Join(req.ProjectPath, req.ProjectName)
	if _, err := os.Stat(projectDir); err == nil {
		return ErrProjectExists
	}

	if req.CreateRepository {
		if err := s.CreateRepository(ctx, req); err != nil {
			return fmt.Errorf("erro ao criar repositório: %w", err)
		}
	}

	// Cria projeto base
	projectID := uuid.New()
	if req.Stack != 3 && req.Stack != 4 {
		if err := s.createBaseProject(ctx, req, projectID); err != nil {
			return err
		}
	}

	// Instala stack específica
	var err error
	switch req.Stack {
	case 1:
		// Laravel default – nada extra a instalar
	case 2:
		err = s.installTallStack(ctx, req, projectID)
	case 3:
		err = s.createVILTProject(ctx, req, projectID)
	case 4:
		err = s.createRILTProject(ctx, req, projectID)
	default:
		return ErrInvalidStack
	}

	if err != nil {
		return err
	}

	if req.GithubToken != "" && req.GithubUsername != "" {
		if err := s.pushToGithub(ctx, req); err != nil {
			return fmt.Errorf("falha ao dar push no GitHub: %w", err)
		}
	}

	return nil
}

func (s *LaravelProjectService) createBaseProject(ctx *gin.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

	cmd := exec.CommandContext(ctx,
		"composer", "create-project", "laravel/laravel",
		"--prefer-dist", req.ProjectName,
	)
	cmd.Dir = req.ProjectPath

	if err := s.executeCommand(cmd, projectID); err != nil {
		return fmt.Errorf("%w: %v", ErrCommandExecution, err)
	}

	return nil
}

func (s *LaravelProjectService) installTallStack(ctx *gin.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {
	projectDir := filepath.Join(req.ProjectPath, req.ProjectName)

	// Instala Livewire e TALL Preset
	cmds := []*exec.Cmd{
		exec.CommandContext(ctx, "composer", "require", "livewire/livewire", "laravel-frontend-presets/tall"),
		exec.CommandContext(ctx, "php", "artisan", "ui", "tall"),
	}

	if req.Auth {
		cmds = append(cmds, exec.CommandContext(ctx, "php", "artisan", "ui", "tall", "--auth"))
	}

	for _, cmd := range cmds {
		cmd.Dir = projectDir
		if err := s.executeCommand(cmd, projectID); err != nil {
			return fmt.Errorf("%w: %v", ErrCommandExecution, err)
		}
	}

	return nil
}

func (s *LaravelProjectService) createVILTProject(ctx *gin.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

	cmd := exec.CommandContext(ctx,
		"composer", "create-project", "laravel/vue-starter-kit",
		"--prefer-dist", req.ProjectName,
	)
	cmd.Dir = req.ProjectPath

	if err := s.executeCommand(cmd, projectID); err != nil {
		return fmt.Errorf("%w: %v", ErrCommandExecution, err)
	}

	return nil
}

func (s *LaravelProjectService) createRILTProject(ctx *gin.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

	cmd := exec.CommandContext(ctx,
		"composer", "create-project", "laravel/react-starter-kit",
		"--prefer-dist", req.ProjectName,
	)
	cmd.Dir = req.ProjectPath

	if err := s.executeCommand(cmd, projectID); err != nil {
		return fmt.Errorf("%w: %v", ErrCommandExecution, err)
	}

	return nil
}

func (s *LaravelProjectService) executeCommand(cmd *exec.Cmd, projectID uuid.UUID) error {
	_, err := cmd.CombinedOutput()
	if err != nil {
		return err
	}
	return nil
}

func (s *LaravelProjectService) CreateRepository(ctx *gin.Context, req request.CreateLaravelProjectRequest) error {

	repoData := map[string]interface{}{
		"name":    req.ProjectName,
		"private": false,
	}

	jsonBody, err := json.Marshal(repoData)
	if err != nil {
		return fmt.Errorf("erro ao gerar JSON: %w", err)
	}

	httpReq, err := http.NewRequest("POST", "https://api.github.com/user/repos", bytes.NewBuffer(jsonBody))
	if err != nil {
		return fmt.Errorf("erro ao criar requisição: %w", err)
	}

	httpReq.Header.Set("Authorization", "token "+req.GithubToken)
	httpReq.Header.Set("Content-Type", "application/json")

	client := &http.Client{}
	resp, err := client.Do(httpReq)
	if err != nil {
		return fmt.Errorf("erro ao fazer requisição: %w", err)
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return fmt.Errorf("erro ao ler resposta: %w", err)
	}

	if resp.StatusCode != http.StatusCreated {
		return fmt.Errorf("falha ao criar repositório: %s", string(body))
	}

	// fmt.Println("Repositório criado com sucesso:", string(body))
	return nil
}

func (s *LaravelProjectService) pushToGithub(ctx *gin.Context, req request.CreateLaravelProjectRequest) error {
	projectDir := filepath.Join(req.ProjectPath, req.ProjectName)
	repoURL := fmt.Sprintf("https://%s@github.com/%s/%s.git", req.GithubToken, req.GithubUsername, req.ProjectName)

	commands := [][]string{
		{"git", "init"},
		{"git", "add", "."},
		{"git", "commit", "-m", "Initial commit"},
		{"git", "branch", "-M", "main"},
		{"git", "remote", "add", "origin", repoURL},
		{"git", "push", "-u", "origin", "main"},
	}

	for _, cmd := range commands {
		c := exec.CommandContext(ctx, cmd[0], cmd[1:]...)
		c.Dir = projectDir
		output, err := c.CombinedOutput()
		if err != nil {
			return fmt.Errorf("erro ao executar %v: %v\n%s", cmd, err, output)
		}
	}

	return nil
}
