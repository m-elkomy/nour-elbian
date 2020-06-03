<?php
    ob_start(); //start output buffering store all output in memory
    session_start();//start the session
    if(isset($_SESSION['username'])){//check if there is session with this username
        $pagetitle = 'التقارير'; // set page title
        include 'init.php';//include initialize file
        include $libr . 'tcpdf/tcpdf.php';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $classid = $_POST['classid'];
            $branchid = $_POST['branchid'];
            if($branchid == 0 && $classid == 0) {
                function fetch_data()
                {
                    global $con;
                    $output = '';
                    $stmt = $con->prepare("SELECT students.* ,branch.B_Name,classes.ClassName
                                            FROM students INNER JOIN branch 
                                            ON students.BranchID = branch.BranchID INNER JOIN 
                                            classes ON students.ClassID = classes.ClassID
                                            ORDER BY students.StudentID ASC");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        if($row['Paid'] == 0){
                        $output .= '<tr>  
                          <td>' . $row['StudentID'] . '</td>  
                          <td>' . $row['StudentName'] . '</td>  
                          <td>' . $row['ClassName'] . '</td>  
                          <td>' . $row['B_Name'] . '</td>  
                          <td>' . lang('Not Paid') . '</td>  
                     </tr>';
                    }else{
                            $output .= '<tr>  
                          <td>' . $row['StudentID'] . '</td>  
                          <td>' . $row['StudentName'] . '</td>  
                          <td>' . $row['ClassName'] . '</td>  
                          <td>' . $row['B_Name'] . '</td>  
                          <td>' . lang('Paid') . '</td>  
                     </tr>';
                        }
                    }
                    return $output;
                }

                if (isset($_POST["create_pdf"])) {
                    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $obj_pdf->SetCreator(PDF_CREATOR);
                    $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
                    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
                    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                    $obj_pdf->SetDefaultMonospacedFont('helvetica');
                    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
                    $obj_pdf->setPrintHeader(false);
                    $obj_pdf->setPrintFooter(false);
                    $obj_pdf->SetAutoPageBreak(TRUE, 10);
                    $obj_pdf->SetFont('aealarabiya', '', 12);
                    $obj_pdf->AddPage();
                    $content = '';
                    $content .= '  
      <h3 align="center">'.lang('Students').'</h3><br /><br />  
      <table align="center" border="1" cellspacing="0" cellpadding="5">  
           <tr>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Student ID').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Student Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Class Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="25%">'.lang('Branch Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Pay Or Not').'</th>  
           </tr>  
      ';

                    ob_end_clean();
                    $content .= fetch_data();
                    $content .= '</table>';
                    $obj_pdf->writeHTML($content);
                    $obj_pdf->Output('Student.pdf', 'I');
                }
                $pdf->output();
            }elseif($branchid == 0){
                function fetch_data($classid)
                {
                    global $con;
                    $output = '';
                    $stmt = $con->prepare("SELECT students.* ,branch.B_Name,classes.ClassName
                                            FROM students INNER JOIN branch 
                                            ON students.BranchID = branch.BranchID INNER JOIN 
                                            classes ON students.ClassID = classes.ClassID WHERE classes.ClassID = ?
                                            ORDER BY students.StudentID ASC");
                    $stmt->execute(array($classid));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row)
                    {
                        if($row['Paid'] == 0){
                        $output .= '<tr>  
                          <td>'.$row['StudentID'].'</td>  
                          <td>'.$row['StudentName'].'</td>  
                          <td>'.$row['ClassName'].'</td>  
                          <td>'.$row['B_Name'].'</td>  
                          <td>'.lang('Not Paid').'</td>  
                     </tr>';
                    }else{
                            $output .= '<tr>  
                          <td>'.$row['StudentID'].'</td>  
                          <td>'.$row['StudentName'].'</td>  
                          <td>'.$row['ClassName'].'</td>  
                          <td>'.$row['B_Name'].'</td>  
                          <td>'.lang('Paid').'</td>  
                     </tr>';
                        }
                    }
                    return $output;
                }
                if(isset($_POST["create_pdf"]))
                {
                    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $obj_pdf->SetCreator(PDF_CREATOR);
                    $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
                    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
                    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                    $obj_pdf->SetDefaultMonospacedFont('helvetica');
                    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
                    $obj_pdf->setPrintHeader(false);
                    $obj_pdf->setPrintFooter(false);
                    $obj_pdf->SetAutoPageBreak(TRUE, 10);
                    $obj_pdf->SetFont('aealarabiya', '', 12);
                    $obj_pdf->AddPage();
                    $content = '';
                    $content .= '  
      <h3 align="center">'.lang('Students').'</h3><br /><br />  
      <table align="center" border="1" cellspacing="0" cellpadding="5">  
           <tr>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Student ID').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Student Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Class Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="25%">'.lang('Branch Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Pay Or Not').'</th>  
           </tr>  
      ';

                    ob_end_clean();
                    $content .= fetch_data($classid);
                    $content .= '</table>';
                    $obj_pdf->writeHTML($content);
                    $obj_pdf->Output('Student.pdf', 'I');
                }
                $pdf->output();
            }elseif($classid == 0){
                function fetch_data($branchid)
                {
                    global $con;
                    $output = '';
                    $stmt = $con->prepare("SELECT students.* ,branch.*,classes.ClassName
                                            FROM students INNER JOIN branch 
                                            ON students.BranchID = branch.BranchID INNER JOIN 
                                            classes ON students.ClassID = classes.ClassID WHERE branch.BranchID=?
                                            ORDER BY students.StudentID ASC");
                    $stmt->execute(array($branchid));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row)
                    {
                        if($row['Paid'] == 0){
                        $output .= '<tr>  
                          <td>'.$row['StudentID'].'</td>  
                          <td>'.$row['StudentName'].'</td>  
                          <td>'.$row['ClassName'].'</td>  
                          <td>'.$row['B_Name'].'</td>  
                          <td>'.lang('Not Paid').'</td>  
                     </tr>';
                    }else{
                            $output .= '<tr>  
                          <td>'.$row['StudentID'].'</td>  
                          <td>'.$row['StudentName'].'</td>  
                          <td>'.$row['ClassName'].'</td>  
                          <td>'.$row['B_Name'].'</td>  
                          <td>'.lang('Paid').'</td>  
                     </tr>';
                        }
                    }
                    return $output;
                }
                if(isset($_POST["create_pdf"]))
                {
                    $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                    $obj_pdf->SetCreator(PDF_CREATOR);
                    $obj_pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");
                    $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
                    $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                    $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                    $obj_pdf->SetDefaultMonospacedFont('helvetica');
                    $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                    $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
                    $obj_pdf->setPrintHeader(false);
                    $obj_pdf->setPrintFooter(false);
                    $obj_pdf->SetAutoPageBreak(TRUE, 10);
                    $obj_pdf->SetFont('aealarabiya', '', 12);
                    $obj_pdf->AddPage();
                    $content = '';
                    $content .= '  
      <h3 align="center">Students</h3><br /><br />  
      <table align="center" border="1" cellspacing="0" cellpadding="5">  
           <tr>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Student ID').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Student Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Class Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="25%">'.lang('Branch Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Pay Or Not').'</th>  
           </tr>  
      ';

                    ob_end_clean();
                    $content .= fetch_data($branchid);
                    $content .= '</table>';
                    $obj_pdf->writeHTML($content);
                    $obj_pdf->Output('Student.pdf', 'I');
                }
                $pdf->output();
            }
        }

        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//redirect user to login page if session not exist
        exit();//stop executing any script
    }

    ob_end_flush();//after page end go output