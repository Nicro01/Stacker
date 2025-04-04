package services

import (
	"context"
	"errors"
	"fmt"
	"os"
	"os/exec"
	"path/filepath"

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

func (s *LaravelProjectService) CreateProject(ctx context.Context, req request.CreateLaravelProjectRequest) error {
	// Verifica se o diretório já existe
	projectDir := filepath.Join(req.ProjectPath, req.ProjectName)
	if _, err := os.Stat(projectDir); err == nil {
		return ErrProjectExists
	}

	// Cria projeto base
	projectID := uuid.New()
	if req.Stack != "3" && req.Stack != "4" {
		if err := s.createBaseProject(ctx, req, projectID); err != nil {
			return err
		}
	}

	// Instala stack específica
	switch req.Stack {
	case "1": // Laravel Default
		return nil
	case "2": // TALL Stack
		return s.installTallStack(ctx, req, projectID)
	case "3":
		return s.createVILTProject(ctx, req, projectID)
	case "4":
		return s.createRILTProject(ctx, req, projectID)
	default:
		return ErrInvalidStack
	}
}

func (s *LaravelProjectService) createBaseProject(ctx context.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

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

func (s *LaravelProjectService) installTallStack(ctx context.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {
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

func (s *LaravelProjectService) createVILTProject(ctx context.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

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

func (s *LaravelProjectService) createRILTProject(ctx context.Context, req request.CreateLaravelProjectRequest, projectID uuid.UUID) error {

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
