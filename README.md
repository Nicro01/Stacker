# Stacker

Stacker é uma ferramenta de desenvolvimento desenhada para acelerar a inicialização de projetos, eliminando a necessidade de configurar manualmente stacks de tecnologia complexas.

### Ferramentas e Tecnologias

-----

## 🎯 Sobre o Projeto

Iniciar um novo projeto com uma stack específica, como VILT (Vue, Inertia, Laravel, Tailwind), pode ser um processo demorado e repetitivo. O Stacker resolve este problema ao automatizar todo o processo de download e configuração.

Este projeto destina-se a **programadores** que valorizam a velocidade e a eficiência, permitindo-lhes lançar um ambiente de desenvolvimento completo e funcional em questão de minutos, utilizando templates pré-definidos ou configurações personalizadas.

## ✨ Funcionalidades

  * **Inicialização Rápida de Projetos:** Reduz drasticamente o tempo de configuração inicial de um novo projeto.
  * **Templates de Stacks Populares:** Vem com suporte integrado para stacks prontas a usar, como VILT (Vue, Inertia, Laravel, Tailwind), TALL e RILT.
  * **Configuração Personalizável:** Permite que os programadores usem os seus próprios ficheiros de configuração para padronizar e acelerar o setup.
  * **Seleção de Bibliotecas (Em desenvolvimento):** Oferecerá a flexibilidade de escolher bibliotecas adicionais durante o processo de criação do projeto.

## 🚀 Começar

Siga os passos abaixo para configurar e executar o Stacker no seu ambiente local.

### Pré-requisitos

Antes de começar, certifique-se de que tem as seguintes ferramentas instaladas:

  * Node.js (v21 ou superior)
  * PHP (v8.1 ou superior)
  * Composer
  * Go

### Instalação

1.  **Clone o repositório:**

    ```sh
    git clone https://github.com/teu-usuario/stacker.git
    ```

2.  **Navegue para o diretório do projeto:**

    ```sh
    cd stacker
    ```

3.  **Instale as dependências do Node.js:**

    ```sh
    npm ci
    ```

4.  **Instale as dependências do PHP com o Composer:**

    ```sh
    composer install
    ```

5.  **Execute as migrações e gere a chave da aplicação:**

    ```sh
    php artisan migrate
    php artisan key:generate
    ```

6.  **Instale as dependências da API em Go:**

      * Navegue até o diretório da API:
        ```sh
        cd api
        ```
      * Baixe as dependências do Go:
        ```sh
        go mod tidy
        ```

## 💻 Como Usar

O Stacker é executado com dois processos em simultâneo. Abra dois terminais e siga os passos:

1.  **Terminal 1: Iniciar o servidor Laravel e Vite**

      * No diretório raiz (`stacker/`), execute o script de desenvolvimento:
        ```sh
        composer run dev
        ```

2.  **Terminal 2: Iniciar a API em Go**

      * No diretório da API (`stacker/api/`), execute o ficheiro principal:
        ```sh
        go run ./cmd/api/main.go
        ```

3.  **Aceder à Aplicação**

      * Abra o seu navegador e aceda a `http://localhost:8000`.
      * Faça o seu registo na plataforma.
      * Aceda ao painel de controlo, onde pode criar e configurar os seus novos projetos de forma intuitiva.
