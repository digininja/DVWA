<?php

/*

This file contains all of the code to setup the initial MySQL database. (setup.php)

*/

if( !defined( 'DVWA_WEB_PAGE_TO_ROOT' ) ) {
	define( 'DVWA_WEB_PAGE_TO_ROOT', '../../../' );
}

if( !@($GLOBALS["___mysqli_ston"] = mysqli_connect( $_DVWA[ 'db_server' ],  $_DVWA[ 'db_user' ],  $_DVWA[ 'db_password' ], "", $_DVWA[ 'db_port' ] )) ) {
	dvwaMessagePush( "Could not connect to the database service.<br />Please check the config file.<br />Database Error #" . mysqli_connect_errno() . ": " . mysqli_connect_error() . "." );
	if ($_DVWA[ 'db_user' ] == "root") {
		dvwaMessagePush( 'Your database user is root, if you are using MariaDB, this will not work, please read the README.md file.' );
	}
	dvwaPageReload();
}

// Create database
$drop_db = "DROP DATABASE IF EXISTS {$_DVWA[ 'db_database' ]};";
if( !@mysqli_query($GLOBALS["___mysqli_ston"],  $drop_db ) ) {
	dvwaMessagePush( "Could not drop existing database<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
	dvwaPageReload();
}

$create_db = "CREATE DATABASE {$_DVWA[ 'db_database' ]};";
if( !@mysqli_query($GLOBALS["___mysqli_ston"],  $create_db ) ) {
	dvwaMessagePush( "Could not create database<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
	dvwaPageReload();
}
dvwaMessagePush( "Database has been created." );


// Create table 'users'
if( !@((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $_DVWA[ 'db_database' ])) ) {
	dvwaMessagePush( 'Could not connect to database.' );
	dvwaPageReload();
}

$create_tb = "CREATE TABLE users (user_id int(6),first_name varchar(15),last_name varchar(15), user varchar(15), password varchar(32),avatar varchar(70), last_login TIMESTAMP, failed_login INT(3), PRIMARY KEY (user_id));";
if( !mysqli_query($GLOBALS["___mysqli_ston"],  $create_tb ) ) {
	dvwaMessagePush( "Table could not be created<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
	dvwaPageReload();
}
dvwaMessagePush( "'users' table was created." );


// Insert some data into users
$base_dir= str_replace ("setup.php", "", $_SERVER['SCRIPT_NAME']);
$avatarUrl  = $base_dir . 'hackable/users/';

$insert = "INSERT INTO users VALUES
	('1','admin','admin','admin',MD5('password'),'{$avatarUrl}admin.jpg', NOW(), '0'),
	('2','Gordon','Brown','gordonb',MD5('abc123'),'{$avatarUrl}gordonb.jpg', NOW(), '0'),
	('3','Hack','Me','1337',MD5('charley'),'{$avatarUrl}1337.jpg', NOW(), '0'),
	('4','Pablo','Picasso','pablo',MD5('letmein'),'{$avatarUrl}pablo.jpg', NOW(), '0'),
	('5','Bob','Smith','smithy',MD5('password'),'{$avatarUrl}smithy.jpg', NOW(), '0');";
if( !mysqli_query($GLOBALS["___mysqli_ston"],  $insert ) ) {
	dvwaMessagePush( "Data could not be inserted into 'users' table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
	dvwaPageReload();
}
dvwaMessagePush( "Data inserted into 'users' table." );

// Add role column to users table
$alter_users = "ALTER TABLE users ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'user';";
if( !mysqli_query($GLOBALS["___mysqli_ston"], $alter_users) ) {
    dvwaMessagePush( "Could not add role column to users table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "Added role column to users table." );

// Set admin user role
$update_admin = "UPDATE users SET role = 'admin' WHERE user = 'admin';";
if( !mysqli_query($GLOBALS["___mysqli_ston"], $update_admin) ) {
    dvwaMessagePush( "Could not set admin role<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "Updated admin user role." );

// Create access_log table
$create_access_log = "CREATE TABLE access_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    target_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    timestamp DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (target_id) REFERENCES users(user_id)
) ENGINE=InnoDB;";

if( !mysqli_query($GLOBALS["___mysqli_ston"], $create_access_log) ) {
    dvwaMessagePush( "Could not create access_log table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "'access_log' table was created." );

// Create security_log table
$create_security_log = "CREATE TABLE security_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    target_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    timestamp DATETIME NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (target_id) REFERENCES users(user_id)
) ENGINE=InnoDB;";

if( !mysqli_query($GLOBALS["___mysqli_ston"], $create_security_log) ) {
    dvwaMessagePush( "Could not create security_log table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "'security_log' table was created." );

// Create guestbook table
$create_tb_guestbook = "CREATE TABLE guestbook (comment_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT, comment varchar(300), name varchar(100), PRIMARY KEY (comment_id));";
if( !mysqli_query($GLOBALS["___mysqli_ston"], $create_tb_guestbook) ) {
    dvwaMessagePush( "Table could not be created<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "'guestbook' table was created." );

// Insert data into 'guestbook'
$insert = "INSERT INTO guestbook VALUES ('1','This is a test comment.','test');";
if( !mysqli_query($GLOBALS["___mysqli_ston"], $insert) ) {
    dvwaMessagePush( "Data could not be inserted into 'guestbook' table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "Data inserted into 'guestbook' table." );

// Copy .bak for a fun directory listing vuln
$conf = DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php';
$bakconf = DVWA_WEB_PAGE_TO_ROOT . 'config/config.inc.php.bak';
if (file_exists($conf)) {
    // Who cares if it fails. Suppress.
    @copy($conf, $bakconf);
}

dvwaMessagePush( "Backup file /config/config.inc.php.bak automatically created" );

// Add account_enabled columns to users table
$alter_users_dept = "ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS account_enabled TINYINT(1) DEFAULT 1;";
if( !mysqli_query($GLOBALS["___mysqli_ston"], $alter_users_dept) ) {
    dvwaMessagePush( "Could not add account_enabled column to users table<br />SQL: " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) );
    dvwaPageReload();
}
dvwaMessagePush( "Added account_enabled columns to users table." );

// Done
dvwaMessagePush( "<em>Setup successful</em>!" );

if( !dvwaIsLoggedIn())
    dvwaMessagePush( "Please <a href='login.php'>login</a>.<script>setTimeout(function(){window.location.href='login.php'},5000);</script>" );
dvwaPageReload();

?>
