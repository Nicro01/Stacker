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
              let DATA_PATH = "./src/main/assets/datas/laravel/react_stack/";

              switch (stack) {
                case 0:
                  event.sender.send(
                    "laravel-creation-success",
                    ".env file updated successfully."
                  );
                  return;
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
                  // Install Inertia and React dependencies
                  exec(
                    `cd "${fullPath}" && composer require inertiajs/inertia-laravel && npm install @inertiajs/react react-dom react`
                  );

                  // Remove the default app.blade.php
                  fs.unlinkSync(
                    path.join(fullPath, "resources/views/welcome.blade.php")
                  );

                  // Copy a new app.blade.php for Inertia
                  fs.copyFileSync(
                    `${DATA_PATH}app.blade.php`,
                    path.join(fullPath, "resources/views/app.blade.php")
                  );
                  console.log("a");

                  fs.unlinkSync(path.join(fullPath, "resources/js/app.js"));

                  fs.mkdirSync(path.join(fullPath, "app/Http/Middleware"));

                  fs.copyFileSync(
                    `${DATA_PATH}HandleInertiaRequests.php`,
                    path.join(
                      fullPath,
                      "app/Http/Middleware/HandleInertiaRequests.php"
                    )
                  );

                  // Copy a new bootstrap/app.php
                  fs.copyFileSync(
                    `${DATA_PATH}app.php`,
                    path.join(fullPath, "bootstrap/app.php")
                  );

                  // Update resources/js/app.jsx
                  fs.copyFileSync(
                    `${DATA_PATH}app.jsx`,
                    path.join(fullPath, "resources/js/app.jsx")
                  );

                  fs.mkdirSync(path.join(fullPath, "resources/js/Pages"));

                  fs.copyFileSync(
                    `${DATA_PATH}Home.jsx`,
                    path.join(fullPath, "resources/js/Pages/Home.jsx")
                  );

                  exec(
                    `cd "${fullPath}" && php artisan make:controller HomeController`
                  );

                  fs.copyFileSync(
                    `${DATA_PATH}HomeController.php`,
                    path.join(
                      fullPath,
                      "app/Http/Controllers/HomeController.php"
                    )
                  );

                  fs.copyFileSync(
                    `${DATA_PATH}web.php`,
                    path.join(fullPath, "routes/web.php")
                  );

                  return;
              }

              stackCommand += `&& npm install && npm run build && php artisan migrate`;

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
