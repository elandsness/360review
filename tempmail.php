<?php
                    // Set up the email
                    require_once "Mail.php";
                    $from = "Feedback Mailer <" . EMAIL_USER . ">";
                    $to = $v;
                    $subject = '360 Degree Feedback for ' . $employee;
                    $body = '<html><body>Hello,<br /><br />You have been asked by ' . $employee .
                        ' to participate in a 360&deg; feedback session.  Using the link below, you can access
                        a brief survey that will ask you to rate ' . $employee . '.  The survey will present
                        you with a series of words and phrases which you will rate on a scale of 1-10 with
                        1 being not at all like ' . $employee . ' and 10 being describes ' . $employee .
                        ' exactly.  Please fill out this survey as soon as possible, as it is due no later than ' .
                        $due_date . '.  To begin the survey, use the following link:
                        <a href="http://' . DOMAIN_NAME . '/enterkey.php?key=' . $rev_key . '">
                        http://' . DOMAIN_NAME . '/enterkey.php?key=' . $rev_key . '</a><br />
                        <br />If you have any questions at all, or if you experience
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

                    if (PEAR::isError($mail)) {
                        // Email failed; log it.
                        $failed_send = true;
                        array_push($failed_send_array, $v);
                    } else {
                        // Email sent; add it to the DB
                        $query = "INSERT INTO tbl_reviewers (id, session, email, done, viewed, rev_key) VALUES
                            (0, " . $session_id . ", '" . $v . "', 0, 0, '" . $rev_key . "')";
                        // execute the query
                        $mysqli->query($query);
                    }
?>
