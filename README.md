# Stacker
-----

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
