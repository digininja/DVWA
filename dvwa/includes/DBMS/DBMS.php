<?php

/*

This file contains all of the database management code for DVWA.
All code related to database management should be kept in here.

*/

### MySQL ###
if ($DBMS == 'MySQL') {
 $DBMS = htmlspecialchars(strip_tags($DBMS));
 $DBMS_errorFunc = mysql_error();
 
 function escapeString( $var ) {
  $var = mysql_real_escape_string( $var );
  return $var;
 }
 
 function db_login( $user,$pass )  {
  $login = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";

	$result = @mysql_query($login) or die('<pre>' . mysql_error() . '</pre>' );

	if( $result && mysql_num_rows( $result ) == 1 ) {	// Login Successful...
		dvwaMessagePush( "You have logged in as '".$user."'" );
		dvwaLogin( $user );
		dvwaRedirect( 'index.php' );
		}
 }
}
### END MySQL ###

### PGSQL ###
elseif ($DBMS == 'PGSQL') {
 $DBMS = htmlspecialchars(strip_tags($DBMS));
 $DBMS_errorFunc = @pg_last_error();
 
 function escapeString( $var ) {
  $var = pg_escape_string( $var );
  return $var;
 }
 
 function db_login( $user,$pass ) {
    $login = "SELECT * FROM users WHERE username='$user' AND password='$pass';";
  
  $result = @pg_query( $login ) or die('<pre>' . pg_last_error() . '</pre>');
  
  if($result && pg_num_rows( $result ) == 1) {	// Login Successful...
   dvwaMessagePush( "You have logged in as '".$user."'" );
   dvwaLogin( $user );
   dvwaRedirect( 'index.php' );
  }
 }
}
### END PGSQL ###

### INVALID DBMS ###
else {
 $DBMS = "No DBMS selected.";
 $DBMS_errorFunc = '';
}
### END INVALID ###

$DBMS_connError = '<div align="center">
		<img src="'.DVWA_WEB_PAGE_TO_ROOT.'dvwa/images/logo.png">
		<pre>Unable to connect to the database.<br>'.$DBMS_errorFunc.'<br /><br /></pre>
		Click <a href="'.DVWA_WEB_PAGE_TO_ROOT.'setup.php">here</a> to setup the database.
		</div>';
		
function dvwaDatabaseConnect() {
	global $_DVWA;
	global $DBMS;
	global $DBMS_connError;

	if ($DBMS == 'MySQL') {
		if( !@mysql_connect( $_DVWA[ 'db_server' ], $_DVWA[ 'db_user' ], $_DVWA[ 'db_password' ] )
		|| !@mysql_select_db( $_DVWA[ 'db_database' ] ) ) {
			die( $DBMS_connError );
		}
	}
	
	elseif ($DBMS == 'PGSQL') {
		$dbconn = @pg_connect("host=".$_DVWA[ 'db_server' ]." port=".$_DVWA[ 'db_port' ]." dbname=".$_DVWA[ 'db_database' ]." user=".$_DVWA[ 'db_user' ]." password=".$_DVWA[ 'db_password' ]) 
		or die( $DBMS_connError );
	}
}

// -- END

?>