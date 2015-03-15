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

session_start();
require ('config.php');
if (isset($_GET['type'])){
    $type = $_GET['type'];
} elseif (isset($_SESSION['type'])){
    $type = $_SESSION['type'];
} else {
    $type = 'combo';
}
$image = ImageCreateFromPNG('http://' . DOMAIN_NAME . '/draw' . $type . 'graph.php?' .
            'en=' . $_GET['en'] .
            '&co=' . $_GET['co'] .
            '&pr=' . $_GET['pr'] .
            '&st=' . $_GET['st'] .
            '&tb=' . $_GET['tb'] .
            '&cr=' . $_GET['cr'] .
            '&pe=' . $_GET['pe'] .
            '&at=' . $_GET['at'] .
            '&cm=' . $_GET['cm'] .
            '&av=' . $_GET['av'] .
            '&pl=' . $_GET['pl'] .
            '&dr=' . $_GET['dr']);
$black = imagecolorallocate($image,0,0,0);
ImageLine($image,400,300,282,90,$black);
ImageLine($image,400,300,280,510,$black);
ImageLine($image,400,300,525,91,$black);
ImageLine($image,400,300,522,512,$black);
ImageLine($image,400,300,160,300,$black);
ImageLine($image,400,300,643,300,$black);
header ("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
