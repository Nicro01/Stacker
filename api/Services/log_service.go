package Services

import (
	"stacker-api/Models"
)

type CommandRunner struct {
	Store *Models.LogStore
}

// func NewCommandRunner(store *Models.LogStore) *CommandRunner {
// 	return &CommandRunner{Store: store}
// }

// func (cr *CommandRunner) Execute(projectID uuid.UUID, command string) error {
// 	cmd := exec.Command("sh", "-c", command)

// 	emitter := cr.Store.CreateEmitter(projectID)
// 	defer cr.Store.RemoveEmitter(projectID)

// 	stdout, err := cmd.StdoutPipe()
// 	if err != nil {
// 		return fmt.Errorf("error creating stdout pipe: %v", err)
// 	}
// 	go func() {
// 		buf := make([]byte, 1024)
// 		for {
// 			n, err := stdout.Read(buf)
// 			if n > 0 {
// 				emitter <- string(buf[:n])
// 				cr.Store.AddLog(projectID, string(buf[:n]))
// 			}
// 			if err != nil {
// 				break
// 			}
// 		}
// 	}()

// 	stderr, err := cmd.StderrPipe()
// 	if err != nil {
// 		return fmt.Errorf("error creating stderr pipe: %v", err)
// 	}
// 	go func() {
// 		buf := make([]byte, 1024)
// 		for {
// 			n, err := stderr.Read(buf)
// 			if n > 0 {
// 				emitter <- string(buf[:n])
// 				cr.Store.AddLog(projectID, string(buf[:n]))
// 			}
// 			if err != nil {
// 				break
// 			}
// 		}
// 	}()

// 	if err := cmd.Start(); err != nil {
// 		return fmt.Errorf("error starting command: %v", err)
// 	}

// 	if err := cmd.Wait(); err != nil {
// 		return fmt.Errorf("command execution failed: %v", err)
// 	}

// 	return nil
// }
