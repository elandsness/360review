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
?>

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
