<?php

/*

This file contains all of the code to setup the initial PostgreSQL database. (setup.php)

*/

// Connect to server
if( !@pg_connect("host={$_DVWA[ 'db_server' ]} port={$_DVWA[ 'db_port' ]} user={$_DVWA[ 'db_user' ]} password={$_DVWA[ 'db_password' ]}") ) {
	dvwaMessagePush( "Could not connect to the database.<br/>Please check the config file." );
	dvwaPageReload();
}

// Create database
$drop_db = "DROP DATABASE IF EXISTS {$_DVWA[ 'db_database' ]};";

if( !@pg_query($drop_db) ) {
	dvwaMessagePush( "Could not drop existing database<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

$create_db = "CREATE DATABASE {$_DVWA[ 'db_database' ]};";

if( !@pg_query ( $create_db ) ) {
	dvwaMessagePush( "Could not create database<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

dvwaMessagePush( "Database has been created." );


// Connect to server AND connect to the database
$dbconn = @pg_connect("host={$_DVWA[ 'db_server' ]} port={$_DVWA[ 'db_port' ]} dbname={$_DVWA[ 'db_database' ]} user={$_DVWA[ 'db_user' ]} password={$_DVWA[ 'db_password' ]}");


// Create table 'users'

$drop_table = "DROP TABLE IF EXISTS users;";

if( !pg_query($drop_table) ) {
	dvwaMessagePush( "Could not drop existing users table<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

$create_tb = "CREATE TABLE users (user_id integer UNIQUE, first_name text, last_name text, username text, password text, avatar text, PRIMARY KEY (user_id));";

if( !pg_query( $create_tb ) ) {
	dvwaMessagePush( "Table could not be created<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

dvwaMessagePush( "'users' table was created." );

// Get the base directory for the avatar media...
$baseUrl = 'http://'.$_SERVER[ 'SERVER_NAME' ].$_SERVER[ 'PHP_SELF' ];
$stripPos = strpos( $baseUrl, 'dvwa/setup.php' );
$baseUrl = substr( $baseUrl, 0, $stripPos ).'dvwa/hackable/users/';

$insert = "INSERT INTO users VALUES
	('1','admin','admin','admin',MD5('password'),'{$baseUrl}admin.jpg'),
	('2','Gordon','Brown','gordonb',MD5('abc123'),'{$baseUrl}gordonb.jpg'),
	('3','Hack','Me','1337',MD5('charley'),'{$baseUrl}1337.jpg'),
	('4','Pablo','Picasso','pablo',MD5('letmein'),'{$baseUrl}pablo.jpg'),
	('5','bob','smith','smithy',MD5('password'),'{$baseUrl}smithy.jpg');";
if( !pg_query( $insert ) ) {
	dvwaMessagePush( "Data could not be inserted into 'users' table<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

dvwaMessagePush( "Data inserted into 'users' table." );

// Create guestbook table

$drop_table = "DROP table IF EXISTS guestbook;";

if( !@pg_query($drop_table) ) {
	dvwaMessagePush( "Could not drop existing users table<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

$create_tb_guestbook = "CREATE TABLE guestbook (comment text, name text, comment_id SERIAL PRIMARY KEY);";

if( !pg_query( $create_tb_guestbook ) ) {
	dvwaMessagePush( "guestbook table could not be created<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}

dvwaMessagePush( "'guestbook' table was created." );

// Insert data into 'guestbook'
$insert = "INSERT INTO guestbook (comment, name) VALUES('This is a test comment.','admin')";

if( !pg_query( $insert ) ) {
	dvwaMessagePush( "Data could not be inserted into 'guestbook' table<br />SQL: " . pg_last_error() );
	dvwaPageReload();
}
dvwaMessagePush( "Data inserted into 'guestbook' table." );

dvwaMessagePush( "Setup successful!" );
dvwaPageReload();

pg_close($dbconn);

?>
