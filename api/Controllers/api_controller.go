package Controllers

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"path/filepath"
	"stacker-api/Models"
	"stacker-api/Services"
	"stacker-api/Utils"
)

func StatusHandler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, `{"status": "running", "port": "%s"}`, Utils.GetCurrentPort())
}

func CreateProjectHandler(w http.ResponseWriter, r *http.Request) {
	body, err := io.ReadAll(r.Body)
	if err != nil {
		fmt.Printf("Erro ao ler o corpo da requisição: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Erro ao ler o corpo da requisição")
		return
	}
	defer r.Body.Close()

	var requestData *Models.RequestData

	if err := json.Unmarshal(body, &requestData); err != nil {
		fmt.Printf("Erro ao fazer unmarshal do JSON: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Formato JSON inválido")
		return
	}

	if requestData.ProjectPath == "" || requestData.ProjectName == "" {
		fmt.Printf("ProjectPath e ProjectName são obrigatórios\n")
		Utils.RespondWithError(w, http.StatusBadRequest, "ProjectPath e ProjectName são obrigatórios")
		return
	}

	if _, err := os.Stat(requestData.ProjectPath); os.IsNotExist(err) {
		fmt.Printf("ProjectPath não existe\n")
		Utils.RespondWithError(w, http.StatusBadRequest, "ProjectPath não existe")
		return
	}

	projectDir := filepath.Join(requestData.ProjectPath, requestData.ProjectName)
	if _, err := os.Stat(projectDir); err == nil {
		fmt.Printf("Um projeto com este nome já existe no caminho especificado\n")
		Utils.RespondWithError(w, http.StatusConflict, "Um projeto com este nome já existe no caminho especificado")
		return
	}

	if err := Services.CreateLaravelProject(requestData); err != nil {
		fmt.Printf("Falha ao executar o comando: %s\n", err.Error())
		Utils.RespondWithError(w, http.StatusInternalServerError, fmt.Sprintf("Falha ao executar o comando: %s", err.Error()))
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	fmt.Fprint(w, `{"status": "ok"}`)
}
