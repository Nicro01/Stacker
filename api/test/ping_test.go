package test

import (
	"fmt"
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/handlers"
	"github.com/guifaraco/stacker-api/internal/server"
	"github.com/stretchr/testify/assert"
)

func TestPingRoute(t *testing.T) {
	cfg := config.GetServerConfig()
	handlers := handlers.NewHandlerContainer(cfg)
	router := server.SetupRouter(handlers)

	w := httptest.NewRecorder()

	req, _ := http.NewRequest(http.MethodGet, "/api/status", nil)

	router.ServeHTTP(w, req)

	port := config.GetServerConfig().GetPort()
	msg := fmt.Sprintf(`{"port":%d, "status":"running"}`, port)

	assert.Equal(t, 200, w.Code, "API should return 200")
	assert.JSONEqf(t, msg, w.Body.String(), "API should return the port")
}
