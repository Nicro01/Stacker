package server

import (
	"context"
	"net/http"
	"strconv"
	"sync"

	"github.com/gin-gonic/gin"
	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/handlers"
)

type Server struct {
	cfg     *config.ServerConfig
	router  *gin.Engine
	server  *http.Server
	running bool
	mu      sync.RWMutex
}

func NewServer(cfg *config.ServerConfig) *Server {
	handlers := handlers.NewHandlerContainer(cfg)
	return &Server{
		cfg:    cfg,
		router: SetupRouter(handlers),
	}
}

// IsRunning retorna o estado atual do servidor (thread-safe)
func (s *Server) IsRunning() bool {
	s.mu.RLock()
	defer s.mu.RUnlock()
	return s.running
}

// Start inicia o servidor (thread-safe)
func (s *Server) Start() error {
	s.mu.Lock()
	defer s.mu.Unlock()

	if s.running {
		return nil // Já está rodando
	}

	s.server = &http.Server{
		Addr:    ":" + strconv.Itoa(s.cfg.GetPort()),
		Handler: s.router,
	}

	go func() {
		if err := s.server.ListenAndServe(); err != nil && err != http.ErrServerClosed {
			panic(err)
		}
	}()

	s.running = true
	return nil
}

// Stop encerra o servidor (thread-safe)
func (s *Server) Stop() error {
	s.mu.Lock()
	defer s.mu.Unlock()

	if !s.running {
		return nil // Já está parado
	}

	if err := s.server.Shutdown(context.Background()); err != nil {
		return err
	}

	s.running = false
	return nil
}

func SetupRouter(handlers *handlers.HandlerContainer) *gin.Engine {
	router := gin.Default()
	api := router.Group("/api")

	api.GET("/status", handlers.StatusHandler.PrintStatus)
	api.POST("/create-project", handlers.ProjectHandler.CreateProject)
	api.POST("/change-port", handlers.PortHandler.ChangePort)
	api.POST("/get-folders", handlers.WorkspaceHandler.ListFolders)

	return router
}
