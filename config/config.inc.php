<?php

# If you are having problems connecting to the MySQL database and all of the variables below are correct
# try changing the 'db_server' variable from localhost to 127.0.0.1. Fixes a problem due to sockets.
# Thanks to digininja for the fix.

# Database management system to use

$DBMS = 'MySQL';
#$DBMS = 'PGSQL';

# Database variables
# WARNING: The database specified under db_database WILL BE ENTIRELY DELETED during setup. 
# Please use a database dedicated to DVWA.

$_DVWA = array();
$_DVWA[ 'db_server' ] = 'localhost';
$_DVWA[ 'db_database' ] = 'dvwa';
$_DVWA[ 'db_user' ] = 'root';
$_DVWA[ 'db_password' ] = 'p@ssw0rd';

# Only needed for PGSQL
$_DVWA[ 'db_port' ] = '5432'; 

# ReCAPTCHA Settings
# Get your keys at https://www.google.com/recaptcha/admin/create
$_DVWA['recaptcha_public_key'] = "";
$_DVWA['recaptcha_private_key'] = "";

# Default Security Level
# The default is high, you may wish to set this to either low or medium.
# If you specify an invalid level, DVWA will default to high.
$_DVWA['default_security_level'] = "high";

?>
