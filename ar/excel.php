<?php
session_start();
include "init.php";
if(isset($_POST['export_excel'])){
    $stmt = $con->prepare("SELECT * FROM students ORDER BY StudentID ASC");
    $stmt->execute();
    $row = $stmt->rowCount();
    $rows = $stmt->fetchAll();
        ?>
<div class="container">
<!-- start table responsive -->
<div class="table-responsive">
    <!-- start main table -->
    <table class="main-table text-center table table-bordered">
        <tr>
            <td>Student ID</td>
            <td>Student Name</td>
        </tr>
        <?php
        foreach ($rows as $row) {//foreach loop to show all data
            $output .= '<tr>';
            $output .= '<td>' . $row['StudentID'] . '</td>';
            $output .= '<td>' . $row['StudentName'] . '</td>';
            $output .= '</tr>';
        }
        @header("Content-Disposition: attachment; filename=mysql_to_excel.csv");
        echo $output;
        ?>
    </table>
    <!-- end main table -->
</div>
<!-- end table responsive -->
</div>
<?php

    }

include $temp . 'footer.php';