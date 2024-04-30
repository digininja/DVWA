DAMN VULNERABLE WEB APPLICATION
=======================

v1.10 (*Not Yet Released)
======

+ Improved IIS support. (@g0tmi1k)
+ Improved setup system check. (@g0tmi1k)

v1.9 (2015-10-05)
======

+ Added a dedicated objective (or "flag") for file include. (@g0tmi1k)
+ Added a warning to any module that requires a certain configuration. (@g0tmi1k)
+ Added comments to all source code that would be visible via DVWA modules. (@g0tmi1k)
+ Added CSRF token to pre-auth forms (login/setup/security pages). (@g0tmi1k + @Shinkurt)
+ Added HttpOnly cookie flag on impossible levels. (@g0tmi1k)
+ Added more detail to the documentation. (@g0tmi1k)
+ Added PDO to all impossible levels requiring MySQL. (@g0tmi1k)
+ Added PHPIDS options into the config file. (@g0tmi1k)
+ Added system check to setup. (@g0tmi1k)
+ Added various information to all help pages for every module. (@g0tmi1k)
+ Changed brute force medium to be harder due to sleep. (@g0tmi1k)
+ Changed file include landing page + added 3x example pages. (@g0tmi1k)
+ Changed file include medium to be harder due to more filters. (@g0tmi1k)
+ Changed HTTP REFERER check for medium level CSRF. (@g0tmi1k)
+ Changed input box for medium level with SQLi + SQLi Blind. (@g0tmi1k)
+ Changed SQLi + SQLi Blind to be $_POST rather than $_GET. (@g0tmi1k)
+ Changed SQLi Blind to be a real example of the vulnerability. (@g0tmi1k)
+ Fixed brute force and file upload impossible levels, as they were vulnerable. (@g0tmi1k + @Shinkurt)
+ Fixed bug with file fnclude page not loading. (@g0tmi1k)
+ Fixed CAPTCHA bug to read URL parameters on impossible. (@g0tmi1k)
+ Fixed CAPTCHA bug where the form wouldn't be visible. (@g0tmi1k)
+ Fixed CAPTCHA bug where the URL parameters were not being used for low + medium. (@g0tmi1k)
+ Fixed CSRF medium level bug when not on localhost. (@g0tmi1k)
+ Fixed setup bug with custom URL path. (@g0tmi1k)
+ Removed PostgreSQL DB support. (@g0tmi1k)
+ Renamed 'Command Execution' to 'Command Injection'. (@g0tmi1k)
+ Renamed 'high' level to 'impossible' and created new vectors for 'high'. (@g0tmi1k)
+ Updated README and documentation. (@g0tmi1k)
+ Various code cleanups in the core PHP files + CSS. (@g0tmi1k)
+ Various setup improvements (e.g. redirection + limited menu links). (@g0tmi1k)

v1.8 (2013-05-01)
======

+ Versioning change: Version numbers now follow Major.Minor (e.g. v1.8) removing the middle digit.
+ Moved default security level setting to the config file.
+ Fixed a bug which prevented setup when a database name other than 'dvwa' was used.
+ Added a logic challenge involving an insecure CAPTCHA (requires external internet access)

v1.0.7 (2010-09-08)
======

+ Re-designed the login page + made some other slight cosmetic changes. 06/06/2010 (@ethicalhack3r)
+ Started PostgreSQL implementation. 15/03/2010 (@ethicalhack3r)
+ A few small cosmetic changes. 15/03/2010 (@ethicalhack3r)
+ Improved the help information and look. 15/03/2010 (@ethicalhack3r)
+ Fixed a few bugs thanks to @Digininja. 15/03/2010 (@ethicalhack3r)
+ Show logged in username. 05/02/2010 (Jason Jones)
+ Added new info on RandomStorm. 04/02/2010 (@ethicalhack3r)
+ Added 'SQL Injection (Blind)'. 04/02/2010 (@ethicalhack3r)
+ Added official documentation. 21/11/2009 (@ethicalhack3r)
+ Implemented view all source functionality. 16/10/2009 (tmacuk, craig, @ethicalhack3r)

v1.0.6 (2009-10-05)
======

+ Fixed a bug where the logo would not show on first time use. 03/09/2009 (@ethicalhack3r)
+ Removed 'current password' input box for low+med CSRF security. 03/09/2009 (@ethicalhack3r)
+ Added an article which was written for OWASP Turkey. 03/10/2009 (@ethicalhack3r)
+ Added more toubleshooting information. 02/10/2009 (@ethicalhack3r)
+ Stored XSS high now sanitises output. 02/10/2009 (@ethicalhack3r)
+ Fixed a 'bug' in XSS stored low which made it not vulnerable. 02/10/2009 (@ethicalhack3r)
+ Rewritten command execution high to use a whitelist. 30/09/09 (@ethicalhack3r)
+ Fixed a command execution vulnerability in exec high. 17/09/09 (@ethicalhack3r)
+ Added some troubleshooting info for PHP 5.2.6 in readme.txt. 17/09/09 (@ethicalhack3r)
+ Added the upload directory to the upload help. 17/09/09 (@ethicalhack3r)

v1.0.5 (2009-09-03)
======

+ Made IE friendly as much as possible. 30/08/2009 (@ethicalhack3r)
+ Removed the acunetix scan report. 30/08/2009 (@ethicalhack3r)
+ Added 'Clear Log' button to PHPIDS parser. 27/08/2009 (@ethicalhack3r)
+ Implemented PHPIDS log parser. 27/08/2009 (@ethicalhack3r)
+ Implemented Stored XSS vulnerability. 27/08/2009 (@ethicalhack3r)
+ Added htaccess rule for localhost access only. 22/08/2009 (@ethicalhack3r)
+ Added CSRF. 01/08/2009 (@ethicalhack3r)
+ Implemented sessions/login. 01/08/2009 (@ethicalhack3r)
+ Complete recode. (jamesr)
+ Complete redesign. (jamesr)
+ Delimited 'dvwa' in session- minimising the risk of clash with other projects running on localhost. 01/08/2009 (jamesr)
+ Integrated PHPIDS v0.6. 01/08/2009 (jamesr)
+ Streamlined login functionality. 01/08/2009 (jamesr)

v1.0.4 (2009-06-29)
======

+ Added acunetix scan report. 24/06/2009
+ All links use http://hiderefer.com to hide referrer header. 23/06/2009
+ Updated/added 'more info' links. 23/06/2009
+ Moved change log info to CHANGELOG.txt. 22/06/2009
+ Fixed the exec.php UTF-8 output. 16/06/2009
+ Moved Help/View source buttons to footer. 12/06/2009
+ Fixed phpInfo bug. 12/06/2009
+ Made dvwa IE friendly. 11/06/2009
+ Fixed html bugs. 11/06/2009
+ Added more info to about page. 03/06/2009
+ Added pictures for the users. 03/06/2009
+ Fixed typos on the welcome page. 03/06/2009
+ Improved README.txt and fixed typos. 03/06/2009
+ Made SQL injection possible in sqli_med.php. Thanks to Teodor Lupan. 03/06/2009

v1.0.3 (2009-05-25)
======

+ Changed XAMPP link in index.php. 25/05/2009
+ Set default security to low. 25/05/2009
+ Improved output in setup.php. 25/05/2009

v1.0.2 (2009-05-24)
======

+ Removed phpinfo on higher security levels. 24/05/2009
+ Moved all vulnerable code to /source/. 24/05/2009
+ Added viewsource. 24/05/2009

v1.0.1 (2009-05-24)
======

+ Implemented different security levels. 24/05/2009
+ Changed XSS from POST to GET. 22/05/2009
+ Some changes to CSS. 22/05/2009
+ Version number now in variable in header.php. 21/05/2009
+ Added about page. 21/05/2009
+ Updated login script to use database. 21/05/2009
+ Added admin user to database. 21/05/2009
+ Combined RFI + LFI to make 'File Inclusion'. 21/05/2009
+ More realism to Local File Inclusion. 21/05/2009
+ Better error output on upload script. 21/05/2009

v1.0 (2009-05-20)
====

+ Made command execution more realistic. 20/05/2009
+ Added help buttons. 20/05/2009
+ Added .htaccess file to turn magic quotes off. 20/05/2009
+ Improved database creation with setup.php. 19/05/2009
+ Amended installation instructions in README file. 19/05/2009
+ Added GNU GPL license. 19/05/2009
+ Added a robots.txt file with disallow all. 26/01/2009
+ Removed link to www.ethicalhacker.co.uk in footer. 26/01/2009
+ Added better error output on magic quotes. 26/01/2009


Links
=====

+ Homepage: http://www.dvwa.co.uk

_Created by the DVWA team._
