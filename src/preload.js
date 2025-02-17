const { contextBridge, ipcRenderer } = require("electron");

// Expose protected methods to the renderer process
contextBridge.exposeInMainWorld("electron", {
  send: (channel, data) => ipcRenderer.send(channel, data),
  on: (channel, func) =>
    ipcRenderer.on(channel, (event, ...args) => func(...args)),
  invoke: (channel, data) => ipcRenderer.invoke(channel, data),
});
