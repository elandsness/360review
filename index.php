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
?>

	<div id="page">
		<div id="content">
			<div class="post">
				<h2 class="title"><a href="#">Welcome to the 360&deg; Feedback App </a></h2>
				<div class="entry">
					<p>This is the <strong><?php echo COMPANY_NAME; ?> 360&deg; Feedback App </strong>, a tool created
                                            with the purpose of offering employees an opportunity to obtain
                                            valuable feedback about themselves from their peers.  It isn't often that
                                            we are able to receive open, sincere and honest feedback about the interactions
                                            we have with our peers, but you have decided to take the first step in
                                            soliciting such a review by visiting this site.</p>
                                        <p>This application allows you to reach out to a group of selected coworkers,
                                            subordinates, managers or even friends and family if you like and ask them
                                            to provide an anonymous analysis of the way that you interact with them,
                                            focusing on the way you communicate.  Each person that reviews you will
                                            be presented with a series of <?php
                                            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
                                            $query = "SELECT count(id) as ids FROM tbl_questions";
                                            $result = $mysqli->query($query);
                                            while ($row = $result->fetch_array(MYSQLI_ASSOC)){
                                                echo $row['ids'];
                                            }
                                            $result->close();
                                            ?> words and phrases that they will rate on how applicable each one is
                                            on a scale of 1 to 10.  Based on the answers, you will be given ratings
                                            on 6 personality points outlining your strengths and opportunities for
                                            improvement.</p>
                                        <p>The 6 persona are Entrepreneur, Competitor, Producer, Stabilizer,
                                            Team Builder and Creator.  These are the 6 desirable qualities that
                                            are shared by all of the greatest leaders in the world.  In addition to these,
                                            there are 6 persona that identify excesses in each of the 6 desirable traits.
                                            These are Performer, Attacker, Commander, Avoider, Pleaser and Drifter.  These
                                            Personalities appear in a persons feedback when they have excessive levels
                                            of a persona.  By measuring your strengths and weaknesses against these
                                            traits, you can identify the areas in which you excel and the areas that
                                            you should focus on to help you grow as an individual.  To learn more about
                                            the desirable and excessive persona, take a look at the
                                            <a href="personas.php">persona definition page</a>.</p>
                                        <p>If you have an <a href="enterkey.php">access key</a>, you can enter it into
                                            the form in the upper right hand side of the page and press enter to
                                            access the functionality that your key is intended to provide you.
                                            If you do not have an access key, you can browse to one of the following
                                            pages:</p>
                                        <ul>
                                            <li><a href="index.php">Home</a>: This page.</li>
                                            <li><a href="employee.php">Employee</a>: View your feedback or send feedback invites to your peers.</li>
                                            <li><a href="manager.php">Manager</a>: View your employees' feedback or start a feedback process for someone else.</li>
                                            <li><a href="enterkey.php">Access</a>: Enter an access key that has been sent to you to view the related content.</li>
                                            <li><a href="personas.php">Persona</a>: See explanations of the 12 persona.</li>
                                            <li><a href="lostkey.php">Lost Key</a>: Have your key sent to your email address if you lost it.</li>
                                        </ul>
				</div>
			</div>
		</div>
        

<?php
require_once('footer.php');
?>
