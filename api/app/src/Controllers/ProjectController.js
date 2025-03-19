const ProjectService = require("../Services/ProjectService");
const { v4: uuidv4 } = require("uuid");
const EventEmitter = require("events");

const logStore = new Map();

class ProjectController {
    static emitters = new Map();

    static async createProject(req, res) {
        try {
            const options = req.body;
            const projectId = uuidv4();
            logStore.set(projectId, []);

            const emitter = new EventEmitter();
            ProjectController.emitters.set(projectId, emitter);

            emitter.on("log", (message) => {
                logStore.get(projectId).push(message);
            });

            emitter.once("complete", () => {
                logStore.get(projectId).push("complete");
                if (!res.headersSent) {
                    res.json({
                        success: true,
                        message: "Project created successfully!",
                        projectId,
                    });
                }
            });

            emitter.once("error", (error) => {
                if (!res.headersSent) {
                    res.status(500).json({
                        success: false,
                        message: error || "Project creation failed.",
                    });
                }
            });

            ProjectService.createLaravelProject(options, emitter)
                .then(() => emitter.emit("complete"))
                .catch((err) => emitter.emit("error", err.message))
                .finally(() => {
                    setTimeout(
                        () => ProjectController.emitters.delete(projectId),
                        5000
                    );
                });

            res.json({ projectId });
        } catch (error) {
            res.status(500).json({
                success: false,
                message: error.message,
            });
        }
    }

    static handleLogs(req, res) {
        const projectId = req.query.id;
        const logs = logStore.get(projectId) || [];

        res.json({ logs });
        logStore.set(projectId, []);
    }
}

module.exports = ProjectController;
