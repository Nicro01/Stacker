package Routes

import (
	"net/http"
	"stacker-api/Controllers"
	"stacker-api/Models"
)

func Setup() {
	http.HandleFunc("/api/status", Controllers.StatusHandler)
	http.HandleFunc("/api/create-project", Controllers.CreateProjectHandler)
	http.HandleFunc("/api/get-folders", Controllers.GetFoldersHandler)
	http.HandleFunc("/api/change-port", Controllers.ChangePortHandler)

	logStore := Models.NewLogStore()
	logController := Controllers.NewLogController(logStore)

	http.HandleFunc("/api/logs", logController.HandleLogs)

	http.HandleFunc("/api/project-ids", logController.ListProjectIDs)
}
