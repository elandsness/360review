<?php
require_once('header.php');

if (isset($_GET['key'])){
    $badkey = true;

    if ((strlen($_GET['key']) == 34) && ((strpos($_GET['key'], 'eE') == 0) || (strpos($_GET['key'], 'rR') == 0))){
        $badkey = false;
    }

    if ($badkey){
        echo '<script>window.location="enterkey.php"</script>';
    }
}

echo '<div id="page">
		<div id="content">';
if (!isset($_GET['key'])){
    echo '<script>window.location="enterkey.php"</script>"</script>';
} else {
    // Connect to the DB
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
    // See if the employee has done their self appraisal.  If not, send them off to do it
    $query = "SELECT done FROM tbl_reviewers WHERE rev_key='" . $_GET['key'] . "'";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){   
        if ($row['done'] == 0){
            echo '<script>window.location="review.php?key=' . $_GET['key'] . '"</script>';
        }
    }
    
    if (isset($_POST['submit'])){
        // Parse the email addresses, add the database entries, and send out the emails
        // Get the feedback session data needed to add the reviewer entries in the DB and to send the emails
        $query = "SELECT employee, due_date, id FROM tbl_sessions WHERE emp_key='{$_GET['key']}'";
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
            echo '<p>All emails have been sent out.  Once the reviewers submit feedback, you can return
                to this page to view the results.  You can also return to this page at any time to
                invite more reviewers.</p>';
        }
        echo '</div></div>';
    }
?>
			<div class="post">
				<h2 class="title"><a href="#">Invite Reviewers</a></h2>
				<div class="entry">
                                    <form action="employee.php?key=<?php echo $_GET['key']; ?>" method="POST">
                                        <p>
                                           Enter email addresses separated by a comma to invite your colleagues to
                                           provide feedback.<br />
                                           <textarea cols="70" rows="10" name="emails"></textarea><br />
                                           <input type="submit" name="submit" value="Send Invites" />
                                        </p>
                                    </form>
                                </div>
			</div>
<?php
    // See if there's any feedback.  If there are more than 3 responders, show it.
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
    $query = "SELECT rev_id, question, answer FROM view_answers WHERE emp_key='" . $_GET['key'] . "' AND 
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
    $query = "SELECT question, answer FROM view_answers WHERE emp_key='" . $_GET['key'] . "' AND 
        done=1 AND self=1";
    $result = $mysqli->query($query);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $q_id = $row['question'];
            $self_answers[$q_id] = $row['answer'];
        }
    } else {
        $nofeedback = true;
    }
    
    if ($nofeedback){
        echo '<div class="post">
                <h2 class="title"><a href="#">No Feedback Yet</a></h2>
                <div class="entry">
                    <p>There is currently not enough feedback to display.  Once reviewers complete their feedback of you,
                    it will appear here in spreadsheet format and on a personality matrix.</p>
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
                    AND tbl_sessions.emp_key='" . $_GET['key'] . "'
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
                    AND tbl_sessions.emp_key='" . $_GET['key'] . "'
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
            tbl_keep_stop_start.reviewer=tbl_reviewers.id AND tbl_sessions.emp_key='" . $_GET['key'] . "'";
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
echo '</div>';
require_once('footer.php');
?>
