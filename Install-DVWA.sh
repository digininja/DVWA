#!/bin/bash

# Obtener el prefijo del idioma
lang_prefix="${LANG:0:2}"

# Función para verificar el idioma y mostrar el mensaje correspondiente
get_language_message() {
    if [[ $lang_prefix -eq "es" ]]; then
        echo -e "$1"
    else
        echo -e "$2"
    fi
}

# Verificar si el usuario es root
if [ "$EUID" -ne 0 ]; then
    error_message=$(get_language_message "\e[91mEste script debe ejecutarse como usuario root.\e[0m" "\e[91mThis script must be run by the root user.\e[0m")
    echo -e "$error_message"
    exit 1
fi

# Función para verificar la existencia de un programa
check_program() {
    if ! command -v "$1" &>/dev/null; then
        message=$(get_language_message "\033[91m$1 KO." "\033[91m$1 KO")
        echo -e >&2 "$message"
        apt install -y "$1"
    else
        success_message=$(get_language_message "\033[92m$1 OK.\033[0m" "\033[92m$1 OK.\033[0m")
        echo -e "$success_message"
    fi
}

# Función para prompt de contraseña de MySQL root
get_mysql_root_password() {
    read -s -p "$(get_language_message "Enter MySQL root password: " "Ingrese la contraseña de root de MySQL: ")" mysql_root_password
    echo -e "$mysql_root_password"
}
# Arte ASCII
echo -e "\033[96m\033[1m
⠄⠄⠄⠄⠄⠄⠄⢀⣠⣶⣾⣿⣶⣦⣤⣀⠄⢀⣀⣤⣤⣤⣤⣄⠄⠄⠄⠄⠄⠄
⠄⠄⠄⠄⠄⢀⣴⣿⣿⣿⡿⠿⠿⠿⠿⢿⣷⡹⣿⣿⣿⣿⣿⣿⣷⠄⠄⠄⠄⠄
⠄⠄⠄⠄⠄⣾⣿⣿⣿⣯⣵⣾⣿⣿⡶⠦⠭⢁⠩⢭⣭⣵⣶⣶⡬⣄⣀⡀⠄⠄
⠄⠄⠄⡀⠘⠻⣿⣿⣿⣿⡿⠟⠩⠶⠚⠻⠟⠳⢶⣮⢫⣥⠶⠒⠒⠒⠒⠆⠐⠒
⠄⢠⣾⢇⣿⣿⣶⣦⢠⠰⡕⢤⠆⠄⠰⢠⢠⠄⠰⢠⠠⠄⡀⠄⢊⢯⠄⡅⠂⠄
⢠⣿⣿⣿⣿⣿⣿⣿⣏⠘⢼⠬⠆⠄⢘⠨⢐⠄⢘⠈⣼⡄⠄⠄⡢⡲⠄⠂⠠⠄
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣷⣥⣀⡁⠄⠘⠘⠘⢀⣠⣾⣿⢿⣦⣁⠙⠃⠄⠃⠐⣀
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣋⣵⣾⣿⣿⣿⣿⣦⣀⣶⣾⣿⣿⡉⠉⠉
⣿⣿⣿⣿⣿⣿⣿⠟⣫⣥⣬⣭⣛⠿⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡆⠄
⣿⣿⣿⣿⣿⣿⣿⠸⣿⣏⣙⠿⣿⣿⣶⣦⣍⣙⠿⠿⠿⠿⠿⠿⠿⠿⣛⣩⣶⠄
⣛⣛⣛⠿⠿⣿⣿⣿⣮⣙⠿⢿⣶⣶⣭⣭⣛⣛⣛⣛⠛⠛⠻⣛⣛⣛⣛⣋⠁⢀
⣿⣿⣿⣿⣿⣶⣬⢙⡻⠿⠿⣷⣤⣝⣛⣛⣛⣛⣛⣛⣛⣛⠛⠛⣛⣛⠛⣡⣴⣿
⣛⣛⠛⠛⠛⣛⡑⡿⢻⢻⠲⢆⢹⣿⣿⣿⣿⣿⣿⠿⠿⠟⡴⢻⢋⠻⣟⠈⠿⠿
⣿⡿⡿⣿⢷⢤⠄⡔⡘⣃⢃⢰⡦⡤⡤⢤⢤⢤⠒⠞⠳⢸⠃⡆⢸⠄⠟⠸⠛⢿
⡟⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠁⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⢸
\033[0m"

# Resto del script
welcome_message=$(get_language_message "\033[96mWelcome to the DVWA installer!\033[0m" "\033[96mBienvenido al instalador de DVWA!\033[0m")
echo -e "$welcome_message"

echo
# Inicio del Script
update_message=$(get_language_message "\e[96mUpdating repositories...\e[0m" "\e[96mActualizando repositorios...\e[0m")
echo -e "$update_message"
apt update

dependencies_message=$(get_language_message "\e[96mVerifying and installing necessary dependencies...\e[0m" "\e[96mVerificando e instalando dependencias necesarias...\e[0m")
echo -e "$dependencies_message"

check_program apache2
check_program mariadb-server
check_program mariadb-client
check_program php
check_program php-mysqli
check_program php-gd
check_program libapache2-mod-php
check_program git

download_message=$(get_language_message "\e[96mDownloading DVWA from GitHub...\e[0m" "\e[96mDescargando DVWA desde GitHub...\e[0m")
echo -e "$download_message"
git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
sleep 2

mysql_start_message=$(get_language_message "\e[96mStarting MySQL...\e[0m" "\e[96mIniciando MySQL...\e[0m")
echo -e "$mysql_start_message"
systemctl start mysql.service
sleep 2

# Prompt del usuario para la contraseña de MySQL root
mysql_root_password=$(get_mysql_root_password)

# Ejecutar comandos MySQL
mysql -u root -p$mysql_root_password -e "CREATE DATABASE IF NOT EXISTS dvwa;"
mysql -u root -p$mysql_root_password -e "CREATE USER 'dvwa'@'localhost' IDENTIFIED BY 'abc123';"
mysql -u root -p$mysql_root_password -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';"
mysql -u root -p$mysql_root_password -e "FLUSH PRIVILEGES;"
echo
# Mensaje de éxito
success_message=$(get_language_message "\e[92mMySQL commands executed successfully.\e[0m" "\e[92mComandos MySQL ejecutados correctamente.\e[0m")
echo -e "$success_message"

sleep 2

dvwa_config_message=$(get_language_message "\e[96mConfiguring DVWA...\e[0m" "\e[96mConfigurando DVWA...\e[0m")
echo -e "$dvwa_config_message"
cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
sed -i "s/\(\$_DVWA\[ 'db_password' \] = getenv('DVWA_DB_PASSWORD') ?: '\).*\('\)/\1abc123\2/" /var/www/html/DVWA/config/config.inc.php
sleep 2

permissions_config_message=$(get_language_message "\e[96mConfiguring permissions...\e[0m" "\e[96mConfigurando permisos...\e[0m")
echo -e "$permissions_config_message"
chown -R www-data:www-data /var/www/html/DVWA
chmod -R 755 /var/www/html/DVWA
sleep 2

php_config_message=$(get_language_message "\e[96mConfiguring PHP...\e[0m" "\e[96mConfigurando PHP...\e[0m")
echo -e "$php_config_message"
# Intentar encontrar el archivo php.ini en la carpeta de Apache
php_config_file_apache="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"

# Intentar encontrar el archivo php.ini en la carpeta de FPM
php_config_file_fpm="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/fpm/php.ini"

# Comprobar si existe el archivo php.ini en la carpeta de Apache y usarlo si está presente
if [ -f "$php_config_file_apache" ]; then
    php_config_file="$php_config_file_apache"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
# Comprobar si existe el archivo php.ini en la carpeta de FPM y usarlo si está presente
elif [ -f "$php_config_file_fpm" ]; then
    php_config_file="$php_config_file_fpm"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
else
    # Mensaje de advertencia si no se encuentra en ninguna de las carpetas
    echo -e "\e[91mWarning: PHP configuration file not found in Apache or FPM folders.\e[0m"
fi
sleep 2

apache_restart_message=$(get_language_message "\e[96mRestarting Apache...\e[0m" "\e[96mReiniciando Apache...\e[0m")
echo -e "$apache_restart_message"
systemctl restart apache2
sleep 2

credentials_message=$(get_language_message "\e[92mUsername and password for the first use:\e[0m" "\e[92mUsuario y contraseña para el primer uso:\e[0m")
echo -e "$credentials_message"
echo -e "Username: \033[93mdvwa\033[0m"
echo -e "Password: \033[93mabc123\033[0m"

success_message=$(get_language_message "\e[92mDVWA has been installed successfully. Access \e[93mhttp://localhost/DVWA\e[0m to get started." "\e[92mDVWA se ha instalado correctamente. Accede a \e[93mhttp://localhost/DVWA\e[0m para comenzar.")
echo -e "$success_message"

credentials_after_setup_message=$(get_language_message "\e[92mCredentials after setup:\e[0m" "\e[92mCredenciales después de la configuración:\e[0m")
echo -e "$credentials_after_setup_message"
echo -e "Username: \033[93madmin\033[0m"
echo -e "Password: \033[93mpassword\033[0m"

echo
echo -e "\033[91mCon ♡ by Iamcarron"
