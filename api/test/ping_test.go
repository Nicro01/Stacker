package test

import (
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/guifaraco/stacker-api/internal/server"
	"github.com/stretchr/testify/assert"
)

func TestPingRoute(t *testing.T) {
	router := server.SetupRouter()

	w := httptest.NewRecorder()

	req, _ := http.NewRequest(http.MethodGet, "/api/status", nil)

	router.ServeHTTP(w, req)

	assert.Equal(t, 200, w.Code, "API should return 200")
	assert.JSONEqf(t, `{"message":"pong"}`, w.Body.String(), "API should return 'pong'")
}
