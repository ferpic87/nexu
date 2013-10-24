<?php
session_start();


header("Content-Type: text/css");
require_once '../../../arg.php';

$config = new FreiChat();
$config->init_vars();
if (isset($_GET['do']) && $_GET['do'] == 'theme') {
    if (isset($_SESSION[$uid . 'curr_theme'])) {
        $config->freichat_theme = $_SESSION[$uid . 'curr_theme'];
    }
}

/*
  if (isset($_GET['get_latest'])) {
  $get_latest = "?l=" . time();
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  } else {
  $get_latest = '';
  } */


if (isset($_SESSION[$uid . 'rtl']) && $_SESSION[$uid . 'rtl'] == true) {
    ?>		
    @import url("../../themes/<?php echo $config->freichat_theme; ?>/styles_rtl.css");
    <?php
} else {
    ?>
    @import url("../../themes/<?php echo $config->freichat_theme; ?>/styles.css");
<?php
}