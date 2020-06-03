<?php
session_start();
$nonavbar = '';
include 'init.php';
//select all classes and related data in other table
$stmt = $con->prepare("SELECT classes.* FROM classes WHERE BranchID = :z ");
$stmt->execute(array(':z'=>$_GET['q']));
$stmt->execute();//execute the query
$rows = $stmt->fetchAll();//fetch all data
?>
    <label class="control-label col-sm-2">Class Name</label>
    <div class="col-sm-10 col-md-6" >
        <select name="classid">
<?php
 foreach ($rows as $row){
        echo '<option value="'.$row['ClassID'].'">' . $row['ClassName'] . '</option>';
    }

?>
        </select>
    </div>
<?php

include $temp . 'footer.php';
