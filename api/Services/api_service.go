package Services

import (
	"bufio"
	"context"
	"errors"
	"fmt"
	"os"
	"os/exec"
	"runtime"
	"stacker-api/Models"
	"time"

	"github.com/google/uuid"
)

var logStore = Models.NewLogStore()

func CreateLaravelProject(requestData *Models.RequestProjectData) error {
	projectID := uuid.New()
	logMessage := fmt.Sprintf("Starting project creation: %s/%s", requestData.ProjectPath, requestData.ProjectName)

	logStore.AddLog(projectID, logMessage)

	fullpath := requestData.ProjectPath + "/" + requestData.ProjectName

	if err := createBaseProject(requestData.ProjectPath, requestData.ProjectName, projectID); err != nil {
		return err
	} else {
		logStore.AddLog(projectID, "Base project created")
		time.Sleep(3 * time.Second)
		logStore.EmptyLogs(projectID)
	}

	switch requestData.Stack {
	case "1": // LARAVEL DEFAULT
		return nil
	case "2": // TALL STACK
		return installTallStack(fullpath, requestData.Auth, projectID)
	default:
		return errors.New("unsupported stack")
	}
}

func createBaseProject(projectPath, projectName string, projectID uuid.UUID) error {
	command := "cd " + projectPath + " && composer create-project laravel/laravel --prefer-dist " + projectName

	return executeCommand(context.Background(), command, projectID)
}

func installTallStack(fullpath string, auth bool, projectID uuid.UUID) error {
	ctx := context.Background()

	if err := executeCommand(ctx, "cd "+fullpath+" && composer require livewire/livewire laravel-frontend-presets/tall", projectID); err != nil {
		return err
	} else {
		logStore.AddLog(projectID, "Tall stack installed")
	}

	command := "php artisan ui tall"
	if auth {
		command += " --auth"
	}

	executeCommand(ctx, "cd "+fullpath+" && "+command, projectID)

	time.Sleep(3 * time.Second)
	logStore.ClearLogs(projectID)

	return nil
}

func ListFoldersInWorkspace(workspacePath string) ([]string, error) {
	var folders []string

	entries, err := os.ReadDir(workspacePath)

	for _, entry := range entries {
		if entry.IsDir() {
			folders = append(folders, entry.Name())
		}
	}
	return folders, err
}

func executeCommand(ctx context.Context, command string, projectID uuid.UUID) error {
	var cmd *exec.Cmd

	if runtime.GOOS == "windows" {
		cmd = exec.CommandContext(ctx, "cmd.exe", "/C", command)
	} else {
		cmd = exec.CommandContext(ctx, "bash", "-c", command)
	}

	stdout, err := cmd.StdoutPipe()
	if err != nil {
		return fmt.Errorf("failed to capture stdout: %w", err)
	}

	stderr, err := cmd.StderrPipe()
	if err != nil {
		return fmt.Errorf("failed to capture stderr: %w", err)
	}

	if err := cmd.Start(); err != nil {
		return fmt.Errorf("failed to start command: %w", err)
	}

	go func() {
		scanner := bufio.NewScanner(stdout)
		for scanner.Scan() {
			line := scanner.Text()
			logStore.AddLog(projectID, line)
		}
	}()

	go func() {
		scanner := bufio.NewScanner(stderr)
		for scanner.Scan() {
			line := scanner.Text()
			logStore.AddLog(projectID, "ERROR: "+line)
		}
	}()

	if err := cmd.Wait(); err != nil {
		return fmt.Errorf("command execution failed: %w", err)
	}

	return nil
}
