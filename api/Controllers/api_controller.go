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

	var requestData *Models.RequestProjectData

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

func GetFoldersHandler(w http.ResponseWriter, r *http.Request) {
	body, err := io.ReadAll(r.Body)
	if err != nil {
		fmt.Printf("Erro ao ler o corpo da requisição: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Erro ao ler o corpo da requisição")
		return
	}
	defer r.Body.Close()

	var requestData *Models.RequestFolders

	if err := json.Unmarshal(body, &requestData); err != nil {
		fmt.Printf("Erro ao fazer unmarshal do JSON: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Formato JSON inválido")
		return
	}

	if requestData.WorkspacePath == "" {
		fmt.Printf("Workspace é obrigatório\n")
		Utils.RespondWithError(w, http.StatusBadRequest, "Workspace é obrigatório")
		return
	}

	if _, err := os.Stat(requestData.WorkspacePath); os.IsNotExist(err) {
		fmt.Printf("WorkspacePath não existe\n")
		Utils.RespondWithError(w, http.StatusBadRequest, "WorkspacePath não existe")
		return
	}

	if folders, err := Services.ListFoldersInWorkspace(requestData.WorkspacePath); err != nil {
		fmt.Printf("Erro ao listar as pastas no workspace: %v\n", err)
		Utils.RespondWithError(w, http.StatusInternalServerError, "Erro ao listar as pastas no workspace")
		return
	} else {
		response := struct {
			Status  string   `json:"status"`
			Folders []string `json:"folders"`
		}{
			Status:  "ok",
			Folders: folders,
		}

		w.Header().Set("Content-Type", "application/json")
		w.WriteHeader(http.StatusOK)
		if err := json.NewEncoder(w).Encode(response); err != nil {
			fmt.Printf("Erro ao codificar a resposta: %v\n", err)
			Utils.RespondWithError(w, http.StatusInternalServerError, "Erro ao processar a resposta")
		}
	}
}

func ChangePortHandler(w http.ResponseWriter, r *http.Request) {
	body, err := io.ReadAll(r.Body)
	if err != nil {
		fmt.Printf("Erro ao ler o corpo da requisição: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Erro ao ler o corpo da requisição")
		return
	}
	defer r.Body.Close()

	var requestData *Models.RequestPort

	if err := json.Unmarshal(body, &requestData); err != nil {
		fmt.Printf("Erro ao fazer unmarshal do JSON: %v\n", err)
		Utils.RespondWithError(w, http.StatusBadRequest, "Formato JSON inválido")
		return
	}

	if requestData.Port == "" {
		fmt.Printf("A porta é obrigatória\n")
		Utils.RespondWithError(w, http.StatusBadRequest, "A porta é obrigatória")
		return
	}

	if err := Services.ChangePort(requestData.Port); err != nil {
		fmt.Printf("Erro ao alterar a porta: %v\n", err)
		Utils.RespondWithError(w, http.StatusInternalServerError, "Erro ao alterar a porta")
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(http.StatusOK)
	fmt.Fprint(w, `{"status": "ok"}`)
}
