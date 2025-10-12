@echo off
:: ============================================
:: Script de inicialização do Laravel no Windows
:: Executa limpeza de cache, migrações e inicia o servidor
:: ============================================

title Laravel - Inicialização do Projeto
color 0A

echo ============================================
echo Iniciando manutenção do Laravel...
echo ============================================

:: Garante que o terminal esteja na pasta do projeto
cd /d %~dp0

echo.
echo Limpando cache de configuração...
php artisan config:clear

echo.
echo Executando migrações do banco de dados...
php artisan migrate

echo.
echo Limpando cache geral...
php artisan cache:clear

echo.
echo Limpando cache de configuração novamente...
php artisan config:clear

echo.
echo Limpando cache de rotas...
php artisan route:clear

echo.
echo Limpando cache de views...
php artisan view:clear

echo.
echo Otimizando aplicação...
php artisan optimize

echo.
echo ============================================
echo Iniciando servidor Laravel...
echo ============================================

php artisan serve

echo.
echo Servidor Laravel em execução em: http://127.0.0.1:8000
echo Pressione CTRL+C para parar o servidor.
echo ============================================

pause
