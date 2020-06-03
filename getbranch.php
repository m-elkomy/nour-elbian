<?php
session_start();
$nonavbar = '';
include 'init.php';
//select all classes and related data in other table
$order = $_GET['q'];
if($order == 'ID'){
    $order = 'BranchID';
}elseif($order == 'Name'){
    $order = 'B_Name';
}
$stmt = $con->prepare("SELECT branch.* FROM branch ORDER BY $order ");
$stmt->execute();
$rows = $stmt->fetchAll();//fetch all data
?>
    <tr>
        <td><?php echo lang('Branch ID')?></td>
        <td><?php echo lang('Branch Name')?></td>
        <td><?php echo lang('Control')?></td>
    </tr>
<?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['BranchID'] . '</td>';//student id
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href="Branche.php?do=Edit&bid='.$row['BranchID'].'">
                                      <span class="glyphicon glyphicon-edit"></span> '.lang('Edit').'</a>  
                                      <a class="btn btn-danger confirm" href="Branche.php?do=Delete&bid='.$row['BranchID'].'">
                                      <span class="glyphicon glyphicon-remove"></span> '.lang('Delete').'</a>  
                                      </td>';//control buttons
                            echo '</tr>';
                        }
include $temp . 'footer.php';
