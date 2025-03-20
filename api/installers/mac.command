#!/bin/bash

SERVICE_NAME="stacker-api"
INSTALL_DIR="/Applications/$SERVICE_NAME"

sudo mkdir -p $INSTALL_DIR
sudo cp -r ../* $INSTALL_DIR

sudo tee /Library/LaunchDaemons/$SERVICE_NAME > /dev/null <<EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
  <dict>
    <key>Label</key>
    <string>$SERVICE_NAME</string>
    <key>ProgramArguments</key>
    <array>
      <string>$INSTALL_DIR/app/dist/stacker-api-mac</string>
    </array>
    <key>RunAtLoad</key>
    <true/>
    <key>KeepAlive</key>
    <true/>
  </dict>
</plist>
EOF

sudo launchctl load /Library/LaunchDaemons/$SERVICE_NAME
echo "Instalação concluída! A API está rodando em http://localhost:2025"
