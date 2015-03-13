<?php
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
