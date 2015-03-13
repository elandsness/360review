<?php
session_start();
require_once('config.php');
require_once('emailchecker.php');
require_once('shuffle.php');

if (!isset($_SESSION['type'])){
    $_SESSION['type'] = 'brab';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Perfect Blemish
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20100729

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>ClearData 360&deg; Feedback (Brought to you by Turtle Time!)</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />

<link rel="icon" type="image/ico" href="http://<?php echo DOMAIN_NAME; ?>/favicon.ico"/>

<script type="text/javascript" src="toggledone.js"></script>

<link href="datechooser.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="datechooser.js"></script>
<script type="text/javascript">
	<!-- //
            function FunctionEx6(objDate)
		{
			var ndExample5 = document.getElementById('datechooserex5');
			ndExample5.DateChooser.setEarliestDate(objDate);
			ndExample5.DateChooser.updateFields();

			return true;
		}
        // -->
</script>

</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header">
			<div id="logo">
                            <h1><a href="http://<?php echo DOMAIN_NAME; ?>"><img src="images/logo.png" height="43" /></a></h1>
			</div>
			<div id="menu">
				<ul>
<?php
$path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
switch ($path_parts['basename']){
    case ("index.php"):
            $indexactive = true;
    break;
    case ("employee.php"):
            $empactive = true;
    break;
    case ("manager.php"):
            $mgractive = true;
    break;
    case ("enterkey.php"):
            $keyactive = true;
    break;
    case ("personas.php"):
            $personaactive = true;
    break;
    case ("lostkey.php"):
            $recoveractive = true;
    break;
    case ("sendkey.php"):
            $recoveractive = true;
    break;
}

?>
					<li<?php
                                        if ($indexactive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="index.php">Home</a></li>
					<li<?php
                                        if ($empactive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="employee.php">Empl</a></li>
                                        <li<?php
                                        if ($mgractive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="manager.php">Mgr</a></li>
					<li<?php
                                        if ($keyactive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="enterkey.php">Access</a></li>
                                        <li<?php
                                        if ($personaactive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="personas.php">Personas</a></li>
					<li<?php
                                        if ($recoveractive){
                                            echo ' class="current_page_item"';
                                        }
                                        ?>><a href="lostkey.php">Lost Key</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- end #header -->
