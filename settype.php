<?php
require('header.php');
if (isset($_GET['type'])){
    $_SESSION['type'] = $_GET['type'];
    echo '<script>window.location="' . $_GET['return'] . '?key=' . $_GET['key'] . '"</script>';
} else {
    echo '<script>window.location="/"</script>';
}
require('footer.php');
?>
