@echo off
echo ===============================================
echo   Installation de La Bonte Immo
echo   Systeme de Gestion Immobiliere
echo ===============================================
echo.

echo [1/6] Verification des prerequis...
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ERREUR: Composer n'est pas installe ou non accessible
    echo Veuillez installer Composer depuis https://getcomposer.org/
    pause
    exit /b 1
)

where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ERREUR: PHP n'est pas installe ou non accessible
    echo Veuillez installer PHP 8.1 ou superieur
    pause
    exit /b 1
)

echo [2/6] Installation des dependances...
call composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ERREUR: Echec de l'installation des dependances
    pause
    exit /b 1
)

echo [3/6] Configuration de l'environnement...
if not exist ".env" (
    copy ".env.example" ".env"
    echo Fichier .env cree depuis .env.example
)

echo [4/6] Generation de la cle d'application...
php artisan key:generate --force

echo [5/6] Instructions pour la base de donnees...
echo.
echo IMPORTANT: Configuration de la base de donnees
echo -------------------------------------------
echo 1. Creez une base de donnees MySQL nommee 'labonte_immo'
echo 2. Editez le fichier .env et configurez les parametres DB_*
echo 3. Executez ensuite les commandes suivantes :
echo.
echo    php artisan migrate
echo    php artisan db:seed
echo.

echo [6/6] Instructions de demarrage...
echo.
echo Pour demarrer l'application :
echo ----------------------------
echo 1. Assurez-vous que MySQL est demarré
echo 2. Executez: php artisan serve
echo 3. Ouvrez votre navigateur sur: http://localhost:8000
echo.
echo Connexion par defaut :
echo - Email: admin@labonteimmo.com
echo - Mot de passe: password
echo.

echo ===============================================
echo   Installation terminee !
echo ===============================================
echo.
pause