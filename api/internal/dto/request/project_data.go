package request

type CreateProjectRequest struct {
	ProjectPath string `json:"projectPath" binding:"required"`
	ProjectName string `json:"projectName" binding:"required"`
	Stack       string `json:"stack" binding:"required"`
	Auth        bool   `json:"auth"`
}
