<?php
session_start();
$nonavbar = '';
include 'init.php';
//select all classes and related data in other table
$order = $_GET['q'];
if($order == 'CID'){
    $order = 'ClassID';
}elseif($order == 'CName'){
    $order = 'ClassName';
}elseif($order == 'BID'){
    $order = 'branch.BranchID';
}elseif($order == 'BName'){
    $order = 'branch.B_Name';
}
$stmt = $con->prepare("SELECT 
                                classes.*, branch.* 
                                FROM classes INNER JOIN 
                                branch ON classes.BranchID = branch.BranchID ORDER BY $order ");
$stmt->execute();
$stmt->execute();//execute the query
$rows = $stmt->fetchAll();//fetch all data
?>
    <tr>
        <td>ID</td>
        <td>Class Name</td>
        <td>Branch ID</td>
        <td>Branch Name</td>
        <td>Control</td>
    </tr>
<?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['ClassID'] . '</td>';//student id
                                echo '<td>' . $row['ClassName'] . '</td>';//student name
                                echo '<td>' . $row['BranchID'] . '</td>';//student name
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
                                      </td>';//control buttons
                            echo '</tr>';
                        }
include $temp . 'footer.php';
