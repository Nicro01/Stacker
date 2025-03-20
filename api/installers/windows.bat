@echo off
set SERVICE_NAME=stacker-api
set INSTALL_DIR="C:\Program Files\%SERVICE_NAME%"
set SOURCE_DIR="C:\laragon\www\stacker_laravel\api"

REM Criar diretório e copiar arquivos
if not exist %INSTALL_DIR% (
    mkdir %INSTALL_DIR%
) else (
    echo O diretório %INSTALL_DIR% já existe.
)

echo Copiando arquivos da API...
xcopy /E /Y %SOURCE_DIR%\* %INSTALL_DIR%\

REM Verificar se o executável existe
set EXECUTABLE_PATH=%INSTALL_DIR%\app\dist\stacker_api-win.exe
if not exist %EXECUTABLE_PATH% (
    echo Erro: O arquivo %EXECUTABLE_PATH% não foi encontrado.
    exit /b 1
)

REM Instalar como serviço
echo Instalando o serviço...
nssm install %SERVICE_NAME% %EXECUTABLE_PATH%
if errorlevel 1 (
    echo Erro: Falha ao instalar o serviço.
    exit /b 1
)

REM Configurar para iniciar automaticamente
nssm set %SERVICE_NAME% Start SERVICE_AUTO_START

REM Iniciar o serviço
echo Iniciando o serviço...
nssm start %SERVICE_NAME%
if errorlevel 1 (
    echo Erro: Falha ao iniciar o serviço.
    exit /b 1
)

echo Serviço instalado e iniciado com sucesso! Acesse http://localhost:2025
