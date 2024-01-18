#!/bin/bash

# Obtener prefijo de idioma
lang_prefix="${LANG:0:2}"

# Función para verificar el idioma y mostrar el mensaje correspondiente
get_language_message() {
    if [[ $lang_prefix -eq "es" ]]; then
        echo -e "$1"
    else
        echo -e "$2"
    fi
}

# Comprueba si el usuario es root
if [ "$EUID" -ne 0 ]; then
    error_message=$(get_language_message "\e[91mThis script must be run by the root user.\e[0m" "\e[91mEste script debe ejecutarse como usuario root.\e[0m")
    echo -e "$error_message"
    exit 1
fi

# Arte ASCII
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

# Función para verificar la existencia de un programa
check_program() {
    if ! dpkg-query -W -f='${Status}' "$1" 2>/dev/null | grep -q "install ok installed"; then
        message=$(get_language_message "\033[91m$1 is not installed. Installing it now...\e[0m" "\033[91m$1 no está instalado. Instalándolo ahora...\e[0m")
        echo -e >&2 "$message"
        apt install -y "$1"
    else
        success_message=$(get_language_message "\033[92m$1 is installed!\033[0m" "\033[92m$1 !Está instalado!\033[0m")
        echo -e "$success_message"
    fi
}

run_mysql_commands() {
    local mysql_user
    local mysql_password

    while true; do
        echo -e "\n$(get_language_message "\e[96mDefault credentials:\e[0m" "\e[96mCredenciales por defecto:\e[0m")"
        echo -e "Username: \033[93mroot\033[0m"
        echo -e "\n$(get_language_message "Password: \033[93m[No password just hit Enter]\033[0m" "Password: \033[93m[Sin contraseña solo presiona Enter.]\033[0m")"
        read -p "$(get_language_message "\e[96mEnter MySQL user:\e[0m " "\e[96mIngrese el usuario de MySQL:\e[0m ")" mysql_user
        read -s -p "$(get_language_message "\e[96mEnter MySQL password (press Enter for no password):\e[96m " "\e[96mIngrese la contraseña de MySQL (presiona Enter si no hay contraseña):\e[0m ")" mysql_password

        # Verificar si las credenciales son válidas antes de ejecutar comandos MySQL
        if ! mysql -u "$mysql_user" -p"$mysql_password" -e ";" &>/dev/null; then
            echo -e "\e[91mError: Invalid MySQL credentials. Please check your username and password.\e[0m"
        else
            break
        fi
    done

    local success=false
    while [ "$success" != true ]; do
        # Ejecutar comandos MySQL
        mysql_commands_output=$(mysql_commands "$mysql_user" "$mysql_password")

        if [ $? -eq 0 ]; then
            echo -e "$(get_language_message "\033[92mMySQL commands executed successfully.\033[0m" "\033[92mComandos MySQL ejecutados con éxito.\033[0m")"
            success=true
        else
            echo -e "$(get_language_message "\033[91mError: Unable to execute MySQL commands. $mysql_commands_output" "\033[91mError: No se pueden ejecutar los comandos de MySQL. $mysql_commands_output")"
            read -p "$(get_language_message "\e[96mDo you want to retry? (yes/no):\e[0m " "\e[96m¿Quieres intentarlo de nuevo? (sí/no):\e[0m ")" choice
            if [ "$choice" != "yes" ]; then
                break
            fi
        fi
    done
}

mysql_commands() {
    local mysql_user="$1"
    local mysql_password="$2"

    # Ejecutar comandos MySQL con o sin contraseña
    mysql_command="mysql -u '$mysql_user'"

    if [ -n "$mysql_password" ]; then
        mysql_command+=" -p'$mysql_password'"
    fi

    $mysql_command -e "CREATE DATABASE IF NOT EXISTS dvwa;" &>/dev/null &&
    $mysql_command -e "CREATE USER 'dvwa'@'localhost' IDENTIFIED BY 'p@ssw0rd';" &>/dev/null &&
    $mysql_command -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';" &>/dev/null &&
    $mysql_command -e "FLUSH PRIVILEGES;" &>/dev/null

    echo $?
}

# Inicio del instalador

# Actualizar los repositorios
update_message=$(get_language_message "\e[96mUpdating repositories...\e[0m" "\e[96mActualizando repositorios...\e[0m")
echo -e "$update_message"
apt update

# Comprueba si las dependencias están instaladas
dependencies_message=$(get_language_message "\e[96mVerifying and installing necessary dependencies...\e[0m" "\e[96mVerificando e instalando dependencias necesarias...\e[0m")
echo -e "$dependencies_message"

check_program apache2
check_program mariadb-server
check_program mariadb-client
check_program php
check_program php-mysql
check_program php-gd
check_program libapache2-mod-php
check_program git

# Descargar el repositorio DVWA desde GitHub

# Comprobando si la carpeta ya existe
if [ -d "/var/www/html/DVWA" ]; then
    # La carpeta ya existe
    warning_message=$(get_language_message "\e[91m¡Atención! La carpeta DVWA ya está creada.\e[0m" "\e[91mAttention! The DVWA folder is already created.\e[0m")
    echo -e "$warning_message"

    # Preguntar al usuario qué acción tomar
    read -p "$(get_language_message "\e[96m¿Desea borrar la carpeta existente y descargarla de nuevo? (s/n): \e[0m " "\e[96mDo you want to delete the existing folder and download it again (y/n): \e[0m ")" user_response

    if [ "$user_response" == "s" ]; then
        # Borrar la carpeta existente
        rm -rf /var/www/html/DVWA

        # Descargar DVWA desde GitHub
        download_message=$(get_language_message "\e[96mDescargando DVWA desde GitHub...\e[0m" "\e[96mDownloading DVWA from GitHub...\e[0m")
        echo -e "$download_message"
        git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
        sleep 2
    elif [ "$user_response" == "n" ]; then
        # El usuario elige no descargar
        no_download_message=$(get_language_message "\e[96mContinuando sin descargar DVWA.\e[0m" "\e[96mContinuing without downloading DVWA.\e[0m")
        echo -e "$no_download_message"
    else
        # Respuesta inválida
        invalid_message=$(et_language_message "\e[91m¡Error! Respuesta no válida. Saliendo del script.\e[0m" "\e[91mError! Invalid response. Exiting the script.\e[0m")
        echo -e "$invalid_message"
        exit 1
    fi
else
    # La carpeta no existe, descargar DVWA desde GitHub
    download_message=$(get_language_message "\e[96mDescargando DVWA desde GitHub...\e[0m" "\e[96mDownloading DVWA from GitHub...\e[0m")
    echo -e "$download_message"
    git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
    sleep 2
fi

# Iniciar MySql
mysql_start_message=$(get_language_message "\e[96mStarting MySQL...\e[0m" "\e[96mIniciando MySQL...\e[0m")
echo -e "$mysql_start_message"
systemctl start mysql.service
sleep 2

# Llama a la función
run_mysql_commands

sleep 2

# Coping DVWA folder to /var/www/html
dvwa_config_message=$(get_language_message "\e[96mConfiguring DVWA...\e[0m" "\e[96mConfigurando DVWA...\e[0m")
echo -e "$dvwa_config_message"
cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
sleep 2

# Asignar los permisos adecuados a DVWA
permissions_config_message=$(get_language_message "\e[96mConfiguring permissions...\e[0m" "\e[96mConfigurando permisos...\e[0m")
echo -e "$permissions_config_message"
chown -R www-data:www-data /var/www/html/DVWA
chmod -R 755 /var/www/html/DVWA
sleep 2

php_config_message=$(get_language_message "\e[96mConfiguring PHP...\e[0m" "\e[96mConfigurando PHP...\e[0m")
echo -e "$php_config_message"
# Intentando encontrar el archivo php.ini en la carpeta Apache
php_config_file_apache="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"

# Intentando encontrar el archivo php.ini en la carpeta FPM
php_config_file_fpm="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/fpm/php.ini"

# Comprueba si el archivo php.ini existe en la carpeta de Apache y úsalo si está presente.
if [ -f "$php_config_file_apache" ]; then
    php_config_file="$php_config_file_apache"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
# Comprueba si el archivo php.ini existe en la carpeta FPM y úsalo si está presente.
elif [ -f "$php_config_file_fpm" ]; then
    php_config_file="$php_config_file_fpm"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
else
    # Mensaje de advertencia si no se encuentra en ninguna de las carpetas
    php_file_message=$(get_language_message "\e[91mWarning: PHP configuration file not found in Apache or FPM folders.\e[0m" "\e[91mAdvertencia: No se encuentra el fichero de configuración PHP en las carpetas de Apache o FPM.\e[0m")
    echo -e "$php_file_message"
fi
sleep 2

# Apache restart
apache_restart_message=$(get_language_message "\e[96mRestarting Apache...\e[0m" "\e[96mReiniciando Apache...\e[0m")
echo -e "$apache_restart_message"
systemctl restart apache2
sleep 2

success_message=$(get_language_message "\e[92mDVWA has been installed successfully. Access \e[93mhttp://localhost/DVWA\e[0m \e[92mto get started." "\e[92mDVWA se ha instalado correctamente. Accede a \e[93mhttp://localhost/DVWA\e[0m \e[92mpara comenzar.")
echo -e "$success_message"

#Mostrar al usuario las credenciales después de la configuración
credentials_after_setup_message=$(get_language_message "\e[92mCredentials:\e[0m" "\e[92mCredenciales:\e[0m")
echo -e "$credentials_after_setup_message"
echo -e "Username: \033[93madmin\033[0m"
echo -e "Password: \033[93mpassword\033[0m"

# Fin del instalador
echo
final_message=$(get_language_message "\033[91mWith ♡ by IamCarron" "\033[91mCon ♡ by IamCarron")
echo -e "$final_message"
