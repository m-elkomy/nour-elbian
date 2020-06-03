<?php

    $dsn = 'mysql:host=localhost;dbname=nursery'; // data source name connceted with pdo
    $user = 'root'; // username to log to database
    $pass = '';     // user password to log to db
    $option = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8'); // assign all entered data with utf8 charset
    try{
        $con = new pdo($dsn,$user,$pass,$option); //create new pdo class
        $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // set error mode
    }catch(PDOException $e){
        echo 'Failed ' . $e->getMessage(); // get reason of fail to connect to db and echo it
    }