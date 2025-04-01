package Utils

import (
	"encoding/json"
	"fmt"
	"net"
	"net/http"
	"os"
	"stacker-api/Icon"

	"github.com/getlantern/systray"
)

func FindAvailablePort(startPort string) string {
	if ln, err := net.Listen("tcp", ":"+startPort); err == nil {
		ln.Close()
		return startPort
	}

	ln, err := net.Listen("tcp", ":0")
	if err != nil {
		panic(fmt.Sprintf("Failed to find available port: %v", err))
	}
	defer ln.Close()

	_, port, _ := net.SplitHostPort(ln.Addr().String())
	return port
}

func ChangePort(port string) {

}

func RespondWithError(w http.ResponseWriter, statusCode int, message string) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(statusCode)
	json.NewEncoder(w).Encode(struct {
		Error string `json:"error"`
	}{Error: message})
}

var (
	statusPortCh = make(chan string, 1)
)

func SetupTray() {
	systray.Run(onReady, onExit)
}

func onExit() {
	fmt.Println("Saindo da aplicação...")
}

func onReady() {
	systray.SetTitle("Servidor API")
	systray.SetTooltip("Iniciando serviço...")
	systray.SetTemplateIcon(Icon.Data, Icon.Data)

	go func() {
		port := <-statusPortCh
		SetCurrentPort(port)
		systray.SetTooltip(fmt.Sprintf("Serviço rodando na porta %s", port))
	}()

	menuItems := struct {
		toggleStatus *systray.MenuItem
		exit         *systray.MenuItem
	}{
		toggleStatus: systray.AddMenuItemCheckbox("Status", "Status", true),
		exit:         systray.AddMenuItem("Exit", "Sair"),
	}

	go handleTrayActions(menuItems.toggleStatus, menuItems.exit)
}

func handleTrayActions(toggleStatus, exit *systray.MenuItem) {
	for {
		select {
		// Toggle Button
		case <-toggleStatus.ClickedCh:
			statusCheck(toggleStatus)

		// Exit Button
		case <-exit.ClickedCh:
			os.Exit(0)
		}
	}
}

func statusCheck(toggleStatus *systray.MenuItem) {
	if toggleStatus.Checked() {
		toggleStatus.Uncheck()
	} else {
		toggleStatus.Check()
	}
}
