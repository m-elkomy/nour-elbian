<?php
    ob_start(); //start output buffering store all output in memory
    session_start();//start the session
    if(isset($_SESSION['username'])){//check if there is session with this username
        $pagetitle = 'Report'; // set page title
        include 'init.php';//include initialize file
        include $libr . 'tcpdf/tcpdf.php';
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $branchid = $_POST['brancid'];
            if($branchid == 0) {
                function fetch_data()
                {
                    global $con;
                    $output = '';
                    $stmt = $con->prepare("SELECT DISTINCT teachers.*,branch.*,attendance.* FROM 
                                                    teachers INNER JOIN branch 
                                                    ON teachers.BranchID = branch.BranchID
                                                    INNER JOIN attendance ON teachers.TeacherID = 
                                                    attendance.TeacherID
                                                    ORDER BY teachers.TeacherID ASC");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row) {
                        $output .= '<tr>  
                          <td>' . $row['TeacherID'] . '</td>  
                          <td>' . $row['TeacherName'] . '</td>  
                          <td>' . $row['B_Name'] . '</td>  
                          <td>' . $row['Date'] . '</td>  
                          <td>' . $row['AttendanceTime']. '</td>  
                          <td>' . $row['LeavingTime']. '</td>  
                     </tr>';
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
      <h3 align="center">'.lang('Teachers').'</h3><br /><br />  
      <table align="center" border="1" cellspacing="0" cellpadding="5">  
           <tr>  
                <th style="background-color:#31B0D5;color:#fff" width="10%">'.lang('Teacher ID').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="25%">'.lang('Teacher Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Branch Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Date').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Attendance Time').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Leaving Time').'</th>  
           </tr>  
      ';

                    ob_end_clean();
                    $content .= fetch_data();
                    $content .= '</table>';
                    $obj_pdf->writeHTML($content);
                    $obj_pdf->Output('Attendance.pdf', 'I');
                }
                $pdf->output();
            }else{
                function fetch_data($branchid)
                {
                    global $con;
                    $output = '';
                    $stmt = $con->prepare("SELECT teachers.*,branch.*,attendance.* 
                                                    FROM teachers INNER JOIN branch 
                                                    ON teachers.BranchID = branch.BranchID 
                                                    INNER JOIN attendance ON teachers.TeacherID = 
                                                    attendance.TeacherID 
                                                    WHERE teachers.BranchID = ? ORDER BY teachers.TeacherID ASC");
                    $stmt->execute(array($branchid));
                    $rows = $stmt->fetchAll();
                    foreach ($rows as $row)
                    {
                        $output .= '<tr>  
                          <td>' . $row['TeacherID'] . '</td>  
                          <td>' . $row['TeacherName'] . '</td>  
                          <td>' . $row['B_Name'] . '</td>  
                          <td>' . $row['Date'] . '</td>  
                          <td>' . $row['AttendanceTime'] .'</td>  
                          <td>' . $row['LeavingTime'] .'</td>  
                     </tr>';
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
      <h3 align="center">'.lang('Teachers').'</h3><br /><br />  
      <table align="center" border="1" cellspacing="0" cellpadding="5">  
           <tr>  
                <th style="background-color:#31B0D5;color:#fff" width="10%">'.lang('Teacher ID').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Teacher Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Branch Name').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="20%">'.lang('Date').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Attendance Time').'</th>  
                <th style="background-color:#31B0D5;color:#fff" width="15%">'.lang('Leaving Time').'</th>  
           </tr>  
      ';

                    ob_end_clean();
                    $content .= fetch_data($branchid);
                    $content .= '</table>';
                    $obj_pdf->writeHTML($content);
                    $obj_pdf->Output('Attendance.pdf', 'I');
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