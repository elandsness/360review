<?php
// defines database connection data and global constants
define('DB_HOST', 'localhost'); //MySQL database server
define('DB_USER', 'mysqluser'); //Username for MySQL database
define('DB_PASSWORD', 'supersecure'); //Password for MySQL user
define('DB_DATABASE', 'database'); //MySQL database name
define('VALID_CODES', 'eE,mM,rR'); //Don't change this!
define('DOMAIN_NAME', 'www.something.com'); //Website URL
define('EMAIL_USER', 'email@something.com'); //Email user for sending messages.  Must be valid!
define('EMAIL_PASS', 'supersecure'); //Password for email user
define('EMAIL_SERVER', 'smtp.something.com'); //The email server for sending emails.
define('ADMIN_EMAIL', 'admin@something.com'); //Admin user email address
//ini_set('display_errors',1); //Uncomment to see error reporting for debugging
//error_reporting(E_ALL|E_STRICT); //Uncomment to see error reporting for debugging
?>
