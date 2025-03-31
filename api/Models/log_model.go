package Models

import (
	"sync"

	"github.com/google/uuid"
)

type LogStore struct {
	mu   sync.Mutex
	logs map[uuid.UUID][]string
}

var sharedLogStore *LogStore

func NewLogStore() *LogStore {
	if sharedLogStore == nil {
		sharedLogStore = &LogStore{
			logs: make(map[uuid.UUID][]string),
		}
	}
	return sharedLogStore
}

func (ls *LogStore) AddLog(projectID uuid.UUID, message string) {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	ls.logs[projectID] = append(ls.logs[projectID], message)
}

func (ls *LogStore) GetLogs(projectID uuid.UUID) []string {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	logs := make([]string, 0, len(ls.logs[projectID]))
	logs = append(logs, ls.logs[projectID]...)

	return logs
}

func (ls *LogStore) EmptyLogs(projectID uuid.UUID) {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	ls.logs[projectID] = make([]string, 0)
}

func (ls *LogStore) ClearLogs(projectID uuid.UUID) {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	delete(ls.logs, projectID)
}

func (ls *LogStore) ClearAllLogs() {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	foreach := func(id uuid.UUID, logs []string) {
		delete(ls.logs, id)
	}

	for id := range ls.logs {
		foreach(id, ls.logs[id])
	}
}

func (ls *LogStore) ListProjectIDs() []uuid.UUID {
	ls.mu.Lock()
	defer ls.mu.Unlock()

	ids := make([]uuid.UUID, 0, len(ls.logs))
	for id := range ls.logs {
		ids = append(ids, id)
	}
	return ids
}
