<?php
require_once('header.php');

if (isset($_GET['key'])){
    $badkey = true;

    if ((strlen($_GET['key']) == 34) && (strpos($_GET['key'], 'mM') == 0)){
        $badkey = false;
    }

    if ($badkey){
        echo '<script>window.location="enterkey.php"</script>';
    }
}
//*********************Spinner Function******************
?>
<script type="text/javascript">
function showSpinner(){
        document.getElementById('spinner').style.display='inline';
        document.getElementById('page').style.opacity='0.15';
        document.getElementById('header').style.opacity='0.15';
        document.getElementById('footer').style.opacity='0.15';
}

function remindAllSpinner(){
	showSpinner();
	window.location='remindall.php?key=<?php echo $_GET['key']; ?>';
}
</script>

<div id="spinner" class="spinner" style="display:none;">
    <img id="img-spinner" src="images/spinner.gif" alt="Loading" />
</div>

<?php
//*********************Spinner Function******************

echo '<div id="page">
		<div id="content">';

// Connect to the database
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
if (isset($_POST['submit'])){
    // Turn the date into a timestamp
    $due_date = strtotime($_POST['due_date']);
    
    // Make sure the email addresses are valid
    if (validEmail($_POST['emp_email']) == true){
        $emp_email = $_POST['emp_email'];
    } else {
        $error['emp_email'] = true;
    }
    if (validEmail($_POST['mgr_email']) == true){
        $mgr_email = $_POST['mgr_email'];
    } else {
        $error['mgr_email'] = true;
    }

    // Make sure that the employee and manager emails are different
    if ($mgr_email == $emp_email){
        $error['dup_email'] = true;
    }
    
    // Sanitize the other fields
    $employee = $mysqli->real_escape_string($_POST['employee']);
    $manager = $mysqli->real_escape_string($_POST['manager']);
    
    // Create the unique keys
    $emp_key = 'eE' . md5("BarryZito" . microtime() . $employee);
    $mgr_key = 'mM' . md5("RichHarden" . microtime() . $manager);
    
    // Submit the form if there's no errors and then send the emails to the employee and manager
    if (!isset($error)){
        
        // Create the query to enter into the DB
        $query = "INSERT INTO tbl_sessions (id, employee, manager, emp_email, mgr_email, emp_key, mgr_key, due_date)
            VALUES (0, '" . $employee . "', '" . $manager . "', '" . $emp_email . "', '" . $mgr_email . "', '" .
            $emp_key . "', '" . $mgr_key . "', " . $due_date . ")";
        
        // execute the query
        $mysqli->query($query);
        echo '<div class="post">';
        if ($mysqli->affected_rows == 1){
            // Now add a record for the self-survey
            $query = "INSERT INTO tbl_reviewers (id, session, email, done, viewed, self, rev_key) VALUES
                (0, " . $mysqli->insert_id . ", '" . $emp_email . "', 0, 0, 1, '" . $emp_key . "')";
            $mysqli->query($query);
            
            echo '<h2 class="title"><a href="#">Session Created</a></h2>
                <div class="entry"><p>The session has been created';
        } else {
            echo '<h2 class="title"><a href="#">Session NOT Created</a></h2>
                <div class="entry"><p id="red">There was an issue creating the session.</p>';
                $error['submit'] = true;
        }
        
        // If the form submitted correctly, then send the emails out
        if (!isset($errors['submit'])){
            // Email to the employee
            require_once "Mail.php";
            $from = "Feedback Mailer <" . EMAIL_USER . ">";
            $to = $emp_email;
            $subject = '360 Degree Feedback';
            $body = '<html><body>Hello ' . $employee . ',<br /><br />You have been asked by ' . $manager .
                ' to participate in a 360&deg; feedback session.  Using the link below, you can take advantage
                of this opportunity to reach out to your colleagues, co-workers, managers, and employees to
                solicit anonymous feedback about the way that you interract with others.  You, and everyone that you
                invite to solicit feedback will be presented with a series of terms and phrases and will be
                asked to rate on a scale of 1 to 10 how realevant the word is when describing you.  This feedback
                will then be averaged and presented to you.  You\'ll be able to see how you view yourself, how
                others view you, and how the two differ.  ' . $manager . ' has asked that you complete this process
                no later than ' . date("m\/d\/Y", $due_date) . ', so please start right away to ensure
                you have enough time to get as much feedback as possible.  To begin inviting individuals to provide
                feedback or to view your existing feedback report, use the following link:
                <a href="http://' . DOMAIN_NAME . '/enterkey.php?key=' . $emp_key . '">
                http://' . DOMAIN_NAME . '/enterkey.php?key=' . $emp_key . '</a><br />
                <br />If you have any questions at all, please see ' . $manager . ', or if you experience
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
                $emp_email_good = false;
            } else {
                $emp_email_good = true;
            }

            
            // Email to the manager
            $from = "Feedback Mailer <" . EMAIL_USER . ">";
            $to = $mgr_email;
            $subject = '360 Degree Feedback for ' . $employee;
            $body = '<html><body>Hello ' . $manager . ',<br /><br />You have asked ' . $employee .
                ' to participate in a 360&deg; feedback session.  Using the following link, you can view
                the progress of the feedback session including who was asked to respond and if they have done so yet,
                as well as the feedback report itself:
                <a href="http://' . DOMAIN_NAME . '/enterkey.php?key=' . $mgr_key . '">
                http://' . DOMAIN_NAME . '/enterkey.php?key=' . $mgr_key . '</a><br />
                <br />If you have any questions, or if you experience
                issues with the application, contact ' . ADMIN_EMAIL . '.<br />Thank You!</body></html>';
            $host = "pop.gmail.com";
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
                $mgr_email_good = false;
            } else {
                $mgr_email_good = true;
            }
            
            if ($mgr_email_good && $emp_email_good){
                echo ' and the emails with the unique links for the employee and manager have been sent.';
            } else {
                echo ' but there was an issue sending out the emails containing the unique links to access the
                    feedback session.  You can use <a href="lostkey.php">the access code retrieval page</a>
                    to attempt to resend the emails.  If that does not work, please contact ' . ADMIN_EMAIL;
            }
            echo '</p>';
        }
        
        echo '</div></div>';
    }
    
    
} elseif (isset($_GET['key'])){
    // Get the emp_key
    $query = "SELECT emp_key FROM tbl_sessions WHERE mgr_key='" . $_GET['key'] . "'";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $emp_key = $row['emp_key'];
        }
    }
    // Get the employee's name
    $query = "SELECT employee FROM tbl_sessions WHERE mgr_key='" . $_GET['key'] . "'";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $emp_name = $row['employee'];
    }
    
    // Turn Get[r] into an array to handle multiple reminder email feature
    $remindersArray = explode(".", $_GET['r']);
    $reminderErrors = explode(".", $_GET['re']);
    
    // Show a list of people that have been asked for feedback and if they have viewed or completed it
    $query = "SELECT tbl_reviewers.id as rev_id, tbl_reviewers.rev_key as revkey, tbl_reviewers.email as email, tbl_reviewers.done as done,
        tbl_reviewers.viewed as viewed FROM tbl_sessions, tbl_reviewers WHERE tbl_reviewers.session=tbl_sessions.id AND
        tbl_sessions.emp_key='" . $emp_key . "'";
    $result = $mysqli->query($query);
    if ($result->num_rows == 0){
        echo '<div class="post">
                <h2 class="title"><a href="#">No Reviewers Invited</a></h2>
                <div class="entry">
                    <p id="red">Nobody has been invited to provide feedback for ' . $emp_name . ' yet.</p>
                </div></div>';
    } else {
        echo '<div class="post">
            <h2 class="title"><a href="#">Reviewers for ' . $emp_name . '</a></h2>
            <div class="entry">
                <p><button type="button" name="remindall" onclick="remindAllSpinner();">Remind All Reviewers</button> *WARNING: can be really slow</p><p>';
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            echo $row['email'] . ': ';
            if ($row['viewed'] == 1 && $row['done'] == 1){
                echo '<span id="green">Feedback submitted</span>';
            }
            if ($row['viewed'] == 1 && $row['done'] == 0){
                echo '<span id="red">Form viewed but feedback not yet submitted</span> | ';
                if (in_array($row['rev_id'], $remindersArray) || in_array($row['rev_id'], $reminderErrors)){
                    if (in_array($row['rev_id'], $reminderErrors)){
                        echo '<span id="red">ISSUE SENDING REMINDER!</span>';
                    } else {
                        echo '<span id="green">Reminder Sent</span>';
                    }
                } else {
                    echo '<a href="sendreminder.php?r=' . $row['revkey'] . '&key=' . $_GET['key'] . '" onclick="showSpinner();">SEND REMINDER</a>';
                }
            }
            if ($row['viewed'] == 0 && $row['done'] == 0){
                echo '<span id="red">Form not viewed and feedback not yet submitted</span> | ';
                if (in_array($row['rev_id'], $remindersArray) || in_array($row['rev_id'], $reminderErrors)){
                    if (in_array($row['rev_id'], $reminderErrors)){
                        echo '<span id="red">ISSUE SENDING REMINDER!</span>';
                    } else {
                        echo '<span id="green">Reminder Sent</span>';
                    }
                } else {
                    echo '<a href="sendreminder.php?r=' . $row['revkey'] . '&key=' . $_GET['key'] . '" onclick="showSpinner();">SEND REMINDER</a>';
                }
            }
            if ($row['viewed'] == 0 && $row['done'] == 1){
                echo '<span id="red">Something is wrong here...</span>';
            }
            echo '<br />';
        }
        echo '</p>
                    </div></div>';
    }
    
    
//******************Invite people to review the employee.  Added feature on 9/22/2011***************************
    if (isset($_POST['invite'])){
        // Parse the email addresses, add the database entries, and send out the emails
        // Get the feedback session data needed to add the reviewer entries in the DB and to send the emails
        $query = "SELECT employee, due_date, id FROM tbl_sessions WHERE mgr_key='{$_GET['key']}'";
        $result = $mysqli->query($query);
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $employee = $row['employee'];
            $session_id = $row['id'];
            $due_date = date("m\/d\/Y", $row['due_date']);
        }
        
        // Set variables to track if there are any invalid emails and any failed sends
        $failed_send = false;
        $bad_email = false;
        $dup_email = false;
        
        // Array to hold the bad emails and failed sends
        $failed_send_array = array();
        $bad_address_array = array();
        $dup_email_array = array();
        
        // Parse the email address submission and remove any spaces
        $emails = explode(',',$_POST['emails']);
        foreach ($emails as $k=>$v){
            $emails[$k] = trim($v);
        }
        
        // Loop through the emails, see if they are good.  If yes, send email.  If no, log and ignore.
        foreach ($emails as $k=>$v){
            // Is the email good
            if (validEmail($v)){
                // Yep, see if it's a duplicate email
                $query = "SELECT id FROM tbl_reviewers WHERE email='" . $v . "' AND session=" . $session_id;
                $result = $mysqli->query($query);
                if ($result->num_rows > 0){
                    // It's a duplicate, so throw an error
                    $dup_email = true;
                    array_push($dup_email_array, $v);
                } else {
                    // Not a duplicate, so continue
                    // Generate the key
                    $rev_key = 'rR' . md5('Trevor Cahill' . $v . microtime());

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
                    $host = "pop.gmail.com";
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
                }
            } else {
                // Nope.  Log and skip
                $bad_email = true;
                array_push($bad_address_array,$v);
            }
        }
        echo '<div class="post">
				<h2 class="title"><a href="#">';
        if ($bad_email || $failed_send || $dup_email){
            echo 'Issues Detected';
        } else {
            echo 'Emails Sent';
        }
        echo '</a></h2>
		<div class="entry">';
        
        // If there's any issues, report them
        if ($bad_email || $failed_send || $dup_email){
            // Print duplicate addresses
            if ($dup_email){
                echo '<p id="red">The following email addresses have already been invited to provide feedback:<br />';
                foreach ($dup_email_array as $k => $v){
                    echo $v . '<br />';
                }
                echo 'Please invite colleagues that have not yet been invited.</p>';
            }

            // Print bad addresses
            if ($bad_email){
                echo '<p id="red">The following email addresses are not valid:<br />';
                foreach ($bad_address_array as $k => $v){
                    echo $v . '<br />';
                }
                echo 'Please correct the addresses and resend the emails.</p>';
            }
           
            // Print failed sends
            if ($failed_send){
                echo '<p id="red">Emails to the following addresses failed:<br />';
                foreach ($failed_send_array as $k => $v){
                    echo $v . '<br />';
                }
                echo 'Please try to resend the emails or contact ' . ADMIN_EMAIL . ' if you continue to have issues.</p>';
            }
            echo '<p>All emails not listed above have been sent out correctly.';
            
        } else {
            echo '<p>All emails have been sent out.</p>';
        }
        echo '</div></div>';
    }
?>
			<div class="post">
				<h2 class="title"><a href="#">Invite Reviewers</a></h2>
				<div class="entry">
                                    <form action="manager.php?key=<?php echo $_GET['key']; ?>" method="POST" onsubmit="showSpinner();">
                                        <p>
                                           Enter email addresses separated by a comma to invite people to
                                           provide feedback.<br />
                                           <textarea cols="70" rows="10" name="emails"></textarea><br />
                                           <input type="submit" name="invite" value="Send Invites" />
                                        </p>
                                    </form>
                                </div>
			</div>
<?php
//*************************End invite people feature added on 9/22/2011******************************************
    
    // See if there's any feedback.  If there is, show it.
    // Get all the question names and put them in an array
    $query = "SELECT id, name FROM tbl_questions";
    $result = $mysqli->query($query);
    $questions = array();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $id = $row['id'];
        $questions[$id] = $row['name'];
    }
    // Grab all the answers for this session that are done and put them into an array
    $reviewers = array();
    $answers = array();
    $query = "SELECT rev_id, question, answer FROM view_answers WHERE emp_key='" . $emp_key . "' AND 
        done=1 AND self=0";
    $result = $mysqli->query($query);
    if ($result->num_rows > (2 * count($questions))){
        $nofeedback = false;
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $rev_id = $row['rev_id'];
            // Make an entry in the reviewers array
            $reviewers[$rev_id] = 1;
            $q_id = $row['question'];
            $answers[$rev_id][$q_id] = $row['answer'];
        }
    } else {
        $nofeedback = true;
    }
    
    // How many reviewers?
    $num_reviewers = count($reviewers);
    
    // Grab all the self feedback answers for this session and put them into an array
    $self_answers = array();
    $query = "SELECT question, answer FROM view_answers WHERE emp_key='" . $emp_key . "' AND 
        done=1 AND self=1";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $q_id = $row['question'];
            $self_answers[$q_id] = $row['answer'];
        }
    } else {
        $noselffeedback = true;
    }
    
    if ($noselffeedback){
        echo '<div class="post">
                <h2 class="title"><a href="#">Self Appraisal Not Completed</a></h2>
                <div class="entry">
                    <p>The employee has yet to fill out his/her self appraisal. This step must be completed
                    before any feedback can be displayed. Once the employee completes the self appraisal and
                    enough peer reviewers complete their feedback, the results
                    will appear here in spreadsheet format and on a personality matrix.</p>
                </div></div>';
    } elseif ($nofeedback){
        echo '<div class="post">
                <h2 class="title"><a href="#">Not Enough Feedback</a></h2>
                <div class="entry">
                    <p>There is currently not enough feedback to display the reports.  Once enough peer reviewers complete their feedback,
                    the results will appear here in spreadsheet format and on a personality matrix.</p><p>*Note: to preserve
                    anonymity, at least 3 reviewers must submit their feedback before displaying any results.</p>
                </div></div>';
    } else {
        // Get the average scores to draw the graph
        $query = "SELECT
                    tbl_sessions.emp_key,
                    AVG(tbl_answers.answer) AS avg_answer,
                    tbl_personalities.name AS personality
                  FROM
                    tbl_answers,
                    tbl_questions,
                    tbl_personalities,
                    tbl_sessions,
                    tbl_reviewers 
                  WHERE
                    tbl_reviewers.session=tbl_sessions.id
                    AND tbl_answers.reviewer=tbl_reviewers.id
                    AND tbl_answers.question=tbl_questions.id
                    AND tbl_questions.personality=tbl_personalities.id
                    AND tbl_reviewers.self=0
                    AND tbl_reviewers.done=1
                    AND tbl_sessions.emp_key='" . $emp_key . "'
                  GROUP BY
                    tbl_answers.question";
        $result = $mysqli->query($query);
        $avg_personality_scores = array();
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $personality = $row['personality'];
            $avg_personality_scores[$personality] += $row['avg_answer'];
        }
        if ($_SESSION['type'] == 'brab'){
            // Get the deltas to show on the graph
            $query = "SELECT
                    tbl_sessions.emp_key,
                    tbl_answers.answer AS answer,
                    tbl_personalities.name AS personality
                  FROM
                    tbl_answers,
                    tbl_questions,
                    tbl_personalities,
                    tbl_sessions,
                    tbl_reviewers 
                  WHERE
                    tbl_reviewers.session=tbl_sessions.id
                    AND tbl_answers.reviewer=tbl_reviewers.id
                    AND tbl_answers.question=tbl_questions.id
                    AND tbl_questions.personality=tbl_personalities.id
                    AND tbl_reviewers.self=1
                    AND tbl_sessions.emp_key='" . $emp_key . "'
                  GROUP BY
                    tbl_answers.question";
            $result = $mysqli->query($query);
            $employee_scores = array();
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $personality = $row['personality'];
                $employee_scores[$personality] += $row['answer'];
            }
            $deltas = array();
            foreach ($employee_scores as $k => $v){
                $deltas[$k] = $employee_scores[$k] - $avg_personality_scores[$k];
            }
            
            // Draw it
                $img_url = 'brabimg.php?' .
                    'en=' . number_format(round($avg_personality_scores['Entrepreneur'], 1), 1) .
                    '&co=' . number_format(round($avg_personality_scores['Competitor'], 1), 1) .
                    '&pr=' . number_format(round($avg_personality_scores['Producer'], 1), 1) .
                    '&st=' . number_format(round($avg_personality_scores['Stabilizer'], 1), 1) .
                    '&tb=' . number_format(round($avg_personality_scores['Team Builder'], 1), 1) .
                    '&cr=' . number_format(round($avg_personality_scores['Creator'], 1), 1) .
                    '&pe=' . number_format(round($avg_personality_scores['Performer'], 1), 1) .
                    '&at=' . number_format(round($avg_personality_scores['Attacker'], 1), 1) .
                    '&cm=' . number_format(round($avg_personality_scores['Commander'], 1), 1) .
                    '&av=' . number_format(round($avg_personality_scores['Avoider'], 1), 1) .
                    '&pl=' . number_format(round($avg_personality_scores['Pleaser'], 1), 1) .
                    '&dr=' . number_format(round($avg_personality_scores['Drifter'], 1), 1) .
                    '&den=' . number_format(round($deltas['Entrepreneur'], 1), 1) .
                    '&dco=' . number_format(round($deltas['Competitor'], 1), 1) .
                    '&dpr=' . number_format(round($deltas['Producer'], 1), 1) .
                    '&dst=' . number_format(round($deltas['Stabilizer'], 1), 1) .
                    '&dtb=' . number_format(round($deltas['Team Builder'], 1), 1) .
                    '&dcr=' . number_format(round($deltas['Creator'], 1), 1) .
                    '&dpe=' . number_format(round($deltas['Performer'], 1), 1) .
                    '&dat=' . number_format(round($deltas['Attacker'], 1), 1) .
                    '&dcm=' . number_format(round($deltas['Commander'], 1), 1) .
                    '&dav=' . number_format(round($deltas['Avoider'], 1), 1) .
                    '&dpl=' . number_format(round($deltas['Pleaser'], 1), 1) .
                    '&ddr=' . number_format(round($deltas['Drifter'], 1), 1);
            } else {
                $img_url = 'addlines.php?' .
                    'en=' . $avg_personality_scores['Entrepreneur'] .
                    '&co=' . $avg_personality_scores['Competitor'] .
                    '&pr=' . $avg_personality_scores['Producer'] .
                    '&st=' . $avg_personality_scores['Stabilizer'] .
                    '&tb=' . $avg_personality_scores['Team Builder'] .
                    '&cr=' . $avg_personality_scores['Creator'] .
                    '&pe=' . $avg_personality_scores['Performer'] .
                    '&at=' . $avg_personality_scores['Attacker'] .
                    '&cm=' . $avg_personality_scores['Commander'] .
                    '&av=' . $avg_personality_scores['Avoider'] .
                    '&pl=' . $avg_personality_scores['Pleaser'] .
                    '&dr=' . $avg_personality_scores['Drifter'];
            }
        // Draw the image
        echo '<div class="post">
                <h2 class="title"><a href="#">Personality Matrix</a></h2>
                <div class="entry"><p>';
        if ($_SESSION['type'] != 'brab'){
            echo '<a href="settype.php?key=' . $_GET['key'] . '&return=' . $path_parts['basename'] .
                '&type=brab">avg</a> | ';
        } else {
            echo 'AVG | ';
        }
        if ($_SESSION['type'] != 'combo'){
            echo '<a href="settype.php?key=' . $_GET['key'] . '&return=' . $path_parts['basename'] .
                '&type=combo">combo</a> | ';
        } else {
            echo 'COMBO | ';
        }
        if ($_SESSION['type'] != 'good'){
            echo '<a href="settype.php?key=' . $_GET['key'] . '&return=' . $path_parts['basename'] .
                '&type=good">achiever</a> | ';
        } else {
            echo 'ACHIEVER | ';
        }
        if ($_SESSION['type'] != 'bad'){
            echo '<a href="settype.php?key=' . $_GET['key'] . '&return=' . $path_parts['basename'] .
                '&type=bad">extreme</a>';
        } else {
            echo 'EXTREME';
        }
        echo '</p><p><img src="' . $img_url . '" width="720" /></p>
                </div></div>';
        
        // Mix up the reviewers
        $reviewers = shuffle_assoc ($reviewers);
        
        // Draw the spreadsheet
        echo '<div class="post">
                <h2 class="title"><a href="#">Feedback Results</a></h2>
                <div class="entry">
                    <div class="scrollable">
                        <table border="1" cellpadding="3"><tr><td></td><td align="center">SELF</td>';
        for ($x = 1; $x <= $num_reviewers; $x++){
            echo '<td>PEER ' . $x . '</td>';
        }
        echo '<td align="center">AVG</td><td>DELTA</td></tr>';
        $question_count = count($questions);
        for ($x = 1; $x <= $question_count; $x++){
            echo '<tr><td width="200px">' . $questions[$x] . '</td><td width="50px" align="center">' . 
            $self_answers[$x] . '</td>';
            $running_total = 0;
            foreach ($reviewers as $rev_id => $v){
                echo '<td width="50px" align="center">' . $answers[$rev_id][$x] . '</td>';
                $running_total += $answers[$rev_id][$x];
            }
            $temp_avg = $running_total / $num_reviewers;
            echo '<td width="50px" align="center">' . number_format($temp_avg, 1) . '</td><td>';
            $delta = $self_answers[$x] - $temp_avg;
            if ($delta > 0){
                echo '<span id="green">';
            } elseif ($delta < 0){
                echo '<span id="red">';
            } else {
                echo '<span>';
            }
            echo number_format($delta, 1) . '</span></td></tr>';
        }
        echo '</table>
                </div>
                    </div></div>';
        
        // Show the keep stop start feedback
        echo '<div class="post">
            <h2 class="title"><a href="#">Keep, Start, Stop Feedback</a></h2>
            <div class="entry">';
        
        $keeps = array();
        $stops = array();
        $starts = array();
        $query = "SELECT   tbl_sessions.id, tbl_keep_stop_start.reviewer, tbl_keep_stop_start.data,  
            tbl_keep_stop_start.kss_type, tbl_reviewers.id FROM  tbl_keep_stop_start, tbl_reviewers,
            tbl_sessions WHERE tbl_reviewers.session = tbl_sessions.id AND 
            tbl_keep_stop_start.reviewer=tbl_reviewers.id AND tbl_sessions.mgr_key='" . $_GET['key'] . "'";
        $result = $mysqli->query($query);
        if ($result->num_rows > 0){
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                switch ($row['kss_type']){
                    case '1':
                        array_push($keeps, $row['data']);
                    break;
                    case '2':
                        array_push($stops, $row['data']);
                    break;
                    case '3':
                        array_push($starts, $row['data']);
                    break;
                }
            }
        }
        
        // Mix up the feedback
        shuffle($keeps);
        shuffle($stops);
        shuffle($starts);
        
        echo '<p><strong>Keeps</strong><br />';
        if (count($keeps) == 0){
            echo 'None.';
        } else {
            echo '<ul>';
            foreach ($keeps as $k => $v){
                echo '<li>' . $v . '</li>';
            }
            echo '</ul>';
        }
        echo '</p><p><strong>Stops</strong><br />';
        if (count($stops) == 0){
            echo 'None.';
        } else {
            echo '<ul>';
            foreach ($stops as $k => $v){
                echo '<li>' . $v . '</li>';
            }
            echo '</ul>';
        }
        echo '</p><p><strong>Starts</strong><br />';
        if (count($starts) == 0){
            echo 'None.';
        } else {
            echo '<ul>';
            foreach ($starts as $k => $v){
                echo '<li>' . $v . '</li>';
            }
            echo '</ul>';
        }
        echo '</p></div></div>';
    }
} 

//No submit button or form data has issues
if (isset($error) or (!isset($_POST['submit']) && !isset($_GET['key']))){
    // Create some feedback
?>
			<div class="post">
				<h2 class="title"><a href="#">Create Feedback Session</a></h2>
				<div class="entry">
                                    <form action="manager.php" method="POST" onsubmit="showSpinner();">
                                        <?php
                                            if (isset($error['submit'])){
                                                echo '<p id="red">Please try submitting the form again or contact ' . ADMIN_EMAIL . '
                                                    if you continue to experience issues.</p>';
                                            }
					    if (isset($error['emp_email'])){
                                                echo '<p id="red">The employee email address supplied is not valid!</p>';
                                            }
					    if (isset($error['mgr_email'])){
                                                echo '<p id="red">The manager email address supplied is not valid!</p>';
                                            }
					    if (isset($error['dup_email'])){
                                                echo '<p id="red">The manager and employee email addresses cannot be the same!</p>';
                                            }
                                        ?>
                                        <p>
                                            Employee Name: <input type="text" name="employee" size="50" /><br />
                                            Employee's Email: <input type="text" name="emp_email" size="50" /><br />
                                            Manager Name: <input type="text" name="manager" size="50" /><br />
                                            Manager's Email: <input type="text" name="mgr_email" size="50" /><br />
                                            Feedback Due Date: <input name="due_date" id="datechooserex6" class="datechooser dc-dateformat='m/d/Y' dc-iconlink='datechooser.png' dc-alloweddays='1,2,3,4,5' dc-weekstartday='7' dc-startdate='<?php echo date("mdY");?>' dc-latestdate='05202050' dc-earliestdate='<?php echo date("mdY");?>' dc-onupdate='FunctionEx6'" type="text" value="" /><br />
                                            <input type="submit" name="submit" id="submit" value="GO" />
                                        </p>
                                    </form>
                                </div>
			</div>
<?php
}
echo '</div>';
require_once('footer.php');
?>
