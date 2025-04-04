package handlers

import (
	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/services"
)

type HandlerContainer struct {
	PortHandler      *PortHandler
	StatusHandler    *StatusHandler
	WorkspaceHandler *WorkspaceHandler
	ProjectHandler   *LaravelProjectHandler
}

func NewHandlerContainer(
	cfg *config.ServerConfig,
) *HandlerContainer {
	return &HandlerContainer{
		PortHandler:      NewPortHandler(cfg),
		StatusHandler:    NewStatusHandler(cfg),
		WorkspaceHandler: NewWorkspaceHandler(services.NewWorkspaceService()),
		ProjectHandler:   NewLaravelProjectHandler(*services.NewLaravelProjectService()),
	}
}
