package Controllers

import (
	"encoding/json"
	"fmt"
	"net/http"

	"stacker-api/Models"

	"github.com/google/uuid"
)

type LogController struct {
	LogStore *Models.LogStore
}

func NewLogController(store *Models.LogStore) *LogController {
	return &LogController{LogStore: store}
}

func (lc *LogController) HandleLogs(w http.ResponseWriter, r *http.Request) {
	idStr := r.URL.Query().Get("id")
	id, err := uuid.Parse(idStr)
	if err != nil {
		http.Error(w, "Invalid project ID", http.StatusBadRequest)
		return
	}

	logs := lc.LogStore.GetLogs(id)
	json.NewEncoder(w).Encode(map[string]interface{}{
		"logs": logs,
	})
}

func (lc *LogController) ListProjectIDs(w http.ResponseWriter, r *http.Request) {
	projectIDs := lc.LogStore.ListProjectIDs()

	fmt.Printf("Lista de IDs de projetos: %v\n", projectIDs)

	json.NewEncoder(w).Encode(map[string]interface{}{
		"projectIDs": projectIDs,
	})
}
