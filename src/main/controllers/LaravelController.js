/*
TODO:
- Choose diferent versions of Laravel.
- Update Projects.
*/

export default class LaravelController {
  static init() {
    const { ipcMain } = require("electron");
    const { dialog } = require("electron");
    const { exec } = require("child_process");
    const fs = require("fs");
    const path = require("path");

    ipcMain.on(
      "create-laravel-project",
      (
        event,
        {
          projectPath,
          projectName,
          stack,
          auth = null,
          DB_CONNECTION = "mysql",
          DB_HOST = "127.0.0.1",
          DB_PORT = 3306,
          DB_DATABASE = "stacker",
          DB_USERNAME = "root",
          DB_PASSWORD = "",
        }
      ) => {
        const fullPath = path.join(projectPath, projectName);

        if (fs.existsSync(fullPath)) {
          event.sender.send(
            "laravel-creation-error",
            `The folder "${fullPath}" already exists. Please choose a different name or path.`
          );
          return;
        }

        let command = `composer create-project --prefer-dist laravel/laravel "${fullPath}"`;

        const child = exec(command, (error, stdout, stderr) => {
          if (error) {
            event.sender.send("laravel-creation-error", error.message);
            return;
          }

          editEnvFile(
            fullPath,
            DB_CONNECTION,
            DB_HOST,
            DB_PORT,
            DB_DATABASE,
            DB_USERNAME,
            DB_PASSWORD,
            event
          )
            .then(() => {
              let stackCommand = "";

              switch (stack) {
                case 0:
                  break;
                case 1:
                  stackCommand = `cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall`;
                  if (auth) {
                    stackCommand += ` && php artisan ui tall --auth`;
                  } else {
                    stackCommand += ` && php artisan ui tall`;
                  }
                  break;
                case 2:
                  stackCommand = `cd "${fullPath}" && composer require laravel/jetstream && php artisan jetstream:install inertia`;
                  break;
                case 3:
                  stackCommand = `cd "${fullPath}" && composer require inertiajs/inertia-laravel && npm install @inertiajs/react react react-dom @vitejs/plugin-react`;
                  break;
              }

              if (stack == 3) {
                // Install Inertia and React dependencies
                exec(
                  `cd "${fullPath}" && composer require inertiajs/inertia-laravel && php artisan inertia:middleware && npm install @inertiajs/react react-dom react`
                );

                // Remove the default app.blade.php
                fs.unlinkSync(
                  path.join(fullPath, "resources/views/welcome.blade.php")
                );

                // Create a new app.blade.php for Inertia
                fs.writeFileSync(
                  path.join(fullPath, "resources/views/app.blade.php"),
                  `<!DOCTYPE html>
     <html>
        <head>
          <meta charset="utf-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
          @vite('resources/js/app.jsx')
          @inertiaHead
        </head>
        <body>
          @inertia
        </body>
     </html>`
                );

                fs.unlinkSync(path.join(fullPath, "resources/js/app.js"));

                // Update bootstrap/app.php
                fs.writeFileSync(
                  path.join(fullPath, "bootstrap/app.php"),
                  `<?php

      use Illuminate\\Foundation\\Application;
      use Illuminate\\Foundation\\Configuration\\Exceptions;
      use Illuminate\\Foundation\\Configuration\\Middleware;
      use App\\Http\\Middleware\\HandleInertiaRequests;

      return Application::configure(basePath: dirname(__DIR__))
          ->withRouting(
              web: __DIR__ . '/../routes/web.php',
              commands: __DIR__ . '/../routes/console.php',
              health: '/up',
          )
          ->withMiddleware(function (Middleware $middleware) {
              $middleware->web(append: [
                  HandleInertiaRequests::class,
              ]);
          })
          ->withExceptions(function (Exceptions $exceptions) {
              //
          })->create();
      `
                );

                // Update resources/js/app.js
                fs.writeFileSync(
                  path.join(fullPath, "resources/js/app.jsx"),
                  `import React from "react";
import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
        return pages[\`./Pages/\${name}.jsx\`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});`
                );

                fs.mkdirSync(path.join(fullPath, "resources/js/Pages"));

                fs.writeFileSync(
                  path.join(fullPath, "resources/js/Pages/Home.jsx"),
                  `import React from "react";

export default function Home() {
    return (
        <div>
            <h1>Welcome</h1>
            <p>Hello, welcome to your first Inertia app!</p>
        </div>
    );
}
`
                );

                exec(
                  `cd "${fullPath}" && php artisan make:controller HomeController) `
                );

                fs.writeFileSync(
                  path.join(
                    fullPath,
                    "app/Http/Controllers/HomeController.php"
                  ),
                  `<?php

namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;
use Inertia\\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home');
    }
}`
                );

                fs.writeFileSync(
                  path.join(fullPath, "routes/web.php"),
                  `<?php

use Illuminate\\Support\\Facades\\Route;

Route::get('/', [App\\Http\\Controllers\\HomeController::class, 'index'])->name('home');
`
                );

                // Install remaining dependencies and build
                exec(
                  `cd "${fullPath}" && npm install`,
                  (error, stdout, stderr) => {
                    if (error) {
                      event.sender.send(
                        "laravel-creation-error",
                        error.message
                      );
                      return;
                    }
                    event.sender.send("laravel-creation-success", stdout);
                    return;
                  }
                );
              }

              stackCommand += ` && npm install && npm run build && php artisan migrate`;

              const stackChild = exec(stackCommand, (error, stdout, stderr) => {
                if (error) {
                  event.sender.send("laravel-creation-error", error.message);
                  return;
                }
                event.sender.send("laravel-creation-success", stdout);
              });

              stackChild.stdout.on("data", (data) => {
                event.sender.send("laravel-creation-log", data);
              });

              stackChild.stderr.on("data", (data) => {
                event.sender.send("laravel-creation-error", data);
              });
            })
            .catch((err) => {
              event.sender.send("laravel-creation-error", err.message);
            });

          event.sender.send("laravel-creation-success", stdout);
        });

        child.stdout.on("data", (data) => {
          event.sender.send("laravel-creation-log", data);
        });

        child.stderr.on("data", (data) => {
          event.sender.send("laravel-creation-error", data);
        });
      }
    );

    function editEnvFile(
      fullPath,
      DB_CONNECTION,
      DB_HOST,
      DB_PORT,
      DB_DATABASE,
      DB_USERNAME,
      DB_PASSWORD,
      event
    ) {
      return new Promise((resolve, reject) => {
        const envPath = path.join(fullPath, ".env");

        fs.readFile(envPath, "utf8", (err, data) => {
          if (err) {
            reject(new Error(`Failed to read .env file: ${err.message}`));
            return;
          }

          const updatedEnv = data
            .replace(/DB_CONNECTION=.*/, "DB_CONNECTION=" + DB_CONNECTION)
            .replace(/# DB_HOST=.*/, "DB_HOST=" + DB_HOST)
            .replace(/# DB_PORT=.*/, "DB_PORT=" + DB_PORT)
            .replace(/# DB_DATABASE=.*/, "DB_DATABASE=" + DB_DATABASE)
            .replace(/# DB_USERNAME=.*/, "DB_USERNAME=" + DB_USERNAME)
            .replace(/# DB_PASSWORD=.*/, "DB_PASSWORD=" + DB_PASSWORD);

          fs.writeFile(envPath, updatedEnv, "utf8", (err) => {
            if (err) {
              reject(new Error(`Failed to write .env file: ${err.message}`));
              return;
            }

            event.sender.send(
              "laravel-creation-log",
              ".env file updated successfully."
            );
            resolve();
          });
        });
      });
    }

    ipcMain.handle("select-folder", async () => {
      const result = await dialog.showOpenDialog({
        properties: ["openDirectory"],
      });

      if (!result.canceled && result.filePaths.length > 0) {
        return result.filePaths[0];
      }
      return null;
    });
  }
}
