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

        await this.installDependencies(fullPath, options, emitter);

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

    // static async installReactStack(fullPath, options, emitter) {
    //     // Install dependencies
    //     await this.executeCommand(
    //         event,
    //         `cd "${fullPath}" && composer require inertiajs/inertia-laravel && npm install @inertiajs/react react-dom react`
    //     );

    //     // File operations
    //     this.handleReactFiles(fullPath);

    //     // Generate controller
    //     await this.executeCommand(
    //         event,
    //         `cd "${fullPath}" && php artisan make:controller HomeController`
    //     );
    // }

    static async installDependencies(fullPath, options, emitter) {
        const command = `cd "${fullPath}" && npm install && npm run build && php artisan migrate`;

        await CommandRunner.execute(command, emitter);
    }

    static async configureEnvironment(fullPath, options, emitter) {
        //
    }

    static async setupStack(fullPath, options, emitter) {
        // Stack-specific installation logic
        // ...
    }
}

module.exports = ProjectService;
