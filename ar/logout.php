<?php
    session_start(); // start the session you want to destroy

    session_unset(); // unset the session data

    session_destroy(); // destroy the session

    header('location:index.php');
    exit();
