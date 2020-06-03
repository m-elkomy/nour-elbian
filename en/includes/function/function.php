<?php

    // get title function to check page title and echo it in case page has page title
    function gettitle(){
        global $pagetitle;
        if(isset($pagetitle)){
            echo $pagetitle;
        }else{
            echo 'Default';
        }
    }

    // create redirect function
    // accept parameters
    // $errormsg = echo the error message
    // $url = link to go to in redirect
    // $seconed  = number of seconed before redirect

    function redirect($msg,$url = null ,$sec=3){

        if($url === null){
            // if url is null redirect to index.php page
            $url = 'index.php';
            $link = 'Home Page';
        }else{
            // else if there is an http_referer page you come from return to it
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
                $url = $_SERVER['HTTP_REFERER']; // page you come from
                $link = 'Previous Page';
            }else{
                //else redirect to index
                $url = 'index.php';
                $link = 'Home Page';
            }

        }

        echo '<div class="container">' . $msg . '</div>';
        echo '<div class="container"><div class="alert alert-info"> You Will Be Redirected To '.$link.' Page After ' . $sec . '</div></div>';
        header("refresh:$sec;url=$url");
        exit();
    }

    //cehck item function
    //check if item exist in database
    //$select = item to select from db to check if it's exist or not
    //$from = table to select from
    //$value = value of $select

    function checkitem($select,$from,$value){
        global $con;
        $stmt1 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
        $stmt1->execute(array($value));
        $count = $stmt1->rowCount();
        return $count;
    }

    /*
     * create count number of users functions
     * accept parameter
     * $select = coloumn you want to count number of it
     * $table = table you select from
     */
    function countnum($select,$table){
        global $con;
        $statement = $con->prepare("SELECT COUNT($select) FROM $table");
        $statement->execute();
        return $statement->fetchColumn();
    }

    /*
     * get latest function
     * accept parameter
     * $select = field to select
     * $table = table to select from
     * $limit = number of latest recored to fetch
     */
    function getlatest($select,$table,$order ,$limit=5){
        global $con;
        $statement2 = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $statement2->execute();
        $row = $statement2->fetchAll();
        return $row;
    }


if(isset($_POST["ExportType"]))
{

    switch($_POST["ExportType"])
    {
        case "export-to-excel" :
            // Submission from
            $filename = $_POST["ExportType"] . ".xls";
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=\"$filename\"");
            ExportFile($data);
            //$_POST["ExportType"] = '';
            exit();
        default :
            die("Unknown action : ".$_POST["action"]);
            break;
    }
}
function ExportFile($records) {
    $heading = false;
    if(!empty($records))
        foreach($records as $row) {
            if(!$heading) {
                // display field/column names as a first row
                echo implode("\t", array_keys($row)) . "\n";
                $heading = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
    exit;
}