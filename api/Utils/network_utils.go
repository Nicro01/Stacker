package Utils

import (
	"encoding/json"
	"fmt"
	"net"
	"net/http"
)

func FindAvailablePort(startPort string) string {
	if ln, err := net.Listen("tcp", ":"+startPort); err == nil {
		ln.Close()
		return startPort
	}

	ln, err := net.Listen("tcp", ":0")
	if err != nil {
		panic(fmt.Sprintf("Failed to find available port: %v", err))
	}
	defer ln.Close()

	_, port, _ := net.SplitHostPort(ln.Addr().String())
	return port
}

func RespondWithError(w http.ResponseWriter, statusCode int, message string) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(statusCode)
	json.NewEncoder(w).Encode(struct {
		Error string `json:"error"`
	}{Error: message})
}
