const { exec } = require("child_process");

class CommandRunner {
  static execute(command, emitter) {
    return new Promise((resolve, reject) => {
      const child = exec(command);

      child.stdout.on("data", (data) => {
        emitter.emit("log", data.toString());
      });

      child.stderr.on("data", (data) => {
        emitter.emit("log", data.toString());
      });

      child.on("close", (code) => {
        if (code === 0) resolve();
        else reject(new Error(`Command failed with code ${code}`));
      });
    });
  }
}

module.exports = CommandRunner;
