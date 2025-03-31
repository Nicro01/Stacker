package Models

type RequestData struct {
	ProjectPath string `json:"projectPath"`
	ProjectName string `json:"projectName"`
	Stack       string `json:"stack"`
	Auth        bool   `json:"auth"`
}
