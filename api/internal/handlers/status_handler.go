package handlers

import (
	"github.com/gin-gonic/gin"
	"github.com/guifaraco/stacker-api/internal/config"
)

type StatusHandler struct {
	cfg *config.ServerConfig
}

func NewStatusHandler(cfg *config.ServerConfig) *StatusHandler {
	return &StatusHandler{cfg: cfg}
}

func (h *StatusHandler) PrintStatus(ctx *gin.Context) {
	port := h.cfg.GetPort()
	ctx.JSON(200, gin.H{"status": "running", "port": port})
}
