<?php
require_once('header.php');
echo '<div id="page">
		<div id="content">';
if (!isset($_GET['key'])){
    echo '<script>window.location="enterkey.php"</script>"</script>';
} else {
    // Connect to db
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
    // Mark this feedback form as viewed
    $query = "UPDATE tbl_reviewers SET viewed=1 WHERE rev_key='{$_GET['key']}'";
    $mysqli->query($query);
    
    // Get all of the questions and store them in an array
    $questions_array = array();
    $query = "SELECT name, id FROM tbl_questions";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $id = $row['id'];
        $questions_array[$id] = $row['name'];
    }
    
    // Get all of the definitions and store them in an array
    $definition_array = array();
    $query = "SELECT name, definition FROM tbl_questions";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $name = $row['name'];
        $definition_array[$name] = $row['definition'];
    }
    
    // Randomly sort the questions to encourage actually reading them
    $questions_array = shuffle_assoc($questions_array);
    
    
    // Get the reviewer id
    $query = "SELECT tbl_reviewers.id as id, tbl_reviewers.self as self, tbl_sessions.employee as employee
        FROM tbl_reviewers, tbl_sessions WHERE rev_key='{$_GET['key']}' AND tbl_reviewers.session=tbl_sessions.id";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $revid = $row['id'];
        if ($row['self'] == 1){
            $is_self = 1;
            $employee = 'yourself';
        } else {
            $employee = $row['employee'];
            $is_self = 0;
        }
    }
    
    // Get all the questions, if any, that have been answered
    $answers = array();
    $query = "SELECT question, answer FROM tbl_answers WHERE reviewer=" . $revid;
    $result = $mysqli->query($query);
    if ($result->num_rows > 0){
        while ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $ansid = $row['question'];
            $answers[$ansid] = $row['answer'];
        }
    }
    
    // handle form submission if applicable
    if (isset($_POST['submit'])){
        // If done is checked, update the table as so
        if ($_POST['done'] == 1){
            // Make sure all questions are answered
            $donedone = true;
            foreach($questions_array as $k => $v){
                if (!isset($_POST['answer'][$k])){
                    $donedone = false;
                    $needtoenter[$k] = 'yep';
                }
            }
            
            if ($donedone){
                $query = "UPDATE tbl_reviewers SET done=1 WHERE id=" . $revid;
                $mysqli->query($query);
            }
        }
        $allgood = true;
        foreach($_POST['answer'] as $k => $v){
            if ($v != ''){
                // See if the answer exists in the db
                if (isset($answers[$k])){
                    $query = "UPDATE tbl_answers SET answer=" . $v . " WHERE reviewer=" . $revid . " AND question="
                        . $k;
                    if (!$mysqli->query($query))
                            $allgood = false;
                } else {
                    $query = "INSERT INTO tbl_answers (id, reviewer, question, answer) VALUES (0," . $revid
                        . ", " . $k . ", " . $v . ")";
                    if (!$mysqli->query($query))
                            $allgood = false;
                }
            }
        }
        
        // Handle keep/stop/start stuff
        // First blow out all the existing ones so they can be overwritten
        $query = "DELETE FROM tbl_keep_stop_start WHERE reviewer=" . $revid;
        $mysqli->query($query);
        
        // Now add all of them back in
        foreach ($_POST['keeps'] as $k => $v){
            if ($v != ''){
                $x = $mysqli->real_escape_string($v);
                $query = "INSERT INTO tbl_keep_stop_start (id, reviewer, data, kss_type) VALUES
                    (0, " . $revid . ", '" . $x . "', 1)";
                $mysqli->query($query);
            }
        }
        foreach ($_POST['stops'] as $k => $v){
            if ($v != ''){
                $x = $mysqli->real_escape_string($v);
                $query = "INSERT INTO tbl_keep_stop_start (id, reviewer, data, kss_type) VALUES
                    (0, " . $revid . ", '" . $x . "', 2)";
                $mysqli->query($query);
            }
        }
        foreach ($_POST['starts'] as $k => $v){
            if ($v != ''){
                $x = $mysqli->real_escape_string($v);
                $query = "INSERT INTO tbl_keep_stop_start (id, reviewer, data, kss_type) VALUES
                    (0, " . $revid . ", '" . $x . "', 3)";
                $mysqli->query($query);
            }
        }
        
        // Print results of queries
        echo '<div class="post">
                <h2 class="title"><a href="#">Submitted</a></h2>
                <div class="entry">';
        if ($allgood && !isset($needtoenter)){
            echo '<p>Saved';
            if ($donedone == true)
                echo ' as complete.  You can no longer edit your feedback';
            echo '.</p>';
        } elseif (isset($needtoenter)) {
            echo '<p id="red">You left some answers blank.  Please complete the form before saving as complete.</p>';
        } else {    
            echo '<p id="red">There were issues saving your feedback.  Please contact ' . ADMIN_EMAIL . ' if
                you continue to experience issues.  ' . $mysqli->error . '</p>';
        }
        echo '</div></div>';
        
        // Refresh the $answers array.
        unset ($answers);
        $answers = array();
        $query = "SELECT question, answer FROM tbl_answers WHERE reviewer=" . $revid;
        $result = $mysqli->query($query);
        if ($result->num_rows > 0){
            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                $ansid = $row['question'];
                $answers[$ansid] = $row['answer'];
            }
        }
    }
    
    // See if the feedback is done
    $query = "SELECT done FROM tbl_reviewers WHERE id=" . $revid;
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $isdone = $row['done'];
    }
    
    
?>
                        <div class="post">
				<h2 class="title"><a href="#">Feedback for <?php echo $employee; ?></a></h2>
				<div class="entry">
<?php
    if ($isdone == 0){
        echo '<form name="feedback" action="review.php?key=' . $_GET['key'] . '" method="POST">';
    } elseif ($is_self == 1){
        echo '<script>window.location="employee.php?key=' . $_GET['key'] . '"</script>';
    }
    
    // Display the questions in a table
    echo '<p>For each of the words or phrases below, please rate on a scale of 1 to 10, with 1 being "Never Describes" 
        and 10 being "Always Describes", how well each item describes ' . $employee .
        '.&nbsp;&nbsp;<font style="font-size: 12px; font-style: italic;">(hint: hover over the terms to see a definition.)</font></p>
        <table border="0" cellpadding="2"><tr><td>&nbsp;</td><td>';
    if ($isdone == 0){
        echo '<table border="0" width="100%">
        <tr><td align="center" width="20%">Never</td><td align="center" width="20%" style="border-left: solid">Seldom</td>
        <td align="center" width="20%" style="border-left: solid">Some-times</td>
        <td align="center" width="20%" style="border-left: solid">Often</td>
        <td align="center" width="20%"style="border-left: solid">Always</td></td></tr></table>';
    }
    
    // Loop through the array of questions
    foreach ($questions_array as $k => $v){
        echo '<tr><td valign="middle"><span class="tooltip" data-tooltip="' . $definition_array[$v] . '">';
        if ($needtoenter[$k] == 'yep'){
            echo '<p id="red">' . $v . '</p>';
        } else {
            echo $v;
        }
        echo '</span></td><td><table border="0" width="375px"><tr><td>';
        if ($isdone == 0){
            echo '<table border="0" width="100%"><tr>';
            for ($x = 1; $x<=10; $x++){
                echo '<td align="center">' . $x . '</td>';
            }
            echo '</tr><tr>';
            for ($x = 1; $x<=10; $x++){
                echo '<td align="center"><input type="radio" name="answer[' . $k . ']" value="' . $x . '" ';
                if ($x == $answers[$k]){
                    echo 'checked ';
                }
                echo '/></td>';
            }
            echo '</tr></table>';
            if ($needtoenter[$k] == 'yep'){
                echo '</td><td><p id="red">Please Answer</p>';
            }
        } else {
            echo '<img src="images/bar' . $answers[$k] . '.png" />';
        }
        echo '</td></tr></table></td></tr>';
    }
    echo '</table>';
    if ($is_self == 0){
        // Do the keep, stop, start stuff
        $keeps = array();
        $stops = array();
        $starts = array();
        $query = "SELECT   tbl_keep_stop_start.reviewer, tbl_keep_stop_start.data,  tbl_keep_stop_start.kss_type,
            tbl_reviewers.id FROM  tbl_keep_stop_start, tbl_reviewers WHERE tbl_keep_stop_start.reviewer=tbl_reviewers.id 
            AND tbl_reviewers.id=" . $revid;
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
        
        echo '<hr />';
        
        if ($isdone == 0){
        //Explain the keep/stop/start idea
        echo '<br /><p>In this section, write things that ' . $employee . ' should keep doing, stop doing, and start doing
            in the appropriate boxes.  Feel free to write keep/stop/starts for any behavior, action, activity, etc. that
            you think will help ' . $employee . '.  Be sure to avoid writing comments that could potentially identify
            you, as these comments will be viewable verbatim.';
        }
        
        $num_starts = count($starts);
        $num_stops = count($stops);
        $num_keeps = count($keeps);
?>
<script type="text/javascript">
var keepitems=<?php echo $num_keeps;?>;
function AddKeepItem() {
  div=document.getElementById("keepitems");
  keepitems++;
  newitem="<div id=\"keepitems["+keepitems+"]\">";
  newitem+="<textarea name=\"keeps[" + keepitems + "]\" cols=\"70\" rows=\"1\"";
  newitem+=" id=\"keeps[" + keepitems;
  newitem+="]\"></textarea>";
  newitem+="<img src=\"images/minus.png\" onClick=\"DelKeepItem("+keepitems+")\" id=\"keepdel"+keepitems+"\" /></div>";
  newnode=document.createElement("div");
  newnode.innerHTML=newitem;
  newnode.id="keepitems"+keepitems;
  div.appendChild(newnode);
}
function DelKeepItem(delNum) {
  keepitems--;
  theID=document.getElementById("keepitems["+delNum+"]");
  theID.parentNode.removeChild(theID);
}
</script>                                    

<script type="text/javascript">
var stopitems=<?php echo $num_stops;?>;
function AddStopItem() {
  div=document.getElementById("stopitems");
  stopitems++;
  newitem="<div id=\"stopitems["+stopitems+"]\">";
  newitem+="<textarea name=\"stops[" + stopitems + "]\" cols=\"70\" rows=\"1\"";
  newitem+=" id=\"stops[" + stopitems;
  newitem+="]\"></textarea>";
  newitem+="<img src=\"images/minus.png\" onClick=\"DelStopItem("+stopitems+")\" id=\"stopdel"+stopitems+"\" /></div>";
  newnode=document.createElement("div");
  newnode.innerHTML=newitem;
  newnode.id="stopitems"+stopitems;
  div.appendChild(newnode);
}
function DelStopItem(delNum) {
  stopitems--;
  theID=document.getElementById("stopitems["+delNum+"]");
  theID.parentNode.removeChild(theID);
}
</script>                                    

<script type="text/javascript">
var startitems=<?php echo $num_starts;?>;
function AddStartItem() {
  div=document.getElementById("startitems");
  startitems++;
  newitem="<div id=\"startitems["+startitems+"]\">";
  newitem+="<textarea name=\"starts[" + startitems + "]\" cols=\"70\" rows=\"1\"";
  newitem+=" id=\"starts[" + startitems;
  newitem+="]\"></textarea>";
  newitem+="<img src=\"images/minus.png\" onClick=\"DelStartItem("+startitems+")\" id=\"startdel"+startitems+"\" /></div>";
  newnode=document.createElement("div");
  newnode.innerHTML=newitem;
  newnode.id="startitems"+startitems;
  div.appendChild(newnode);
}
function DelStartItem(delNum) {
  startitems--;
  theID=document.getElementById("startitems["+delNum+"]");
  theID.parentNode.removeChild(theID);
}
</script>                                    

<?php
        //keeps
        echo '<p><strong>' . $employee . ' should keep...</strong>';
        if ($isdone == 0){
            echo '<img src="images/plus.png" onClick="AddKeepItem()" id="addkeep1" alt="Add More" />';
        }
        echo '<br />';
        if ($isdone == 0){
            echo '<div id="keepitems">
                <textarea name="keeps[0]" cols="70" rows="1">';
        } elseif ($num_keeps > 0) {
            echo '<ul><li>';
        }
        if ($num_keeps > 0){
            echo $keeps[0];
        }
        if ($isdone == 0){
            echo '</textarea><br />';
        } elseif ($num_keeps > 0) {
            echo '</li>';
        }
        foreach ($keeps as $k => $v){
            if ($k != 0){
                if ($isdone == 0){
                    echo '<div id="keepitems[' . $k . ']">
                        <textarea name="keeps[' . $k . ']" cols="70" rows="1">';
                } else {
                    echo '<li>';
                }
                echo $v;
                if ($isdone == 0){
                    echo '</textarea><img src="images/minus.png" onClick="DelKeepItem(' . $k . ')" id="delkeep' . $k . 
                    '" /></div>';
                } else {
                    echo '</li>';
                }
            }
        }
        if ($isdone == 0){
            echo '</div>';
        } elseif ($num_keeps > 0) {
            echo '</ul>';
        }
        echo '</p>';
        
        //stops
        echo '<p><strong>' . $employee . ' should stop...</strong>';
        if ($isdone == 0){
            echo '<img src="images/plus.png" onClick="AddStopItem()" id="addstop1" alt="Add More" />';
        }
        echo '<br />';
        if ($isdone == 0){
            echo '<div id="stopitems">
                <textarea name="stops[0]" cols="70" rows="1">';
        } elseif ($num_stops > 0) {
            echo '<ul><li>';
        }
        if ($num_stops > 0){
            echo $stops[0];
        }
        if ($isdone == 0){
            echo '</textarea><br />';
        } elseif ($num_stops > 0) {
            echo '</li>';
        }
        foreach ($stops as $k => $v){
            if ($k != 0){
                if ($isdone == 0){
                    echo '<div id="stopitems[' . $k . ']">
                        <textarea name="stops[' . $k . ']" cols="70" rows="1">';
                } else {
                    echo '<li>';
                }
                echo $v;
                if ($isdone == 0){
                    echo '</textarea><img src="images/minus.png" onClick="DelStopItem(' . $k . ')" id="delstop' . $k . 
                    '" /></div>';
                } else {
                    echo '</li>';
                }
            }
        }
        if ($isdone == 0){
            echo '</div>';
        } elseif ($num_stops > 0) {
            echo '</ul>';
        }
        echo '</p>';
        
        //starts
        echo '<p><strong>' . $employee . ' should start...</strong>';
        if ($isdone == 0){
            echo '<img src="images/plus.png" onClick="AddStartItem()" id="addstart1" alt="Add More" />';
        }
        echo '<br />';
        if ($isdone == 0){
            echo '<div id="startitems">
                <textarea name="starts[0]" cols="70" rows="1">';
        } elseif ($num_starts > 0) {
            echo '<ul><li>';
        }
        if ($num_starts > 0){
            echo $starts[0];
        }
        if ($isdone == 0){
            echo '</textarea><br />';
        } elseif ($num_starts > 0) {
            echo '</li>';
        }
        foreach ($starts as $k => $v){
            if ($k != 0){
                if ($isdone == 0){
                    echo '<div id="startitems[' . $k . ']">
                        <textarea name="starts[' . $k . ']" cols="70" rows="1">';
                } else {
                    echo '<li>';
                }
                echo $v;
                if ($isdone == 0){
                    echo '</textarea><img src="images/minus.png" onClick="DelStartItem(' . $k . ')" id="delstart' . $k . 
                    '" /></div>';
                } else {
                    echo '</li>';
                }
            }
        }
        if ($isdone == 0){
            echo '</div>';
        } elseif ($num_starts > 0) {
            echo '</ul>';
        }
        echo '</p>';
        
    }
    if ($isdone == 0){
        echo '<p>Save As Complete?<input type="checkbox" name="done" value="1" onclick="toggle();" />
            <input type="submit" name="submit" value="Save" /></p></form>';
    }
?>
                                </div>
			</div>
<?php
}
echo '</div>';
require_once('footer.php');
?>
