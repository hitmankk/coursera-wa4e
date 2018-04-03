<?php
  session_start();
  $_SESSION['error'] = "fault";
  echo("what?");
  if ( isset($_SESSION['error']) ) {
    echo("hello");
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    error_log(htmlentities($_SESSION['error']));
    unset($_SESSION['error']);
  }
?>
