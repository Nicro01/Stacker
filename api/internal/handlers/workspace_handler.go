// internal/handlers/workspace_handler.go
package handlers

import (
	"errors"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/guifaraco/stacker-api/internal/dto/request"
	"github.com/guifaraco/stacker-api/internal/services"
)

type WorkspaceHandler struct {
	service services.WorkspaceService
}

func NewWorkspaceHandler(service services.WorkspaceService) *WorkspaceHandler {
	return &WorkspaceHandler{service: service}
}

func (h *WorkspaceHandler) ListFolders(c *gin.Context) {
	var req request.WorkspaceRequest

	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Formato de requisição inválido"})
		return
	}

	folders, err := h.service.ListFoldersInWorkspace(req.WorkspacePath)
	if err != nil {
		handleWorkspaceServiceError(c, err)
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"folders": folders,
	})
}

func handleWorkspaceServiceError(c *gin.Context, err error) {
	switch {
	case errors.Is(err, services.ErrEmptyWorkspacePath):
		c.JSON(http.StatusBadRequest, gin.H{"error": "Caminho do workspace é obrigatório"})
	case errors.Is(err, services.ErrInvalidWorkspace):
		c.JSON(http.StatusNotFound, gin.H{"error": "Workspace não encontrado"})
	default:
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Erro ao processar a requisição"})
	}
}
