@echo off
TITLE Strategic Enterprise - Startup
SET PHP_PATH=C:\php\php.exe
SET NPM_PATH=C:\Program Files\nodejs\npm.cmd

echo ===================================================
echo   STRATEGIC ENTERPRISE - INICIANDO SERVICOS
echo ===================================================

:: Verifica se o MySQL está rodando
echo [1/3] Verificando MySQL...
net start MySQL84 >nul 2>&1
if %ERRORLEVEL% EQU 0 (
    echo [OK] MySQL rodando.
) else (
    echo [!] MySQL ja estava rodando ou requer permissao de Admin.
)

:: Inicia o servidor PHP Artisan
echo [2/3] Iniciando servidor Laravel (Porta 8000)...
start "Strategic - Laravel Server" cmd /k "%PHP_PATH% artisan serve"

:: Inicia o Vite
echo [3/3] Iniciando Vite Dev Server...
start "Strategic - Vite Dev" cmd /k "npm run dev"

echo.
echo ---------------------------------------------------
echo   TUDO PRONTO!
echo   Painel: http://127.0.0.1:8000/admin
echo ---------------------------------------------------
echo.
pause
