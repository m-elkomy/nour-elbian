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
$stmt->execute();//execute the query
$rows = $stmt->fetchAll();//fetch all data
?>
    <tr>
        <td>ID</td>
        <td>Branch Name</td>
        <td>Control</td>
    </tr>
<?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['BranchID'] . '</td>';//student id
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
                                      </td>';//control buttons
                            echo '</tr>';
                        }
include $temp . 'footer.php';
