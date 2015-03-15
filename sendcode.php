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

require_once ('header.php');
if (isset($_GET['id']) && (isset($_GET['m']) || isset($_GET['e']) || isset($_GET['r'])) && $_SESSION['prepage'] == 'lostkey.php'){
    // Connect to the DB
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    // Set up query based on the type set in the URL
    if (isset($_GET['e'])){
        $query = "SELECT emp_email as email, emp_key as thekey from tbl_sessions
            WHERE id='{$_GET['id']}'";
    } elseif (isset($_GET['m'])) {
        $query = "SELECT mgr_email as email, mgr_key as thekey from tbl_sessions
            WHERE id='{$_GET['id']}'";
    } else {
        $query = "SELECT email, rev_key as thekey from tbl_reviewers
            WHERE id='{$_GET['id']}'";
    }
    
    $result = $mysqli->query($query);
    
    // Send the email
    require_once "Mail.php";
    $from = "Feedback Mailer <" . EMAIL_USER . ">";
    while ($row=$result->fetch_array(MYSQLI_ASSOC)){
        $to = $row['email'];
        $subject = 'Feedback Access Code';
        $body = '<html><body>Hello,<br /><br />You have requested that an access code for <a href="http://' . DOMAIN_NAME . '">
                http://' . DOMAIN_NAME . '</a> be sent to you.  Your access code is:<br /><br />' .
                $row['thekey'] . '<br /><br />You can utilize this code by visiting <a href="http://' . DOMAIN_NAME . '">
                http://' . DOMAIN_NAME . '</a> and entering the code in the upper right hand corner of the site
                or by visiting <a href="http://' . DOMAIN_NAME . '/enterkey.php?key=' . $row['thekey'] . '">
                http://' . DOMAIN_NAME . '/enterkey.php?key=' . $row['thekey'] . '</a> directly in a browser.<br />
                <br />Thank You!</body></html>';
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
        
        if (PEAR::isError($mail)) {
            $sent = false;
        } else {
            $sent = true;
        }
        
    }
    $result->close();
    echo '<div id="page">
		<div id="content">
			<div class="post">';
    
    if ($sent == true){
        echo '<h2 class="title"><a href="#">Email Sent</a></h2>
            <div class="entry"><p>Your access code has been emailed to you.</p>';
    } else {
        echo '<h2 class="title"><a href="#">Email Not Sent</a></h2>
            <div class="entry"><p id="red">There was an error sedning your access code.</p>';
    }
    echo '                  </div>
                        </div>
                </div>';
} else {
    echo '<script>window.location="' . $_SESSION['prepage'] . '?error"</script>';
}
require_once ('footer.php');
?>
