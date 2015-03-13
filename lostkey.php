<?php
require_once('header.php');
$goodemail = true;
?>

<script type="text/javascript">
function showSpinner(){
        document.getElementById('spinner').style.display='inline';
        document.getElementById('page').style.opacity='0.15';
        document.getElementById('header').style.opacity='0.15';
        document.getElementById('footer').style.opacity='0.15';
}
</script>

<div id="spinner" class="spinner" style="display:none;">
    <img id="img-spinner" src="images/spinner.gif" alt="Loading" />
</div>


	<div id="page">
		<div id="content">
<?php
if (isset($_POST['submit'])){
    $goodemail = validEmail($_POST['email']); //Check to see if the email address is valid
    if ($goodemail){
        $noemail = true; //Set this bool to false until the email address is found in the db

        // Connect to the DB
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        // Display any supervisor sessions if applicable
        $query = "SELECT id, employee, due_date from tbl_sessions WHERE mgr_email='{$_POST['email']}' ORDER BY id DESC";
        $result = $mysqli->query($query);
        echo '<div class="post"><h2 class="title"><a href="#">Supervisor Access Codes</a></h2>
                                    <div class="entry">';
        if ($result->num_rows == 0){
            echo '<p>No supervisor access codes for this email address.</p>';
        } else {
            $noemail = false;
            echo '<p>';
            while ($row=$result->fetch_array(MYSQLI_ASSOC)){
                echo 'Review for ' . $row['employee'] . ' due on ' . date("m\/d\/Y", $row['due_date']) . ': <a href="sendcode.php?id=' .
                    $row['id'] . '&m=1" onclick="showSpinner();">Retrieve Code</a><br />';
            }
            echo '</p>';
        }
        echo '</div></div>';
        $result->close();

        // Display any employee sessions if applicable
        $query = "SELECT id, employee, due_date from tbl_sessions WHERE emp_email='{$_POST['email']}' ORDER BY id DESC";
        $result = $mysqli->query($query);
        echo '<div class="post"><h2 class="title"><a href="#">Employee Access Codes</a></h2>
                                    <div class="entry">';
        if ($result->num_rows == 0){
            echo '<p>No employee access codes for this email address.</p>';
        } else {
            $noemail = false;
            echo '<p>';
            while ($row=$result->fetch_array(MYSQLI_ASSOC)){
                echo 'Review for ' . $row['employee'] . ' due on ' . date("m\/d\/Y", $row['due_date']) . ': <a href="sendcode.php?id=' .
                    $row['id'] . '&e=1" onclick="showSpinner();">Retrieve Code</a><br />';
            }
            echo '</p>';
        }
        echo '</div></div>';
        $result->close();

        // Display any reviewer sessions if applicable
        $query = "SELECT tbl_reviewers.id as id, tbl_sessions.employee as emp, tbl_sessions.due_date as dd,
            tbl_reviewers.done as done from tbl_reviewers,tbl_sessions WHERE tbl_reviewers.session=tbl_sessions.id AND 
            tbl_reviewers.email='{$_POST['email']}' && tbl_reviewers.self='0' ORDER BY id DESC";
        $result = $mysqli->query($query);
        echo '<div class="post"><h2 class="title"><a href="#">Reviewer Access Codes</a></h2>
                                    <div class="entry">';
        if ($result->num_rows == 0){
            echo '<p>No reviewer access codes for this email address.</p>';
        } else {
            $noemail = false;
            echo '<p>';
            while ($row=$result->fetch_array(MYSQLI_ASSOC)){
                echo 'Review for ' . $row['emp'] . ' due on ' . date("m\/d\/Y", $row['dd']) . ': ';
                if ($row['done'] == 1){
                    echo ' <font style="color: darkgreen">(completed)</font>';
                } else {
                    echo '<a href="sendcode.php?id=' . $row['id'] . '&r=1" onclick="showSpinner();">Retrieve Code</a>';
                }
                echo '<br />';
            }
            echo '</p>';
        }
        echo '</div></div>';
        $result->close();
    }
}
if (!isset($_POST['submit']) || !$goodemail || $noemail){
    //Show the form if the user hasn't submitted it yet or there is bad data
?>
                    <div class="post">
                        <h2 class="title"><a href="#">Retrieve Access Code</a></h2>
				<div class="entry">
                                    <form action="lostkey.php" method="POST">
                                    <p>
                                        Please Enter your email address and press "GO".<br />
                                        Email: <input type="text" name="email" size="50" />
                                        <input type="submit" name="submit" id="submit" value="GO" />
                                    </p>
                                    <?php
                                        if (!$goodemail){
                                            echo '<p id="red">Please enter a <strong>VALID</strong> email address.</p>';

                                        }
                                        if ($noemail && $goodemail){
                                            echo '<p id="red">It does not appear that you have any valid access
                                                codes associated with your email address.</p>';
                                        }
                                    ?>
                                    </form>
                                </div>
			</div>
<?php
}
echo '</div>';
require_once('footer.php');
?>
