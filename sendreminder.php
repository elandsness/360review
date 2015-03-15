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

require_once('header.php');

if (isset($_GET['r'])){
    $badkey = true;

    if ((strlen($_GET['key']) == 34) && ((strpos($_GET['key'], 'rR') == 0) || (strpos($_GET['key'], 'eE') == 0))){
        $badkey = false;
    }

    if ($badkey){
        echo '<script>window.location="enterkey.php"</script>';
    }
}

// Connect to the DB and get all the details
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

$query = "SELECT tbl_sessions.employee as emp, tbl_sessions.due_date, tbl_reviewers.email as email,
    tbl_reviewers.id as rev_id FROM tbl_sessions, tbl_reviewers WHERE tbl_reviewers.session=tbl_sessions.id AND
    tbl_reviewers.rev_key='{$_GET['r']}'";
    
$result = $mysqli->query($query);
while ($row = $result->fetch_array(MYSQLI_ASSOC)){
    $employee = $row['emp'];
    $emp_email = $row['email'];
    $due_date = date('m\/d\/Y', $row['due_date']);
    $rev_id = $row['rev_id'];
}

// Email to the reviewer
require_once "Mail.php";
$from = "Feedback Mailer <" . EMAIL_USER . ">";
$to = $emp_email;
$subject = '360 Degree Feedback for ' . $employee;
$body = '<html><body>Hello,<br />
    Please go to <a href="http://' . DOMAIN_NAME . '/enterkey.php?key=' . $_GET['r'] . '">
    http://' . DOMAIN_NAME . '/enterkey.php?key=' . $_GET['r'] . '</a> and complete the feedback for ' .
    $employee . '.  The deadline to provide feedback is ' . $due_date . '.  Please complete the feedback as soon as possible
    to ensure that ' . $employee . ' gets the feedback needed for personal development.
    <br /><br />If you have any questions at all, or if you experience
    issues with the application, contact ' . ADMIN_EMAIL . '.<br />Thank You!</body></html>';
$host = EMAIL_SERVER;
$username = EMAIL_USER;
$password = EMAIL_PASS;

$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject,
  'Content-Type' => 'text/html', 
  'MIME-Version' => '1.0');
$smtp = Mail::factory('smtp',
  array ('host' => $host,
        'auth' => true,
        'username' => $username,
        'password' => $password));

$mail = $smtp->send($to, $headers, $body);             

echo '<script>window.location="manager.php?key=' . $_GET['key'];

if (PEAR::isError($mail)) {
    echo '&re=' . $rev_id;
} else {
    echo '&r=' . $rev_id;
}

echo '"</script>';

?>
