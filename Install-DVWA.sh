#!/bin/bash
clear

# Verify if the user is root
if [ "$EUID" -ne 0 ]; then
    echo -e "\e[91mThis script must be run by the root user.\e[0m"
    exit 1
fi

# Function to check the existence of a program
check_program() {
    command -v "$1" >/dev/null 2>&1 || {
        echo -e >&2 "\033[91m$1 is not installed. Installing it now..."
        apt install -y "$1" > /dev/null 2>&1
    }
}

# ASCII Art
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

echo -e "\033[96mWelcome to the DVWA installer!\033[0m"

echo
echo
# Function to display instructions in English
show_english_instructions() {
	echo -e "\e[96mUpdating repositories...\e[0m"
	apt update > /dev/null 2>&1
	clear
    
	echo -e "\e[96mVerifying and installing necessary dependencies...\e[0m"
    
    check_program apache2
    check_program mariadb-server
    check_program mariadb-client
    check_program php
    check_program php-mysqli
    check_program php-gd
    check_program libapache2-mod-php
    check_program git
    sleep 2
	clear
    
	echo -e "\e[96mDownloading DVWA from GitHub...\e[0m"
    git clone https://github.com/ethicalhack3r/DVWA.git /var/www/html/DVWA
	sleep 2
	clear
    
	echo -e "\e[96mStarting MySQL...\e[0m"
    systemctl start mysql.service
	sleep 2
	clear
    
	echo -e "\e[96mConfiguring the database for DVWA...\e[0m"
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS dvwa;"
    mysql -u root -e "CREATE USER 'dvwa'@'localhost' IDENTIFIED BY 'abc123';"
    mysql -u root -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';"
    mysql -u root -e "FLUSH PRIVILEGES;"
	sleep 2
	
    echo -e "\e[96mConfiguring DVWA...\e[0m"
    cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
    sed -i "s/\(\$_DVWA\[ 'db_password' \] = '\).*\('\)/\1abc123\2/" /var/www/html/DVWA/config/config.inc.php
    sleep 2

    echo -e "\e[96mConfiguring permissions...\e[0m"
    chown -R www-data:www-data /var/www/html/DVWA
    chmod -R 755 /var/www/html/DVWA
    sleep 2

    echo -e "\e[96mConfiguring PHP...\e[0m"
    php_config_file="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"
    sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
    sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
    sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
    sleep 2

    echo -e "\e[96mRestarting Apache...\e[0m"
    systemctl restart apache2
    sleep 2

    echo -e "\e[92mUsername and password for the first use:\e[0m"
	echo -e "Username: \033[93mdvwa\033[0m"
	echo -e "Password: \033[93mabc123\033[0m"

    echo -e "\e[92mDVWA has been installed successfully. Access \e[93mhttp://localhost/DVWA\e[0m to get started."

    echo -e "\e[92mCredentials after setup:\e[0m"
	echo -e "Username: \033[93madmin\033[0m"
	echo -e "Password: \033[93mpassword\033[0m"
  echo
  echo
  echo -e "\033[91mWith ♡ by Iamcarron"
}
# Function to display instructions in Spanish
show_spanish_instructions() {
	# Mensajes informativos con formato en color para resaltar
	echo -e "\e[96mActualizando repositorios...\e[0m"
	apt update
	clear

	echo -e "\e[96mVerificando e instalando dependencias necesarias...\e[0m"

	# Comprobando la existencia de programas necesarios
	check_program apache2
	check_program mariadb-server
	check_program mariadb-client
	check_program php
	check_program php-mysqli
	check_program php-gd
	check_program libapache2-mod-php
	check_program git
	sleep 2
	clear

	echo -e "\e[96mDescargando DVWA desde GitHub...\e[0m"
	git clone https://github.com/ethicalhack3r/DVWA.git /var/www/html/DVWA
	sleep 2
	clear

	echo -e "\e[96mIniciando MySQL...\e[0m"
	systemctl start mysql.service
	sleep 2
	clear

	echo -e "\e[96mConfigurando la base de datos para DVWA...\e[0m"
	mysql -u root -e "CREATE DATABASE IF NOT EXISTS dvwa;"
	mysql -u root -e "CREATE USER 'dvwa'@'localhost' IDENTIFICADO POR 'abc123';"
	mysql -u root -e "GRANT ALL PRIVILEGES ON dvwa.* TO 'dvwa'@'localhost';"
	mysql -u root -e "FLUSH PRIVILEGES;"
	sleep 2

	echo -e "\e[96mConfigurando DVWA...\e[0m"
	cp /var/www/html/DVWA/config/config.inc.php.dist /var/www/html/DVWA/config/config.inc.php
	sed -i "s/\(\$_DVWA\[ 'db_password' \] = '\).*\('\)/\1abc123\2/" /var/www/html/DVWA/config/config.inc.php
	sleep 2

	echo -e "\e[96mConfigurando permisos...\e[0m"
	chown -R www-data:www-data /var/www/html/DVWA
	chmod -R 755 /var/www/html/DVWA
	sleep 2

	echo -e "\e[96mConfigurando PHP...\e[0m"
	php_config_file="/etc/php/$(php -r 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')/apache2/php.ini"
	sed -i 's/^\(allow_url_include =\).*/\1 on/' $php_config_file
	sed -i 's/^\(allow_url_fopen =\).*/\1 on/' $php_config_file
	sed -i 's/^\(display_errors =\).*/\1 on/' $php_config_file
	sed -i 's/^\(display_startup_errors =\).*/\1 on/' $php_config_file
	sleep 2

	echo -e "\e[96mReiniciando Apache...\e[0m"
	systemctl restart apache2
	sleep 2

	# Mensajes de finalización y credenciales de acceso
	echo -e "\e[92mUsuario y contraseña para el primer uso:\e[0m"
	echo -e "Usuario: \033[93mdvwa\033[0m"
	echo -e "Contraseña: \033[93mabc123\033[0m"

	echo -e "\e[92mDVWA se ha instalado correctamente. Accede a \e[93mhttp://localhost/DVWA\e[0m para comenzar."

	echo -e "\e[92mCredenciales después de la configuración:\e[0m"
	echo -e "Usuario: \033[93madmin\033[0m"
	echo -e "Contraseña: \033[93mpassword\033[0m"
  echo
  echo
  echo -e "\033[91mCon ♡ by Iamcarron"
}

# Verify the language and execute the corresponding instructions
if [[ $LANG == *"es"* ]]; then
    # Instructions in English
    echo -e "\e[94mEste script está configurado para ejecutarse en español.\e[0m"
    echo -e "\e[94mEjecutando instrucciones en español...\e[0m"
    show_spanish_instructions
else
    # Instructions in English (default)
    echo -e "\e[94mThis script is configured to run in English.\e[0m"
    echo -e "\e[94mRunning instructions in English...\e[0m"
    show_english_instructions
fi
