package request

type CreateLaravelProjectRequest struct {
	ProjectPath string `json:"projectPath" binding:"required"`
	ProjectName string `json:"projectName" binding:"required"`
	Stack       int    `json:"stack" binding:"required"`
	Auth        bool   `json:"auth"`
}
