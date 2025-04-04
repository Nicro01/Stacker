package handlers

import (
	"errors"
	"net/http"

	"github.com/gin-gonic/gin"
	"github.com/guifaraco/stacker-api/internal/dto/request"
	"github.com/guifaraco/stacker-api/internal/services"
	"github.com/guifaraco/stacker-api/pkg/utils"
)

type ProjectHandler struct {
	service services.ProjectService
}

func NewProjectHandler(service services.ProjectService) *ProjectHandler {
	return &ProjectHandler{service: service}
}

func (h *ProjectHandler) CreateProject(c *gin.Context) {
	var req request.CreateProjectRequest

	// Bind e validação do request
	if err := c.ShouldBindJSON(&req); err != nil {
		utils.APIError(c, http.StatusBadRequest, "Formato de requisição inválido")
		return
	}

	// Validação customizada
	if validationErr := validateCreateRequest(req); validationErr != nil {
		utils.APIError(c, http.StatusBadRequest, validationErr.Error())
		return
	}

	// Criação do projeto
	if err := h.service.CreateProject(c.Request.Context(), req); err != nil {
		handleProjectServiceError(c, err)
		return
	}

	c.JSON(http.StatusCreated, gin.H{
		"status":  "success",
		"message": "Projeto criado com sucesso",
	})
}

func validateCreateRequest(req request.CreateProjectRequest) error {
	if req.ProjectPath == "" || req.ProjectName == "" {
		return utils.NewValidationError("ProjectPath e ProjectName são obrigatórios")
	}
	return nil
}

func handleProjectServiceError(c *gin.Context, err error) {
	switch {
	case errors.Is(err, services.ErrProjectExists):
		utils.APIError(c, http.StatusConflict, "Projeto já existe")
	case errors.Is(err, services.ErrInvalidStack):
		utils.APIError(c, http.StatusBadRequest, "Stack não suportada")
	case errors.Is(err, services.ErrCommandExecution):
		utils.APIError(c, http.StatusInternalServerError, "Erro na execução do comando")
	default:
		utils.APIError(c, http.StatusInternalServerError, "Erro ao criar projeto")
	}
}
