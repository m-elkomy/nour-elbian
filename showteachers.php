<?php
session_start();
$nonavbar = '';
include 'init.php';
//select all classes and related data in other table
$order = $_GET['q'];
if($order == 'TID'){
    $order = 'TeacherID';
}elseif($order == 'TName'){
    $order = 'TeacherName';
}elseif($order == 'BID'){
    $order = 'branch.BranchID';
}elseif($order == 'BName'){
    $order = 'branch.B_Name';
}
$stmt = $con->prepare("SELECT 
                                teachers.*, branch.* 
                                FROM teachers INNER JOIN 
                                branch ON teachers.BranchID = branch.BranchID ORDER BY $order ");
$stmt->execute();
$rows = $stmt->fetchAll();//fetch all data
?>
    <tr>
        <td><?php echo lang('Teacher ID')?></td>
        <td><?php echo lang('Teacher Name')?></td>
        <td><?php echo lang('Branch ID')?></td>
        <td><?php echo lang('Branch Name')?></td>
        <td><?php echo lang('Control')?></td>
    </tr>
<?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['TeacherID'] . '</td>';//student id
                                echo '<td>' . $row['TeacherName'] . '</td>';//student name
                                echo '<td>' . $row['BranchID'] . '</td>';//student name
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href="teachers.php?do=Edit&tid='.$row['TeacherID'].'">
                                      <span class="glyphicon glyphicon-edit"></span> '.lang('Edit').'</a>  
                                      <a class="btn btn-danger confirm" href="teachers.php?do=Delete&tid='.$row['TeacherID'].'">
                                      <span class="glyphicon glyphicon-remove"></span> '.lang('Delete').'</a>  
                                      </td>';//control buttons
                            echo '</tr>';
                        }
include $temp . 'footer.php';
