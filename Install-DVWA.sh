#!/bin/bash

# Get language prefix
lang_prefix="${LANG:0:2}"

# Function for verifying the language and displaying the corresponding message
get_language_message() {
    if [[ $lang_prefix -eq "es" ]]; then
        echo -e "$1"
    else
        echo -e "$2"
    fi
}

# Check if the user is root
if [ "$EUID" -ne 0 ]; then
    error_message=$(get_language_message "\e[91mThis script must be run by the root user.\e[0m" "\e[91mEste script debe ejecutarse como usuario root.\e[0m")
    echo -e "$error_message"
    exit 1
fi

# Function to verify the existence of a program
check_program() {
    if ! dpkg-query -W -f='${Status}' "$1" 2>/dev/null | grep -q "install ok installed"; then
        message=$(get_language_message "\033[91m$1 is not installed. Installing it now..." "\033[91m$1 no está instalado. Instalándolo ahora...")
        echo -e >&2 "$message"
        apt install -y "$1"
    else
        success_message=$(get_language_message "\033[92m$1 is installed!\033[0m" "\033[92m$1 !Está instalado!\033[0m")
        echo -e "$success_message"
    fi
}

# MySQL root password prompt function
get_mysql_root_password() {
    read -s -p "$(get_language_message "Enter MySQL root password: " "Ingrese la contraseña de root de MySQL: ")" mysql_root_password
    echo -e "$mysql_root_password"
}
# ASCII Art
echo -e "\033[96m\033[1m
                  ██████╗ ██╗   ██╗██╗    ██╗ █████╗                    
                  ██╔══██╗██║   ██║██║    ██║██╔══██╗                   
                  ██║  ██║██║   ██║██║ █╗ ██║███████║                   
                  ██║  ██║╚██╗ ██╔╝██║███╗██║██╔══██║                   
                  ██████╔╝ ╚████╔╝ ╚███╔███╔╝██║  ██║                   
                  ╚═════╝   ╚═══╝   ╚══╝╚══╝ ╚═╝  ╚═╝                   
                                                                        
  ██╗███╗   ██╗███████╗████████╗ █████╗ ██╗     ██╗     ███████╗██████╗ 
  ██║████╗  ██║██╔════╝╚══██╔══╝██╔══██╗██║     ██║     ██╔════╝██╔══██╗
  ██║██╔██╗ ██║███████╗   ██║   ███████║██║     ██║     █████╗  ██████╔╝
  ██║██║╚██╗██║╚════██║   ██║   ██╔══██║██║     ██║     ██╔══╝  ██╔══██╗
  ██║██║ ╚████║███████║   ██║   ██║  ██║███████╗███████╗███████╗██║  ██║
  ╚═╝╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝╚═╝  ╚═╝                                                                        
\033[0m"

welcome_message=$(get_language_message "\033[96mWelcome to the DVWA installer!\033[0m" "\033[96m¡Bienvenido al instalador de DVWA!\033[0m")
echo -e "$welcome_message"

echo
# Start of the installer

# Update the repositories
update_message=$(get_language_message "\e[96mUpdating repositories...\e[0m" "\e[96mActualizando repositorios...\e[0m")
echo -e "$update_message"
apt update

# Chek if the dependencies are installed
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

# Download the repo
download_message=$(get_language_message "\e[96mDownloading DVWA from GitHub...\e[0m" "\e[96mDescargando DVWA desde GitHub...\e[0m")
echo -e "$download_message"
git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
sleep 2

# Start MySql
mysql_start_message=$(get_language_message "\e[96mStarting MySQL...\e[0m" "\e[96mIniciando MySQL...\e[0m")
echo -e "$mysql_start_message"
systemctl start mysql.service
sleep 2

# User Prompt for the MySQL root password
mysql_root_password=$(get_mysql_root_password)

# Run MySQL commands
mysql -u root -p$mysql_root_password -e "CREATE DATABASE IF NOT EXISTS dvwa;"
mysql -u root -p$mysql_root_password -e "CREATE USER 'dvwa'@'localhost' IDENTIFIED BY 'p@ssw0rd';"
mysql -u root -p$mysql_root_password -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';"
mysql -u root -p$mysql_root_password -e "FLUSH PRIVILEGES;"
echo

# Success message
success_message=$(get_language_message "\e[92mMySQL commands executed successfully.\e[0m" "\e[92mComandos MySQL ejecutados correctamente.\e[0m")
echo -e "$success_message"

sleep 2

# Coping DVWA folder to /var/www/html
dvwa_config_message=$(get_language_message "\e[96mConfiguring DVWA...\e[0m" "\e[96mConfigurando DVWA...\e[0m")
echo -e "$dvwa_config_message"
cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
sleep 2

# Assign the right permissions to DVWA
permissions_config_message=$(get_language_message "\e[96mConfiguring permissions...\e[0m" "\e[96mConfigurando permisos...\e[0m")
echo -e "$permissions_config_message"
chown -R www-data:www-data /var/www/html/DVWA
chmod -R 755 /var/www/html/DVWA
sleep 2

php_config_message=$(get_language_message "\e[96mConfiguring PHP...\e[0m" "\e[96mConfigurando PHP...\e[0m")
echo -e "$php_config_message"
# Trying to find the php.ini file in the Apache folder
php_config_file_apache="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"

# Trying to find the php.ini file in the FPM folder
php_config_file_fpm="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/fpm/php.ini"

# Check if the php.ini file exists in the Apache folder and use it if it is present.
if [ -f "$php_config_file_apache" ]; then
    php_config_file="$php_config_file_apache"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
# Check if the php.ini file exists in the FPM folder and use it if it is present.
elif [ -f "$php_config_file_fpm" ]; then
    php_config_file="$php_config_file_fpm"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
else
    # Warning message if not found in any of the folders
    php_file_message=$(get_language_message "\e[91mWarning: PHP configuration file not found in Apache or FPM folders.\e[0m" "\e[91mAdvertencia: No se encuentra el fichero de configuración PHP en las carpetas de Apache o FPM.\e[0m")
    echo -e "$php_file_message"
fi
sleep 2

# Apache restart
apache_restart_message=$(get_language_message "\e[96mRestarting Apache...\e[0m" "\e[96mReiniciando Apache...\e[0m")
echo -e "$apache_restart_message"
systemctl restart apache2
sleep 2

# Show to user the credentials for first use
credentials_message=$(get_language_message "\e[92mUsername and password for the first use:\e[0m" "\e[92mUsuario y contraseña para el primer uso:\e[0m")
echo -e "$credentials_message"
echo -e "Username: \033[93mdvwa\033[0m"
echo -e "Password: \033[93mp@ssw0rd\033[0m"

success_message=$(get_language_message "\e[92mDVWA has been installed successfully. Access \e[93mhttp://localhost/DVWA\e[0m \e[92mto get started." "\e[92mDVWA se ha instalado correctamente. Accede a \e[93mhttp://localhost/DVWA\e[0m \e[92mpara comenzar.")
echo -e "$success_message"

#Show to user the credentials after setup
credentials_after_setup_message=$(get_language_message "\e[92mCredentials after setup:\e[0m" "\e[92mCredenciales después de la configuración:\e[0m")
echo -e "$credentials_after_setup_message"
echo -e "Username: \033[93madmin\033[0m"
echo -e "Password: \033[93mpassword\033[0m"

# End of the installer
echo
final_message=$(get_language_message "\033[91mWith ♡ by IamCarron" "\033[91mCon ♡ by IamCarron")
echo -e "$final_message"
