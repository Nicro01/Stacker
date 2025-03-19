const express = require("express");
const router = express.Router();
const ProjectController = require("../Controllers/ProjectController");

router.post("/projects", ProjectController.createProject);
// router.post("/select-folder", ProjectController.selectFolder);
router.get("/logs", ProjectController.handleLogs);

module.exports = router;
