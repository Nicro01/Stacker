export default class WindowController {
  static init(mainWindow) {
    const { ipcMain } = require("electron");

    ipcMain.on("minimize-window", () => {
      mainWindow.minimize();
    });

    ipcMain.on("reload-window", () => {
      mainWindow.reload();
    });

    ipcMain.on("close-window", () => {
      mainWindow.close();
    });
  }
}
