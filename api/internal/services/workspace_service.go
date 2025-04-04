// internal/services/workspace_service.go
package services

import (
	"errors"
	"os"
)

var (
	ErrEmptyWorkspacePath = errors.New("caminho do workspace não pode ser vazio")
	ErrInvalidWorkspace   = errors.New("workspace inválido ou não encontrado")
)

type WorkspaceService interface {
	ListFoldersInWorkspace(path string) ([]string, error)
}

type workspaceService struct{}

func NewWorkspaceService() WorkspaceService {
	return &workspaceService{}
}

func (s *workspaceService) ListFoldersInWorkspace(workspacePath string) ([]string, error) {
	// Validação básica
	if workspacePath == "" {
		return nil, ErrEmptyWorkspacePath
	}

	// Verifica se o caminho existe
	if _, err := os.Stat(workspacePath); os.IsNotExist(err) {
		return nil, ErrInvalidWorkspace
	}

	// Lê o diretório
	entries, err := os.ReadDir(workspacePath)
	if err != nil {
		return nil, err
	}

	// Filtra apenas diretórios
	var folders []string
	for _, entry := range entries {
		if entry.IsDir() {
			// Retorna caminho absoluto
			folders = append(folders, entry.Name())
		}
	}

	return folders, nil
}
