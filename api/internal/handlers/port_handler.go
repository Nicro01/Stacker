package handlers

import (
	"github.com/gin-gonic/gin"
	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/dto/request"
)

type PortHandler struct {
	cfg *config.ServerConfig
}

func NewPortHandler(cfg *config.ServerConfig) *PortHandler {
	return &PortHandler{cfg: cfg}
}

func (h *PortHandler) ChangePort(ctx *gin.Context) {
	portRequest := request.PortRequest{}

	if err := ctx.ShouldBindJSON(&portRequest); err != nil {
		ctx.JSON(400, gin.H{"error": "dados inválidos"})
		return
	}

	if err := h.cfg.SetPort(portRequest.Port); err != nil {
		ctx.JSON(400, gin.H{"error": err.Error()})
		return
	}

	ctx.JSON(200, gin.H{"new_port": h.cfg.GetPort()})
}
