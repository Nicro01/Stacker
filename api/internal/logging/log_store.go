package logging

import (
	"sync"

	"github.com/gin-gonic/gin"
	"github.com/google/uuid"
)

type LogStore struct {
	mu   sync.RWMutex
	logs map[uuid.UUID][]string
}

func NewLogStore() *LogStore {
	return &LogStore{
		logs: make(map[uuid.UUID][]string),
	}
}

// Middleware para injetar o LogStore no contexto do Gin
func (ls *LogStore) Middleware() gin.HandlerFunc {
	return func(c *gin.Context) {
		c.Set("logStore", ls)
		c.Next()
	}
}

// Método para obter o LogStore do contexto
func FromContext(c *gin.Context) *LogStore {
	return c.MustGet("logStore").(*LogStore)
}

func (ls *LogStore) AddLog(projectID uuid.UUID, message string) {
	ls.mu.Lock()
	defer ls.mu.Unlock()
	ls.logs[projectID] = append(ls.logs[projectID], message)
}

func (ls *LogStore) GetLogs(projectID uuid.UUID) []string {
	ls.mu.RLock()
	defer ls.mu.RUnlock()
	return append([]string(nil), ls.logs[projectID]...)
}

func (ls *LogStore) ClearLogs(projectID uuid.UUID) {
	ls.mu.Lock()
	defer ls.mu.Unlock()
	delete(ls.logs, projectID)
}

// Handler para expor logs via API
func (ls *LogStore) GetLogsHandler(c *gin.Context) {
	projectID, err := uuid.Parse(c.Param("projectID"))
	if err != nil {
		c.AbortWithStatusJSON(400, gin.H{"error": "ID de projeto inválido"})
		return
	}

	logs := ls.GetLogs(projectID)
	c.JSON(200, gin.H{
		"project_id": projectID,
		"logs":       logs,
	})
}
