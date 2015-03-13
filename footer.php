		<!-- end #content -->
		<div id="sidebar">
			<ul>
                                <li>
                                        <h2>Access Code</h2>
                                        <div id="search" >
                                                <form method="get" action="enterkey.php">
                                                        <div>
                                                                <input type="text" name="key" id="search-text" value="" />
                                                                <input type="submit" name="submit" id="submit" value="GO" />
                                                        </div>
                                                </form>
                                        </div>
                                        <div style="clear: both;">&nbsp;</div>
                                </li>

				<li>
					<h2>Navigation</h2>
					<ul>
						<li><a href="index.php">Home</a></li>
                                                <li><a href="employee.php">Employee</a></li>
                                                <li><a href="manager.php">Manager</a></li>
                                                <li><a href="enterkey.php">Code</a></li>
                                                <li><a href="personas.php">Personas</a></li>
                                                <li><a href="enterkey.php">Access</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- end #sidebar -->
		<div style="clear: both;">&nbsp;</div>
	</div>

<!-- end #page -->
</div>
<div id="footer">
	<p>made with l&hearts;ve</p>
</div>
<!-- end #footer -->
</body>
</html>

<?php
// Setup current and previous page variables
$_SESSION['prepage'] = $_SESSION['curpage'];
$_SESSION['curpage'] = $path_parts['basename'];
?>
