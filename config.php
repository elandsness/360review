<?php
/*
**    Copyright 2010-2014 Erik Landsness
**    This file is part of 360 Feedback.
**
**    360 Feedback is free software: you can redistribute it and/or modify
**    it under the terms of the GNU General Public License as published by
**    the Free Software Foundation, either version 3 of the License, or any later version.
**
**    360 Feedback is distributed in the hope that it will be useful,
**    but WITHOUT ANY WARRANTY; without even the implied warranty of
**    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**    GNU General Public License for more details.
**
**    You should have received a copy of the GNU General Public License
**    along with 360 Feedback.  If not, see <http://www.gnu.org/licenses/>.
*/

// defines database connection data and global constants
define('DB_HOST', 'localhost'); //MySQL database server
define('DB_USER', 'mysqluser'); //Username for MySQL database
define('DB_PASSWORD', 'supersecure'); //Password for MySQL user
define('DB_DATABASE', 'database'); //MySQL database name
define('VALID_CODES', 'eE,mM,rR'); //Don't change this!
define('DOMAIN_NAME', 'www.something.com'); //Website URL
define('COMPANY_NAME', 'My Super Great Company, Inc.'); //Name of company for branding
define('EMAIL_USER', 'email@something.com'); //Email user for sending messages.  Must be valid!
define('EMAIL_PASS', 'supersecure'); //Password for email user
define('EMAIL_SERVER', 'smtp.something.com'); //The email server for sending emails.
define('ADMIN_EMAIL', 'admin@something.com'); //Admin user email address
//ini_set('display_errors',1); //Uncomment to see error reporting for debugging
//error_reporting(E_ALL|E_STRICT); //Uncomment to see error reporting for debugging
?>
