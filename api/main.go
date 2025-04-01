package main

import (
	"flag"
	"fmt"
	"net/http"
	"os"
	"stacker-api/Icon"
	"stacker-api/Routes"
	"stacker-api/Utils"

	"github.com/getlantern/systray"
)

var (
	statusPortCh = make(chan string, 1)
)

func setupTray() {
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
		Utils.SetCurrentPort(port)
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

func main() {
	port := flag.String("port", "9025", "Número da porta inicial para o servidor API")
	flag.Parse()

	go func() {
		// actualPort := Utils.FindAvailablePort(*port)
		// statusPortCh <- actualPort
		actualPort := *port
		Utils.SetCurrentPort(actualPort)

		Routes.Setup()

		fmt.Printf("Iniciando servidor na porta %s\n", actualPort)

		if err := http.ListenAndServe(":"+actualPort, nil); err != nil {
			fmt.Printf("Erro no servidor: %v\n", err)
		}
	}()

	setupTray()
}
