<?php

    /*
     * users divided into [ add \ manage \ edit \ update \ delete \ insert \stats]
     * we split one page into many page instead of create page for every section
     *
     */
    /*
    $do = '';
    if(isset($_GET['do'])){
        echo $_GET['do'];
    }else{
        $do = 'manage';
    }
    */
    // short code for if condition
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    // if page is main page
    if($do == 'manage'){
        echo 'manage page';
    }elseif($do == 'edit'){
        echo 'edit page';
    }elseif($do == 'update'){
        echo 'update page';
    }elseif($do == 'add'){
        echo 'add page';
    }elseif($do == 'insert'){
        echo 'insert page';
    }elseif($do == 'delete'){
        echo 'delete page';
    }else{
        echo 'error';
    }