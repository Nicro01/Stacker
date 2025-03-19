const { exec } = require("child_process");

class CommandRunner {
    static execute(command, emitter) {
        return new Promise((resolve, reject) => {
            const child = exec(command);

            child.stdout.on("data", (data) =>
                emitter.emit("log", data.toString())
            );

            child.stderr.on("data", (data) =>
                emitter.emit("log", data.toString())
            );

            child.on("close", (code) =>
                code === 0 ? resolve() : reject(new Error(`Exit code ${code}`))
            );
        });
    }
}

module.exports = CommandRunner;
