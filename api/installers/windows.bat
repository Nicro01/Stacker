@echo off

set SERVICE_NAME=stacker-api
set INSTALL_DIR="C:\Program Files\%SERVICE_NAME%"

REM Criar diretório e copiar arquivos
mkdir %INSTALL_DIR%
xcopy /E /Y ..\ %INSTALL_DIR%

REM Instalar como serviço
nssm install %SERVICE_NAME% "%INSTALL_DIR%\api\app\dist\stacker-api-win.exe"
nssm start %SERVICE_NAME%

echo Serviço instalado! Acesse http://localhost:2025
