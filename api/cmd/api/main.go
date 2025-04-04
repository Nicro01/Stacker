package main

import (
	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/server"
	"github.com/guifaraco/stacker-api/internal/tray"
)

func main() {
	cfg := config.GetServerConfig()
	srv := server.NewServer(cfg)
	t := tray.NewTray(cfg, srv)

	go t.Start()

	// Mantém a aplicação rodando
	select {}
}
