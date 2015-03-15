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

include ('config.php');
session_start();
$image = ImageCreateFromPNG('http://' . DOMAIN_NAME . '/images/brabimg.png');
/*            'en=' . $_GET['en'] .
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
            '&dr=' . $_GET['dr']);*/
$black = imagecolorallocate($image,0,0,0);
$green = imagecolorallocate($image,34,139,34);
$red = imagecolorallocate($image,255,0,0);
$yellow = imagecolorallocate($image,255,215,0);

if ($_GET['en'] >= 17){
    $color = $green;
} elseif ($_GET['en'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 378, 230, $color, './verdana.ttf', $_GET['en']);
if ($_GET['co'] >= 17){
    $color = $green;
} elseif ($_GET['co'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 449, 265, $color, './verdana.ttf', $_GET['co']);
if ($_GET['pr'] >= 17){
    $color = $green;
} elseif ($_GET['pr'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 449, 350, $color, './verdana.ttf', $_GET['pr']);
if ($_GET['st'] >= 17){
    $color = $green;
} elseif ($_GET['st'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 378, 385, $color, './verdana.ttf', $_GET['st']);
if ($_GET['tb'] >= 17){
    $color = $green;
} elseif ($_GET['tb'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 303, 350, $color, './verdana.ttf', $_GET['tb']);
if ($_GET['cr'] >= 17){
    $color = $green;
} elseif ($_GET['cr'] <= 6){
    $color = $yellow;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 303, 265, $color, './verdana.ttf', $_GET['cr']);
if ($_GET['pe'] >= 17){
    $color = $yellow;
} elseif ($_GET['pe'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 378, 90, $color, './verdana.ttf', $_GET['pe']);
if ($_GET['at'] >= 17){
    $color = $yellow;
} elseif ($_GET['at'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 571, 190, $color, './verdana.ttf', $_GET['at']);
if ($_GET['cm'] >= 17){
    $color = $yellow;
} elseif ($_GET['cm'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 571, 420, $color, './verdana.ttf', $_GET['cm']);
if ($_GET['av'] >= 17){
    $color = $yellow;
} elseif ($_GET['av'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 378, 520, $color, './verdana.ttf', $_GET['av']);
if ($_GET['pl'] >= 17){
    $color = $yellow;
} elseif ($_GET['pl'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 181, 420, $color, './verdana.ttf', $_GET['pl']);
if ($_GET['dr'] >= 17){
    $color = $yellow;
} elseif ($_GET['dr'] <= 6){
    $color = $green;
} else {
    $color = $black;
}
imagefttext($image, 16, 0, 181, 190, $color, './verdana.ttf', $_GET['dr']);

$voffset = 12;
$size = 10;
$negoffset = 5;
$posoffset = 10;

if ($_GET['den'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['den'] >= 3 || $_GET['den'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 378;
$vparrent = 230;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['den']);

if ($_GET['dco'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dco'] >= 3 || $_GET['dco'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 449;
$vparrent = 265;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dco']);

if ($_GET['dpr'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dpr'] >= 3 || $_GET['dpr'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 449;
$vparrent = 350;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dpr']);

if ($_GET['dst'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dst'] >= 3 || $_GET['dst'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 378;
$vparrent = 385;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dst']);

if ($_GET['dtb'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dtb'] >= 3 || $_GET['dtb'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 303;
$vparrent = 350;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dtb']);

if ($_GET['dcr'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dcr'] >= 3 || $_GET['dcr'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 303;
$vparrent = 265;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dcr']);

if ($_GET['dpe'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dpe'] >= 3 || $_GET['dpe'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 378;
$vparrent = 90;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dpe']);

if ($_GET['dat'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dat'] >= 3 || $_GET['dat'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 571;
$vparrent = 190;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dat']);

if ($_GET['dcm'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dcm'] >= 3 || $_GET['dcm'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 571;
$vparrent = 420;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dcm']);

if ($_GET['dav'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dav'] >= 3 || $_GET['dav'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 378;
$vparrent = 520;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dav']);

if ($_GET['dpl'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['dpl'] >= 3 || $_GET['dpl'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 181;
$vparrent = 420;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['dpl']);

if ($_GET['ddr'] < 0){
    $offset = $negoffset;
} else {
    $offset = $posoffset;
}
if ($_GET['ddr'] >= 3 || $_GET['ddr'] <= -3){
    $color = $red;
} else {
    $color = $black;
}
$parrent = 181;
$vparrent = 190;
imagefttext($image, $size, 0, $parrent + $offset, $vparrent + $voffset, $color, './verdana.ttf', $_GET['ddr']);

header ("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
