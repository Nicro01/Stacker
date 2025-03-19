#!/bin/bash

# Nome do serviço
SERVICE_NAME="stacker-api"

# Diretório de instalação
INSTALL_DIR="/opt/$SERVICE_NAME"

# Criar diretório e copiar arquivos
sudo mkdir -p $INSTALL_DIR
sudo cp -r ../app/* $INSTALL_DIR
sudo chmod +x $INSTALL_DIR/dist/stacker_api-linux

# Criar serviço systemd
sudo tee /etc/systemd/system/$SERVICE_NAME.service > /dev/null <<EOF
[Unit]
Description=Stacker API Service
After=network.target

[Service]
User=root
WorkingDirectory=$INSTALL_DIR
ExecStart=$INSTALL_DIR/dist/stacker_api-linux
Restart=always

[Install]
WantedBy=multi-user.target
EOF

# Recarregar e iniciar o serviço
sudo systemctl daemon-reload
sudo systemctl enable $SERVICE_NAME
sudo systemctl start $SERVICE_NAME

echo "Instalação concluída! A API está rodando em http://localhost:2025"
