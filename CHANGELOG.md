DAMN VULNERABLE WEB APP
=======================

v1.0.8
======

Versioning change: Version numbers now follow Major.Minor (e.g. 1.8) removing the middle digit.  
Moved default security level setting to the config file.  
Fixed a bug which prevented setup when a database name other than 'dvwa' was used.  
Added a logic challenge involving an insecure CAPTCHA (requires external internet access)

v1.0.7
======

Re-designed the login page + made some other slight cosmetic changes. 06/06/2010 (ethicalhack3r)  
Started PostgreSQL implementation. 15/03/2010 (ethicalhack3r)  
A few small cosmetic changes. 15/03/2010 (ethicalhack3r)  
Improved the help information and look. 15/03/2010 (ethicalhack3r)  
Fixed a few bugs thanks to Digininja. 15/03/2010 (ethicalhack3r)  
Show logged in username. 05/02/2010 (Jason Jones)  
Added new info on RandomStorm. 04/02/2010 (ethicalhack3r)  
Added 'SQL Injection (Blind)'. 04/02/2010 (ethicalhack3r)  
Added official documentation. 21/11/2009 (ethicalhack3r)  
Implemented view all source functionality. 16/10/2009 (tmacuk, craig, ethicalhack3r)  

v1.0.6
======

Fixed a bug where the logo would not show on first time use. 03/09/2009 (ethicalhack3r)  
Removed 'current password' input box for low+med CSRF security. 03/09/2009 (ethicalhack3r)  
Added an article which was written for OWASP Turkey. 03/10/2009 (ethicalhack3r)  
Added more toubleshooting information. 02/10/2009 (ethicalhack3r)  
Stored XSS high now sanitises output. 02/10/2009 (ethicalhack3r)  
Fixed a 'bug' in XSS stored low which made it not vulnerable. 02/10/2009 (ethicalhack3r)  
Rewritten command execution high to use a whitelist. 30/09/09 (ethicalhack3r)  
Fixed a command execution vulnerability in exec high. 17/09/09 (ethicalhack3r)  
Added some troubleshooting info for PHP 5.2.6 in readme.txt. 17/09/09 (ethicalhack3r)  
Added the upload directory to the upload help. 17/09/09 (ethicalhack3r)  

v1.0.5
======

Made IE friendly as much as possible. 30/08/2009 (ethicalhack3r)  
Removed the acunetix scan report. 30/08/2009 (ethicalhack3r)  
Added 'Clear Log' button to PHPIDS parser. 27/08/2009 (ethicalhack3r)  
Implemented PHPIDS log parser. 27/08/2009 (ethicalhack3r)  
Implemented Stored XSS vulnerability. 27/08/2009 (ethicalhack3r)  
Added htaccess rule for localhost access only. 22/08/2009 (ethicalhack3r)  
Added CSRF. 01/08/2009 (ethicalhack3r)  
Implemented sessions/login. 01/08/2009 (ethicalhack3r)  
Complete recode. (jamesr)  
Complete redesign. (jamesr)  
Delimited 'dvwa' in session- minimising the risk of clash with other projects running on localhost. 01/08/2009 (jamesr)  
Integrated PHPIDS v0.6. 01/08/2009 (jamesr)  
Streamlined login functionality. 01/08/2009 (jamesr)

v1.0.4
======

Added acunetix scan report. 24/06/2009  
All links use http://hiderefer.com to hide referrer header. 23/06/2009  
Updated/added 'more info' links. 23/06/2009  
Moved change log info to CHANGELOG.txt. 22/06/2009  
Fixed the exec.php UTF-8 output. 16/06/2009  
Moved Help/View source buttons to footer. 12/06/2009  
Fixed phpInfo bug. 12/06/2009  
Made dvwa IE friendly. 11/06/2009  
Fixed html bugs. 11/06/2009  
Added more info to about page. 03/06/2009  
Added pictures for the users. 03/06/2009  
Fixed typos on the welcome page. 03/06/2009  
Improved README.txt and fixed typos. 03/06/2009  
Made SQL injection possible in sqli_med.php. Thanks to Teodor Lupan. 03/06/2009  

v1.0.3
======

Changed XAMPP link in index.php. 25/05/2009  
Set default security to low. 25/05/2009  
Improved output in setup.php. 25/05/2009  

v1.0.2
======

Removed phpinfo on higher security levels. 24/05/2009  
Moved all vulnerable code to /source/. 24/05/2009  
Added viewsource. 24/05/2009  

v1.0.1
======

Implemented different security levels. 24/05/2009  
Changed XSS from POST to GET. 22/05/2009  
Some changes to CSS. 22/05/2009  
Version number now in variable in header.php. 21/05/2009  
Added about page. 21/05/2009  
Updated login script to use database. 21/05/2009  
Added admin user to database. 21/05/2009  
Combined RFI + LFI to make 'File Inclusion'. 21/05/2009  
More realism to Local File Inclusion. 21/05/2009  
Better error output on upload script. 21/05/2009  

v1.0
====

Made command execution more realistic. 20/05/2009  
Added help buttons. 20/05/2009  
Added .htaccess file to turn magic quotes off. 20/05/2009  
Improved database creation with setup.php. 19/05/2009  
Amended installation instructions in README file. 19/05/2009  
Added GNU GPL license. 19/05/2009  
Added a robots.txt file with disallow all. 26/01/2009  
Removed link to www.ethicalhacker.co.uk in footer. 26/01/2009  
Added better error output on magic quotes. 26/01/2009  


Links
=====

Homepage: http://www.dvwa.co.uk

Project Home: https://github.com/RandomStorm/DVWA

*Created by the DVWA team*
