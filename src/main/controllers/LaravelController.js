const { ipcMain, dialog } = require("electron");
const { exec } = require("child_process");
const fs = require("fs");
const path = require("path");
const DATA_PATH = "./src/main/assets/datas/laravel/react_stack/";

/*
TODO:
- Choose different versions of Laravel.
- Update Projects.
*/

export default class LaravelController {
  static init() {
    ipcMain.on("create-laravel-project", async (event, options) => {
      try {
        const fullPath = await this.validateProjectPath(options);

        if (options.stack === 4) {
          await this.gitClone(
            event,
            fullPath,
            options.projectUrl,
            options.token
          );
          await this.configureEnvironment(event, fullPath, options);

          await this.installComposerDependencies(event, fullPath);

          if (options.npmDependencies) {
            await this.runBuildCommands(event, fullPath);
          }

          this.sendEvent(
            event,
            "success",
            "Project created and configured successfully"
          );
          return;
        }

        await this.installLaravel(event, fullPath, options.projectName);
        await this.configureEnvironment(event, fullPath, options);
        await this.setupStack(event, fullPath, options);

        this.sendEvent(
          event,
          "success",
          "Project created and configured successfully"
        );
      } catch (error) {
        this.sendEvent(event, "error", error.message);
      }
    });

    ipcMain.handle("select-folder", async () => {
      const result = await dialog.showOpenDialog({
        properties: ["openDirectory"],
      });
      return result.canceled ? null : result.filePaths[0];
    });
  }

  static sendEvent(event, type, message) {
    const eventMap = {
      log: "laravel-creation-log",
      error: "laravel-creation-error",
      success: "laravel-creation-success",
    };
    if (eventMap[type]) event.sender.send(eventMap[type], message);
  }

  static async validateProjectPath({ projectPath, projectName }) {
    const fullPath = path.join(projectPath, projectName);
    if (fs.existsSync(fullPath)) {
      throw new Error(
        `The folder "${fullPath}" already exists. Please choose a different name or path.`
      );
    }
    return fullPath;
  }

  static async gitClone(event, fullPath, projectUrl, token) {
    try {
      this.sendEvent(event, "log", "Starting repository clone...");
      const command = `git clone ${projectUrl} "${fullPath}"`;
      await this.executeCommand(event, command);
      this.sendEvent(event, "log", "Repository cloned successfully");
    } catch (error) {
      throw new Error(`Git clone failed: ${error.message}`);
    }
  }

  static async installComposerDependencies(event, fullPath) {
    await this.executeCommand(event, `cd "${fullPath}" && composer install`);
  }

  static installLaravel(event, fullPath, projectName) {
    return new Promise((resolve, reject) => {
      const command = `composer create-project --prefer-dist laravel/laravel "${fullPath}"`;

      const child = exec(command, (error) => {
        error ? reject(error) : resolve();
      });

      child.stdout.on("data", (data) => this.sendEvent(event, "log", data));
      child.stderr.on("data", (data) => this.sendEvent(event, "log", data));
    });
  }

  static async configureEnvironment(event, fullPath, options) {
    if (!fs.existsSync(path.join(fullPath, ".env"))) {
      const envExamplePath = path.join(fullPath, ".env.example");
      const envExampleContent = await fs.promises.readFile(
        envExamplePath,
        "utf8"
      );
      await fs.promises.writeFile(
        path.join(fullPath, ".env"),
        envExampleContent
      );
    }

    const envPath = path.join(fullPath, ".env");
    const envContent = await fs.promises.readFile(envPath, "utf8");

    const updatedEnv = envContent
      .replace(/DB_CONNECTION=.*/, `DB_CONNECTION=${options.DB_CONNECTION}`)
      .replace(/# DB_HOST=.*/, `DB_HOST=${options.DB_HOST}`)
      .replace(/# DB_PORT=.*/, `DB_PORT=${options.DB_PORT}`)
      .replace(/# DB_DATABASE=.*/, `DB_DATABASE=${options.DB_DATABASE}`)
      .replace(/# DB_USERNAME=.*/, `DB_USERNAME=${options.DB_USERNAME}`)
      .replace(/# DB_PASSWORD=.*/, `DB_PASSWORD=${options.DB_PASSWORD}`);

    await fs.promises.writeFile(envPath, updatedEnv);
    this.sendEvent(event, "log", ".env file updated successfully");
  }

  static async setupStack(event, fullPath, { stack, auth }) {
    if (stack === 0) return; // No stack selected

    try {
      switch (stack) {
        case 1:
          await this.installTallStack(event, fullPath, auth);
          break;
        case 2:
          await this.installJetstreamStack(event, fullPath);
          break;
        case 3:
          await this.installReactStack(event, fullPath);
          break;
      }

      await this.runBuildCommands(event, fullPath);
    } catch (error) {
      throw new Error(`Installation failed: ${error.message}`);
    }
  }

  static async installTallStack(event, fullPath, auth) {
    let command = `cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall`;
    command += auth
      ? ` && php artisan ui tall --auth`
      : ` && php artisan ui tall`;

    await this.executeCommand(event, command);
  }

  static async installJetstreamStack(event, fullPath) {
    const command = `cd "${fullPath}" && composer require laravel/jetstream && php artisan jetstream:install inertia`;
    await this.executeCommand(event, command);
  }

  static async installReactStack(event, fullPath) {
    // Install dependencies
    await this.executeCommand(
      event,
      `cd "${fullPath}" && composer require inertiajs/inertia-laravel && npm install @inertiajs/react react-dom react`
    );

    // File operations
    this.handleReactFiles(fullPath);

    // Generate controller
    await this.executeCommand(
      event,
      `cd "${fullPath}" && php artisan make:controller HomeController`
    );
  }

  static handleReactFiles(fullPath) {
    const reactFiles = [
      { action: "unlink", path: "resources/views/welcome.blade.php" },
      {
        action: "copy",
        source: "app.blade.php",
        target: "resources/views/app.blade.php",
      },
      { action: "unlink", path: "resources/js/app.js" },
      { action: "mkdir", path: "app/Http/Middleware" },
      {
        action: "copy",
        source: "HandleInertiaRequests.php",
        target: "app/Http/Middleware/HandleInertiaRequests.php",
      },
      { action: "copy", source: "app.php", target: "bootstrap/app.php" },
      { action: "copy", source: "app.jsx", target: "resources/js/app.jsx" },
      { action: "mkdir", path: "resources/js/Pages" },
      {
        action: "copy",
        source: "Home.jsx",
        target: "resources/js/Pages/Home.jsx",
      },
      {
        action: "copy",
        source: "HomeController.php",
        target: "app/Http/Controllers/HomeController.php",
      },
      { action: "copy", source: "web.php", target: "routes/web.php" },
    ];

    reactFiles.forEach(({ action, path: filePath, source, target }) => {
      const fullTarget = path.join(fullPath, target || filePath);
      switch (action) {
        case "unlink":
          fs.unlinkSync(fullTarget);
          break;
        case "copy":
          fs.copyFileSync(path.join(DATA_PATH, source), fullTarget);
          break;
        case "mkdir":
          fs.mkdirSync(fullTarget, { recursive: true });
          break;
      }
    });
  }

  static async runBuildCommands(event, fullPath) {
    await this.executeCommand(
      event,
      `cd "${fullPath}" && npm install && npm run build && php artisan migrate`
    );
  }

  static executeCommand(event, command) {
    return new Promise((resolve, reject) => {
      const child = exec(command, (error) => {
        error ? reject(error) : resolve();
      });

      child.stdout.on("data", (data) => this.sendEvent(event, "log", data));
      child.stderr.on("data", (data) => this.sendEvent(event, "log", data));
    });
  }
}
