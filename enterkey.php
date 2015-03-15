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
$validcodes = explode(',', VALID_CODES);
if (isset($_GET['key'])){
    $badkey = true;
}
foreach ($validcodes as $k => $v){
    if ((strlen($_GET['key']) == (32 + strlen($v))) && (strpos($_GET['key'], $v) == 0)){
        $badkey = false;
    }
}

if (!isset($_GET['key']) || $badkey){
?>

	<div id="page">
		<div id="content">
			<div class="post">
				<h2 class="title"><a href="#">Enter Access Code</a></h2>
				<div class="entry">
                                    <form action="enterkey.php" method="GET">
                                        <p>
                                            Access Code: <input type="text" name="key" size="50" />
                                            <input type="submit" name="submit" id="submit" value="GO" />
                                        </p>
                                        <?php
                                            if ($badkey){
                                                echo '<p id="red">Please enter a <strong>VALID</strong> access code.</p>';
                                                    
                                            }
                                            echo '<p>If you do not know your access code, please visit the
                                                    <a href="lostkey.php">lost code page</a> to retrieve it.</p>';
                                        ?>
                                    </form>
                                </div>
			</div>
		</div>
<?php
} else {
    // Determine what this key is for and send the user to the right content.
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    // check to see if this is a manager code
    $query = "SELECT id FROM tbl_reviewers WHERE rev_key='" . $_GET['key'] . "'";
    if ($result = $mysqli->query($query)){
        if ($result->num_rows != 0){
                echo '<script>window.location="review.php?key=' . $_GET['key'] . '"</script>';
        } else {
            $query = "SELECT id FROM tbl_sessions WHERE emp_key='" . $_GET['key'] . "'";
            if ($result = $mysqli->query($query)){
                if ($result->num_rows != 0){
                        echo '<script>window.location="employee.php?key=' . $_GET['key'] . '"</script>';
                } else {
                    $query = "SELECT id FROM tbl_sessions WHERE mgr_key='" . $_GET['key'] . "'";
                    if ($result = $mysqli->query($query)){
                        if ($result->num_rows != 0){
                                echo '<script>window.location="manager.php?key=' . $_GET['key'] . '"</script>';
                        } else {
                            echo '<script>window.location="enterkey.php?key="</script>';
                        }
                    }
                }
            }
        }
    } else {
        echo $mysqli->error;
    }
}
require_once('footer.php');
?>
