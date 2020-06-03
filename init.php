<?php

    //include connect to database
    include 'connect.php';
    // routes for directory

    $temp = 'includes/template/'; // template directory
    $lang = 'includes/language/'; // language directory
    $func = 'includes/function/'; // function directory
    $libr = 'includes/library/'; // library directory
    $css  = 'layout/css/';       // css directory
    $js   = 'layout/js/';        // js directory
    // include important files
    include $lang . 'arabic.php'; // include english file
    include $func . 'function.php'; // include function file
    include $temp . 'header.php'; // include header file
    if(!isset($nonavbar)) {
        include $temp . 'navbar.php'; // include navbar file to all page that not has nonavbar variable
    }