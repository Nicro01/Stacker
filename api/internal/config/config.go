package config

import (
	"fmt"
	"os"
	"strconv"
	"sync"
)

type ServerConfig struct {
	port int
	mu   sync.RWMutex
}

var (
	serverConfig *ServerConfig
	once         sync.Once
)

// GetServerConfig retorna a instância singleton do ServerConfig
func GetServerConfig() *ServerConfig {
	once.Do(func() {
		serverConfig = &ServerConfig{
			port: loadPortFromEnv(), // Carrega do ambiente ou usa default
		}
	})
	return serverConfig
}

func init() {
	// Inicializa com valor padrão ou do ambiente
	serverConfig = &ServerConfig{
		port: loadPortFromEnv(), // Carrega do ambiente ou usa default
	}
}

// GetPort retorna a porta atual (thread-safe)
func (c *ServerConfig) GetPort() int {
	c.mu.RLock()
	defer c.mu.RUnlock()
	return c.port
}

// SetPort atualiza a porta (thread-safe)
func (c *ServerConfig) SetPort(newPort int) error {
	if newPort < 1 || newPort > 65535 {
		return fmt.Errorf("porta inválida: %d", newPort)
	}
	c.mu.Lock()
	defer c.mu.Unlock()
	c.port = newPort
	return nil
}

// loadPortFromEnv carrega a porta do ambiente ou usa default
func loadPortFromEnv() int {
	portStr := os.Getenv("SERVER_PORT")
	if portStr == "" {
		return 9025 // Valor padrão
	}
	port, err := strconv.Atoi(portStr)
	if err != nil {
		return 9025
	}
	return port
}
