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

        switch (stack) {
          case 1:
            console.log(auth);
            if (auth) {
              `&& cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall && php artisan ui tall --auth && npm install && npm run build`;
            } else {
              `&& cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall && php artisan ui tall && npm install && npm run build`;
            }
            break;
        }

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
              let stackCommand = `cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall`;

              if (auth) {
                stackCommand += ` && php artisan ui tall --auth`;
              } else {
                stackCommand += ` && php artisan ui tall`;
              }

              stackCommand += ` && npm install && npm run build`;

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
