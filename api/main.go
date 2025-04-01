package main

import (
	"flag"
	"fmt"
	"net/http"
	"stacker-api/Routes"
	"stacker-api/Utils"

	"github.com/rs/cors"
)

func main() {
	port := flag.String("port", "2025", "Número da porta inicial para o servidor API")
	flag.Parse()

	go func() {
		// actualPort := Utils.FindAvailablePort(*port)
		// statusPortCh <- actualPort
		actualPort := *port
		Utils.SetCurrentPort(actualPort)

		// Configura as rotas (elas usam http.DefaultServeMux)
		Routes.Setup()

		fmt.Printf("Iniciando servidor na porta %s\n", actualPort)

		// Configura o middleware CORS
		corsHandler := cors.New(cors.Options{
			AllowedOrigins:   []string{"*"}, // ou use "*" para permitir todos
			AllowedMethods:   []string{"GET", "POST", "OPTIONS"},
			AllowedHeaders:   []string{"*"},
			AllowCredentials: true,
		}).Handler(http.DefaultServeMux) // <--- aqui o middleware envolve o mux padrão

		if err := http.ListenAndServe(":"+actualPort, corsHandler); err != nil {
			fmt.Printf("Erro no servidor: %v\n", err)
		}
	}()

	Utils.SetupTray()
}
