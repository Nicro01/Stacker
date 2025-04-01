package Models

type RequestProjectData struct {
	ProjectPath string `json:"projectPath"`
	ProjectName string `json:"projectName"`
	Stack       string `json:"stack"`
	Auth        bool   `json:"auth"`
}

type RequestFolders struct {
	WorkspacePath string `json:"path"`
}

type RequestPort struct {
	Port string `json:"port"`
}
