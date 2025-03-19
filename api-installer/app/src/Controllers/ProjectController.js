const ProjectService = require("../Services/ProjectService");
const { EventEmitter } = require("events");
const eventEmitter = new EventEmitter();

class ProjectController {
  static async createProject(req, res) {
    try {
      const options = req.body;
      const logs = [];

      eventEmitter.on("log", (message) => {
        logs.push(message);
      });

      await ProjectService.createLaravelProject(options, eventEmitter);

      res.status(201).json({
        success: true,
        message: "Project created successfully",
        logs,
      });
    } catch (error) {
      res.status(500).json({
        success: false,
        message: error.message,
      });
    }
  }

  static async selectFolder(req, res) {
    try {
      const { path } = req.body;
      const isValid = await ProjectService.validatePath(path);

      res.status(200).json({
        valid: isValid,
        path,
      });
    } catch (error) {
      res.status(400).json({
        valid: false,
        message: error.message,
      });
    }
  }

  static handleLogs(req, res) {
    res.setHeader("Content-Type", "text/event-stream");
    res.setHeader("Cache-Control", "no-cache");
    res.setHeader("Connection", "keep-alive");

    const listener = (message) => {
      res.write(`data: ${JSON.stringify(message)}\n\n`);
    };

    eventEmitter.on("log", listener);

    req.on("close", () => {
      eventEmitter.off("log", listener);
    });
  }
}

module.exports = ProjectController;
