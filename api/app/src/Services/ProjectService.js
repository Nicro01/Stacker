const fs = require("fs");
const path = require("path");
const { exec } = require("child_process");
const CommandRunner = require("../Utils/commandRunner");
const DATA_PATH = "./assets/datas/laravel/react_stack/";

class ProjectService {
    static async createLaravelProject(options, emitter) {
        const fullPath = path.join(options.projectPath, options.projectName);

        await this.validateProjectPath(fullPath);

        if (options.stack === 4) {
            await this.cloneRepository(fullPath, options, emitter);
        } else {
            await this.installLaravel(fullPath, options, emitter);
        }

        if (options.stack === 2) {
            await this.installTallStack(fullPath, options, emitter);
        }

        await this.configureEnvironment(fullPath, options, emitter);

        if (options.stack !== 4) {
            await this.setupStack(fullPath, options, emitter);
        }
    }

    static async validateProjectPath(fullPath) {
        if (fs.existsSync(fullPath)) {
            throw new Error(`Path ${fullPath} already exists`);
        }
    }

    static async cloneRepository(fullPath, options, emitter) {
        const command = `git clone ${options.projectUrl} "${fullPath}"`;
        await CommandRunner.execute(command, emitter);
    }

    static async installLaravel(fullPath, options, emitter) {
        const command = `composer create-project --prefer-dist laravel/laravel "${fullPath}"`;
        await CommandRunner.execute(command, emitter);
    }

    static async installTallStack(fullPath, options, emitter) {
        let command = `cd "${fullPath}" && composer require livewire/livewire laravel-frontend-presets/tall`;
        command += options.auth
            ? ` && php artisan ui tall --auth`
            : ` && php artisan ui tall`;

        await CommandRunner.execute(command, emitter);
    }

    static async installJetstreamStack(fullPath, options, emitter) {
        const command = `cd "${fullPath}" && composer require laravel/jetstream && php artisan jetstream:install inertia`;
        await CommandRunner.execute(command, emitter);
    }

    static async installDependencies(fullPath, options, emitter) {
        const command = `cd "${fullPath}" && npm install && npm run build`;

        await CommandRunner.execute(command, emitter);
    }

    static async configureEnvironment(projectDir, options, emitter) {
        const fs = require("fs").promises;
        const path = require("path");

        try {
            const fullPath = path.join(projectDir, ".env");

            const dir = path.dirname(fullPath);
            await fs.mkdir(dir, { recursive: true });

            const envContents = Object.entries(options.configs)
                .map(([key, value]) => `${key}=${value}`)
                .join("\n");

            emitter?.emit("environment-configuring");
            await fs.writeFile(fullPath, envContents, "utf8");
            emitter?.emit("environment-configured");
        } catch (error) {
            emitter?.emit("error", `ENV Config Failed: ${error.message}`);
            throw error;
        }
    }

    static async setupStack(fullPath, options, emitter) {
        // Stack-specific installation logic
        // ...
    }
}

module.exports = ProjectService;
