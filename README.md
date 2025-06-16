# Stacker

[Português](https://www.google.com/search?q=%23-stacker-pt-br) | [English](https://www.google.com/search?q=%23-stacker-en-us)

-----

## 🇧🇷 Stacker (PT-BR)

Stacker é uma ferramenta de desenvolvimento desenhada para acelerar a inicialização de projetos, eliminando a necessidade de configurar manualmente stacks de tecnologia complexas.

### Ferramentas e Tecnologias

-----

### 🎯 Sobre o Projeto

Iniciar um novo projeto com uma stack específica, como VILT (Vue, Inertia, Laravel, Tailwind), pode ser um processo demorado e repetitivo. O Stacker resolve este problema ao automatizar todo o processo de download e configuração.

Este projeto destina-se a **programadores** que valorizam a velocidade e a eficiência, permitindo-lhes lançar um ambiente de desenvolvimento completo e funcional em questão de minutos, utilizando templates pré-definidos ou configurações personalizadas.

### ✨ Funcionalidades

  * **Inicialização Rápida de Projetos:** Reduz drasticamente o tempo de configuração inicial de um novo projeto.
  * **Templates de Stacks Populares:** Vem com suporte integrado para stacks prontas a usar, como VILT (Vue, Inertia, Laravel, Tailwind), TALL e RILT.
  * **Configuração Personalizável:** Permite que os programadores usem os seus próprios ficheiros de configuração para padronizar e acelerar o setup.
  * **Seleção de Bibliotecas (Em desenvolvimento):** Oferecerá a flexibilidade de escolher bibliotecas adicionais durante o processo de criação do projeto.

### 🚀 Começar

Siga os passos abaixo para configurar e executar o Stacker no seu ambiente local.

#### Pré-requisitos

Antes de começar, certifique-se de que tem as seguintes ferramentas instaladas:

  * Node.js (v21 ou superior)
  * PHP (v8.1 ou superior)
  * Composer
  * Go

#### Instalação

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

### 💻 Como Usar

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

-----

## 🇺🇸 Stacker (EN-US)

Stacker is a development tool designed to speed up project initialization by eliminating the need to manually configure complex technology stacks.

### Tools and Technologies

-----

### 🎯 About The Project

Starting a new project with a specific stack, such as VILT (Vue, Inertia, Laravel, Tailwind), can be a time-consuming and repetitive process. Stacker solves this problem by automating the entire download and configuration process.

This project is intended for **developers** who value speed and efficiency, allowing them to launch a complete and functional development environment in minutes using pre-defined templates or custom configurations.

### ✨ Features

  * **Rapid Project Initialization:** Drastically reduces the initial setup time for a new project.
  * **Popular Stack Templates:** Comes with built-in support for ready-to-use stacks like VILT (Vue, Inertia, Laravel, Tailwind), TALL, and RILT.
  * **Custom Configuration:** Allows developers to use their own configuration files to standardize and accelerate the setup.
  * **Library Selection (In Development):** Will offer the flexibility to choose additional libraries during the project creation process.

### 🚀 Getting Started

Follow the steps below to set up and run Stacker in your local environment.

#### Prerequisites

Before you begin, ensure you have the following tools installed:

  * Node.js (v21 or higher)
  * PHP (v8.1 or higher)
  * Composer
  * Go

#### Installation

1.  **Clone the repository:**

    ```sh
    git clone https://github.com/your-username/stacker.git
    ```

2.  **Navigate to the project directory:**

    ```sh
    cd stacker
    ```

3.  **Install Node.js dependencies:**

    ```sh
    npm ci
    ```

4.  **Install PHP dependencies with Composer:**

    ```sh
    composer install
    ```

5.  **Run migrations and generate the application key:**

    ```sh
    php artisan migrate
    php artisan key:generate
    ```

6.  **Install Go API dependencies:**

      * Navigate to the API directory:
        ```sh
        cd api
        ```
      * Fetch the Go dependencies:
        ```sh
        go mod tidy
        ```

### 💻 Usage

Stacker runs with two simultaneous processes. Open two terminals and follow the steps:

1.  **Terminal 1: Start the Laravel and Vite server**

      * In the root directory (`stacker/`), run the development script:
        ```sh
        composer run dev
        ```

2.  **Terminal 2: Start the Go API**

      * In the API directory (`stacker/api/`), run the main file:
        ```sh
        go run ./cmd/api/main.go
        ```

3.  **Access the Application**

      * Open your browser and go to `http://localhost:8000`.
      * Register on the platform.
      * Access the dashboard, where you can intuitively create and configure your new projects.
