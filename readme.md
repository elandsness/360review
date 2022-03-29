# 360review
Anonymous 360 degree feedback for employees.

Synopsis
------------------------------
This is a simple 360 degree feedback site that allows individuals to solicit feedback from their peers.  The basic workflow is 1) a manager visits the site and kicks off a review process, 2) the employee is asked to review herself, 3) the employee and manager are able to invite reviews using email addresses, 4) sessions are created for the reviews and emails are sent out, 5) after enough feedback is provided, reports will be generated for the manager and employee.  The site does not utilize any login information, only unique keys for each session.  The manager has some added functionality such as seeing who has not yet responded and an option to send reminder emails.

Motivation
------------------------------
The code was written while I was managing a group of supervisors in a technical support center.  I wanted a tool to allow me to provide meaningful feedback to my employees for their career development.

Installation
------------------------------
You'll need JPGraph (http://jpgraph.net/download/) for the graphig features.  Download that and install it to the same place you put the feedback app files.  Be sure to change the folder name to "jpgraph".  Then, just put all of the feedback php files in a directory, edit the css file to modify the colors to fit your need, edit config.php with the appropriate settings, import the sql file into MySQL, and change the images in the images directory to brand it.

Also required for sending mail is PEAR Mail (https://pear.php.net/package/Mail/).

Finally, the structure for the SQL DB is found in the database folder.

License
------------------------------
This software is being provided under the GNU GENERAL PUBLIC LICENSE v3.  Please see included license file for additional information.
