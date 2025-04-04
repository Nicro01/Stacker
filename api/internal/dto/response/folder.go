package response

type ListFoldersResponse struct {
	Status  string   `json:"status"`
	Folders []string `json:"folders"`
}
