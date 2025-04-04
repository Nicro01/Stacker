// internal/tray/tray.go
package tray

import (
	"fmt"
	"net"
	"os"
	"strconv"
	"sync"
	"time"

	"github.com/gen2brain/beeep"
	"github.com/getlantern/systray"
	"github.com/guifaraco/stacker-api/internal/config"
	"github.com/guifaraco/stacker-api/internal/server"
	"github.com/ncruces/zenity"
)

type Tray struct {
	cfg        *config.ServerConfig
	server     *server.Server
	mu         sync.Mutex
	statusItem *systray.MenuItem
	portItem   *systray.MenuItem
	exitItem   *systray.MenuItem
}

func NewTray(cfg *config.ServerConfig, srv *server.Server) *Tray {
	return &Tray{
		cfg:    cfg,
		server: srv,
	}
}

func (t *Tray) Start() {
	systray.Run(t.onReady, t.onExit)
}

func (t *Tray) onReady() {
	systray.SetIcon(Data) // Use a variável Icon com os bytes do ícone
	systray.SetTitle("API Server")
	systray.SetTooltip("Controle do Servidor")
	t.server.Start()
	// Itens do menu
	t.statusItem = systray.AddMenuItemCheckbox(
		"Iniciar Servidor",
		"Controla o estado do servidor",
		true,
	)
	t.portItem = systray.AddMenuItem(
		fmt.Sprintf("Porta: %d", t.cfg.GetPort()),
		"Porta atual do servidor",
	)
	systray.AddSeparator()
	t.exitItem = systray.AddMenuItem("Sair", "Encerrar aplicação")

	// Atualizações dinâmicas
	go t.watchServerPort()
	go t.handleActions()
}

func (t *Tray) onExit() {
	t.mu.Lock()
	defer t.mu.Unlock()

	if t.server.IsRunning() {
		if err := t.server.Stop(); err != nil {
			fmt.Printf("Erro ao parar servidor: %v\n", err)
		}
	}
	fmt.Println("Aplicação encerrada")
	os.Exit(0)
}

func (t *Tray) handleActions() {
	for {
		select {
		case <-t.statusItem.ClickedCh:
			t.toggleServer()

		case <-t.portItem.ClickedCh:
			go t.handlePortChange()

		case <-t.exitItem.ClickedCh:
			systray.Quit()
			return
		}
	}
}

// Atualize o toggleServer para usar IsRunning()
func (t *Tray) toggleServer() {
	t.mu.Lock()
	defer t.mu.Unlock()

	if t.server.IsRunning() {
		if err := t.server.Stop(); err != nil {
			t.showError("Erro", fmt.Sprintf("Falha ao parar: %v", err))
			return
		}
		t.statusItem.Uncheck()
		systray.SetTooltip("Servidor parado")
	} else {
		if err := t.server.Start(); err != nil {
			t.showError("Erro", fmt.Sprintf("Falha ao iniciar: %v", err))
			return
		}
		t.statusItem.Check()
		systray.SetTooltip(fmt.Sprintf("Rodando na porta %d", t.cfg.GetPort()))
	}
}

// Atualize o watchServerPort para refletir o estado real
func (t *Tray) watchServerPort() {
	ticker := time.NewTicker(2 * time.Second)
	defer ticker.Stop()

	for range ticker.C {
		currentPort := t.cfg.GetPort()
		status := "parado"
		if t.server.IsRunning() {
			status = "rodando"
		}
		t.portItem.SetTitle(fmt.Sprintf("Porta: %d (%s)", currentPort, status))
	}
}

func (t *Tray) showError(title, message string) {
	// Notificação nativa
	if err := beeep.Alert(title, message, ""); err != nil {
		fmt.Printf("Erro na notificação: %v\n", err)
	}

	// Log no console
	fmt.Printf("[ERRO] %s: %s\n", title, message)
}
func (t *Tray) handlePortChange() {
	// Abre diálogo de input
	newPortStr, err := zenity.Entry(
		"Digite a nova porta:",
		zenity.Title("Alterar Porta do Servidor"),
		zenity.EntryText(fmt.Sprintf("%d", t.cfg.GetPort())),
	)
	if err != nil || newPortStr == "" {
		return // Usuário cancelou
	}

	// Converte para número
	newPort, err := strconv.Atoi(newPortStr)
	if err != nil {
		t.showError("Porta Inválida", "Digite apenas números!")
		return
	}

	// Valida faixa da porta
	if newPort < 1 || newPort > 65535 {
		t.showError("Porta Inválida", "A porta deve estar entre 1 e 65535")
		return
	}

	if isPortInUse(newPort) {
		t.showError("Porta Ocupada", "A porta já está em uso por outro programa")
		return
	}
	// Atualiza configuração
	t.cfg.SetPort(newPort)

	// Reinicia servidor se estiver rodando
	if t.server.IsRunning() {
		t.server.Stop()
		if err := t.server.Start(); err != nil {
			t.showError("Erro", fmt.Sprintf("Falha ao reiniciar: %v", err))
			return
		}
	}

	// Atualiza UI
	systray.SetTooltip(fmt.Sprintf("Servidor na porta %d", newPort))
	t.portItem.SetTitle(fmt.Sprintf("Porta: %d", newPort))
	t.showNotification("Porta Alterada", fmt.Sprintf("Nova porta: %d", newPort))
}

func isPortInUse(port int) bool {
	conn, err := net.Listen("tcp", fmt.Sprintf(":%d", port))
	if err != nil {
		return true
	}
	conn.Close()
	return false
}

func (t *Tray) showNotification(title, message string) {
	// Notificação nativa (usando zenity ou beeep)
	zenity.Info(
		message,
		zenity.Title(title),
		zenity.InfoIcon,
	)
}
