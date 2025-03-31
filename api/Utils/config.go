package Utils

import "sync"

var (
	currentPort string
	portMutex   sync.RWMutex
)

func SetCurrentPort(port string) {
	portMutex.Lock()
	defer portMutex.Unlock()
	currentPort = port
}

func GetCurrentPort() string {
	portMutex.RLock()
	defer portMutex.RUnlock()
	return currentPort
}
