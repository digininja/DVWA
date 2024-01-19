#!/bin/bash

# Obtener prefijo de idioma / Get language prefix
lang_prefix="${LANG:0:2}"

# Función para verificar el idioma y mostrar el mensaje correspondiente / Function for verifying the language and displaying the corresponding message
get_language_message() {
    if [[ $lang_prefix -eq "es" ]]; then
        echo -e "$1"
    else
        echo -e "$2"
    fi
}

# Comprueba si el usuario es root / Check if the user is root
if [ "$EUID" -ne 0 ]; then
    error_message=$(get_language_message "\e[91mThis script must be run by the root user.\e[0m" "\e[91mEste script debe ejecutarse como usuario root.\e[0m")
    echo -e "$error_message"
    exit 1
fi

# Arte ASCII / ASCII Art
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

welcome_message=$(get_language_message "\033[96mWelcome to the DVWA setup!\033[0m" "\033[96m¡Bienvenido al instalador de DVWA!\033[0m")
echo -e "$welcome_message"
echo -e "\n$(get_language_message "\033[92mScript Name: Install-DVWA.sh\033[0m" "\033[92mNombre del Script: Install-DVWA.sh\033[0m")"
echo -e "\n$(get_language_message "\033[92mAuthor: IamCarron\033[0m" "\033[92mAutor: IamCarron\033[0m")"
echo -e "\n$(get_language_message "\033[92mGithub Repository: https://github.com/IamCarron/DVWA-Script\033[0m" "\033[92mRepositorio de Github: https://github.com/IamCarron/DVWA-Script\033[0m")"
echo
# Función para verificar la existencia de un programa / Function to verify the existence of a program
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
        echo
        # Verificar si las credenciales son válidas antes de ejecutar comandos MySQL / Verify if credentials are valid before executing MySQL commands
        if ! mysql -u "$mysql_user" -p"$mysql_password" -e ";" &>/dev/null; then
            echo -e "\n$(get_language_message "\e[91mError: Invalid MySQL credentials. Please check your username and password.\e[0m" "\e[91mError: Credenciales MySQL inválidas. Por favor, compruebe su nombre de usuario y contraseña.")"
        else
            break
        fi
    done

    local success=false
    while [ "$success" != true ]; do
        # Ejecutar comandos MySQL / Execute MySQL commands
        mysql_commands_output=$(mysql_commands "$mysql_user" "$mysql_password")

        if [ $? -eq 0 ]; then
            echo -e "$(get_language_message "\033[92mMySQL commands executed successfully.\033[0m" "\033[92mComandos MySQL ejecutados con éxito.\033[0m")"
            success=true
        else
            if [ "$recreate_choice" != "no" ]; then
                break
            fi
        fi
    done
}

mysql_commands() {
    local mysql_user="$1"
    local mysql_password="$2"
    local mysql_command="mysql -u '$mysql_user'"

    if [ -n "$mysql_password" ]; then
        mysql_command+=" -p'$mysql_password'"
    fi

    # Verificar si la base de datos y el usuario ya existen / Verify if the database and user already exist
    if $mysql_command -e "SHOW DATABASES LIKE 'dvwa';" | grep -q 'dvwa'; then
        # Cambiar temporalmente el descriptor de entrada estándar / Temporarily change the standard input descriptor
        exec 3<&0
        read -p "$(get_language_message "\e[96mDatabase 'dvwa' already exists. Do you want to recreate it? (yes/no):\e[96m " "\e[96mLa base de datos 'dvwa' ya existe. ¿Desea volver a crearla? (sí/no):\e[0m ")" recreate_choice <&3
        exec 3<&-
        if [ "$recreate_choice" = "yes" ]; then
            # Eliminar la base de datos existente y recrearla / Delete the existing database and recreate it
            $mysql_command -e "DROP DATABASE IF EXISTS dvwa;" &>/dev/null &&
            $mysql_command -e "CREATE DATABASE dvwa;" &>/dev/null ||
            { echo -e "$(get_language_message "\033[91mAn error occurred while creating the DVWA database." "\033[91mSe ha producido un error al crear la base de datos DVWA.")"; return 1; }
        else
            return 1  # Indicar que hay un error (no se recreó la base de datos) / Indicate that there is an error (the database has not been recreated).
        fi
    fi

    # Verificar si el usuario ya existe / Check if the user already exists
    if $mysql_command -e "SELECT user FROM mysql.user WHERE user='dvwa';" | grep -q 'dvwa'; then
        # Cambiar temporalmente el descriptor de entrada estándar / Temporarily change the standard input descriptor
        exec 3<&0
        read -p "$(get_language_message "\n\e[96mMySQL user 'dvwa' already exists. Do you want to recreate it? (yes/no):\e[96m " "\n\e[96mEl usuario de MySQL 'dvwa' ya existe. ¿Desea volver a crearlo? (sí/no):\e[0m ")" recreate_user_choice <&3
        exec 3<&-
        if [ "$recreate_user_choice" = "yes" ]; then
            # Eliminar el usuario existente y recrearlo / Delete the existing user and recreate it
            $mysql_command -e "DROP USER IF EXISTS 'dvwa'@'localhost';" &>/dev/null &&
            $mysql_command -e "CREATE USER 'dvwa'@'localhost' IDENTIFIED BY 'p@ssw0rd';" &>/dev/null ||
            { echo -e "$(get_language_message "\033[91mAn error occurred while creating the DVWA user." "\033[91mSe ha producido un error al crear el usuario DVWA.")"; return 1; }
        else
            return 1  # Indicar que hay un error (no se recreó el usuario) / Indicate error (user was not recreated)
        fi
    fi

    # Ejecutar comandos MySQL para asignar privilegios / Executing MySQL commands to assign privileges
    $mysql_command -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';" &>/dev/null &&
    $mysql_command -e "FLUSH PRIVILEGES;" &>/dev/null

    echo $?
}

# Inicio del instalador / Installer startup

# Actualizar los repositorios / Update repositories
update_message=$(get_language_message "\e[96mUpdating repositories...\e[0m" "\e[96mActualizando repositorios...\e[0m")
echo -e "$update_message"
apt update

# Comprueba si las dependencias están instaladas / Check if the dependencies are installed
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

# Descargar el repositorio DVWA desde GitHub / Download DVWA repository from GitHub

# Comprobando si la carpeta ya existe / Checking if the folder already exists
if [ -d "/var/www/html/DVWA" ]; then
    # La carpeta ya existe / The folder already exists
    warning_message=$(get_language_message "\e[91m¡Atención! La carpeta DVWA ya está creada.\e[0m" "\e[91mAttention! The DVWA folder is already created.\e[0m")
    echo -e "$warning_message"

    # Preguntar al usuario qué acción tomar / Ask the user what action to take
    read -p "$(get_language_message "\e[96m¿Desea borrar la carpeta existente y descargarla de nuevo? (s/n):\e[0m " "\e[96mDo you want to delete the existing folder and download it again (y/n):\e[0m ")" user_response

    if [ "$user_response" == "s" ]; then
        # Borrar la carpeta existente / Delete existing folder
        rm -rf /var/www/html/DVWA

        # Descargar DVWA desde GitHub / Download DVWA from GitHub
        download_message=$(get_language_message "\e[96mDescargando DVWA desde GitHub...\e[0m" "\e[96mDownloading DVWA from GitHub...\e[0m")
        echo -e "$download_message"
        git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
        sleep 2
    elif [ "$user_response" == "n" ]; then
        # El usuario elige no descargar / User chooses not to download
        no_download_message=$(get_language_message "\e[96mContinuando sin descargar DVWA.\e[0m" "\e[96mContinuing without downloading DVWA.\e[0m")
        echo -e "$no_download_message"
    else
        # Respuesta inválida / Invalid answer
        invalid_message=$(et_language_message "\e[91m¡Error! Respuesta no válida. Saliendo del script.\e[0m" "\e[91mError! Invalid response. Exiting the script.\e[0m")
        echo -e "$invalid_message"
        exit 1
    fi
else
    # La carpeta no existe, descargar DVWA desde GitHub / Folder does not exist, download DVWA from GitHub
    download_message=$(get_language_message "\e[96mDescargando DVWA desde GitHub...\e[0m" "\e[96mDownloading DVWA from GitHub...\e[0m")
    echo -e "$download_message"
    git clone https://github.com/digininja/DVWA.git /var/www/html/DVWA
    sleep 2
fi

# Verificar si MySQL ya está iniciado / Check if MySQL is already started
if systemctl is-active --quiet mysql.service; then
    mysql_already_started_message=$(get_language_message "\033[92mMySQL service is already running.\033[0m" "\033[92mEl servicio MySQL ya está en ejecución.\033[0m")
    echo -e "$mysql_already_started_message"
else
    # Iniciar MySQL / Start MySQL
    mysql_start_message=$(get_language_message "\e[96mStarting MySQL...\e[0m" "\e[96mIniciando MySQL...\e[0m")
    echo -e "$mysql_start_message"
    systemctl start mysql.service
    sleep 2
fi

# Llama a la función / Call the function
run_mysql_commands
sleep 2

# Copia de la carpeta DVWA a /var/www/html / Coping DVWA folder to /var/www/html
dvwa_config_message=$(get_language_message "\e[96mConfiguring DVWA...\e[0m" "\e[96mConfigurando DVWA...\e[0m")
echo -e "$dvwa_config_message"
cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
sleep 2

# Asignar los permisos adecuados a DVWA / Assign the appropriate permissions to DVWA
permissions_config_message=$(get_language_message "\e[96mConfiguring permissions...\e[0m" "\e[96mConfigurando permisos...\e[0m")
echo -e "$permissions_config_message"
chown -R www-data:www-data /var/www/html/DVWA
chmod -R 755 /var/www/html/DVWA
sleep 2

php_config_message=$(get_language_message "\e[96mConfiguring PHP...\e[0m" "\e[96mConfigurando PHP...\e[0m")
echo -e "$php_config_message"
# Intentando encontrar el archivo php.ini en la carpeta Apache / Trying to find the php.ini file in the Apache folder
php_config_file_apache="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"

# Intentando encontrar el archivo php.ini en la carpeta FPM / Trying to find the php.ini file in the FPM folder
php_config_file_fpm="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/fpm/php.ini"

# Comprueba si el archivo php.ini existe en la carpeta de Apache y úsalo si está presente. / Check if the php.ini file exists in the Apache folder and use it if present.
if [ -f "$php_config_file_apache" ]; then
    php_config_file="$php_config_file_apache"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
# Comprueba si el archivo php.ini existe en la carpeta FPM y úsalo si está presente. / Check if the php.ini file exists in the FPM folder and use it if present.
elif [ -f "$php_config_file_fpm" ]; then
    php_config_file="$php_config_file_fpm"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
else
    # Mensaje de advertencia si no se encuentra en ninguna de las carpetas / Warning message if not found in any of the folders
    php_file_message=$(get_language_message "\e[91mWarning: PHP configuration file not found in Apache or FPM folders.\e[0m" "\e[91mAdvertencia: No se encuentra el fichero de configuración PHP en las carpetas de Apache o FPM.\e[0m")
    echo -e "$php_file_message"
fi
sleep 2

# Reinicia el Apache / Apache restart
apache_restart_message=$(get_language_message "\e[96mRestarting Apache...\e[0m" "\e[96mReiniciando Apache...\e[0m")
echo -e "$apache_restart_message"
systemctl restart apache2
sleep 2

success_message=$(get_language_message "\e[92mDVWA has been installed successfully. Access \e[93mhttp://localhost/DVWA\e[0m \e[92mto get started." "\e[92mDVWA se ha instalado correctamente. Accede a \e[93mhttp://localhost/DVWA\e[0m \e[92mpara comenzar.")
echo -e "$success_message"

#Mostrar al usuario las credenciales después de la configuración / Show user credentials after configuration
credentials_after_setup_message=$(get_language_message "\e[92mCredentials:\e[0m" "\e[92mCredenciales:\e[0m")
echo -e "$credentials_after_setup_message"
echo -e "Username: \033[93madmin\033[0m"
echo -e "Password: \033[93mpassword\033[0m"

# Fin del instalador / End of installer
echo
final_message=$(get_language_message "\033[95mWith ♡ by IamCarron" "\033[95mCon ♡ by IamCarron")
echo -e "$final_message"
