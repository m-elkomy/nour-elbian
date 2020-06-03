<?php
    ob_start(); //start output buffering store all output in memory
    session_start();//start the session
    if(isset($_SESSION['username'])){//check if there is session with this username
        $pagetitle = 'الغياب'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//check if there is get request with do
        if($do == 'Manage'){//manage page
            //select all data from teacher table
            $stmt = $con->prepare("SELECT DISTINCT teachers.* ,branch.B_Name 
                                            FROM teachers INNER JOIN branch 
                                            ON teachers.BranchID = branch.BranchID
                                            ORDER BY teachers.TeacherID ASC");
            $stmt->execute();//execute the query
            $rows = $stmt->fetchAll();//fethc all data from database
            if(!empty($rows)){//if rows not empty show manage page
            ?>
            <h1 class="text-center"><?php echo lang('Absence')?></h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div id="datatable-wrapper" class="table-responsive">
                    <!-- start main table -->
                    <table class="table table-bordered main-table text-center">
                        <tr>
                            <td><?php echo lang('Teacher ID')?></td>
                            <td><?php echo lang('Teacher Name')?></td>
                            <td><?php echo lang('Branch Name');?></td>
                            <td><?php echo lang('Control');?></td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//for loop to show all data in table cell
                            echo '<tr>';
                                echo '<td>' . $row['TeacherID'] . '</td>';//show teacher id
                                echo '<td>' . $row['TeacherName'] . '</td>';//show teacher name
                                echo '<td>' . $row['B_Name'] . '</td>';//show branch nam
                                echo '<td>
                                    <a href="?do=DaysOfAbsence&tid='.$row['TeacherID'].'" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-arrow-right"></span> '.lang('Days Of Absence').'</a>
                                    <a href="?do=viewabsence&tid='.$row['TeacherID'].'" class="btn btn-info">
                                    <span class="glyphicon glyphicon-arrow-left"></span> '.lang('View Absence').'</a>
                                    </td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <!-- end main table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- end container -->
        <?php
            }else{//if rows is empty show this error message
                echo '<div class="container">
                    <div class="alert alert-info nice-message">'.lang('Sorry There Is No Recored To Show').'</div>
                    </div>';
            }
            ?>
            <div class="container"><a href="?do=Add" class="add btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span> <?php echo lang('Add New Absence')?></a>
                <form method="post" action="absence_report.php" class="pdf form-horizontal">
                    <input type="submit" name="create_pdf" class="btn btn-primary btn-lg add" value="<?php echo lang('Create PDF')?>" />
                    <div class="form-group form-group-lg">
                        <label class="col-sm-1 control-label"><?php echo lang('Branch')?></label>
                        <div class="col-sm-10 col-md-6 pull-right">
                            <select name="brancid">
                                <option value="0">..........</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC ");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                foreach($rows as $row){
                                    echo '<option value="'.$row['BranchID'].'">' . $row['B_Name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        <?php
        }elseif($do == 'Edit'){//edit page
            //if there is get request with tid get it and check if it's a number and store in teacher id
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) : 0;
            //select data from teacher table based on id selected
            $stmt = $con->prepare("SELECT absence.* ,teachers.* 
                                            FROM absence INNER  JOIN teachers ON 
                                            absence.TeacherID = teachers.TeacherID 
                                            WHERE absence.TeacherID = ?");

            $stmt->execute(array($teacherid));//execute the query
            $rows = $stmt->fetch();//fetch data from db
            $count = $stmt->rowCount();//check number of rows matched the query
            if($count>0){//if number of row large than 0
                ?>
                    <h1 class="text-center"><?php echo lang('Edit Absence');?></h1>
                    <!-- start the container -->
                    <div class="container">
                        <!-- start update form -->
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <!-- start teacher id -->
                            <input type="hidden" name="teacher_id" value="<?php echo $teacherid?>"/>
                            <input type="hidden" name="absid" value="<?php echo $rows['ID']?>"/>
                            <!-- end teacher id -->
                            <!-- start teacher name -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label"><?php echo lang('Teacher Name')?></label>
                                <div class="col-sm-10 col-md-6 pull-right">
                                    <input type="text" disabled class="form-control" required="required"
                                           name="teachername" value="<?php echo $rows['TeacherName'];?>"
                                           placeholder="<?php echo lang('Teacher Name')?>" autocomplete="off"/>
                                </div>
                            </div>
                            <!-- end teacher name -->
                            <!-- start attend date -->
                            <div class="form-group form-group-lg">
                                <label class="control-label col-sm-2"><?php echo lang('Absence Date')?></label>
                                <div class="col-sm-10 col-md-6 pull-right">
                                    <input type="date" value="<?php echo $rows['Date']?>" class="ab-date form-control" required="required"
                                           name="absencedate" autocomplete="off"/>
                                </div>
                            </div>
                            <!-- start submit button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-10">
                                    <input type="submit" value="<?php echo lang('Update');?>" class="btn btn-primary btn-lg"/>
                                </div>
                            </div>
                            <!-- end submit button -->
                        </form>
                        <!-- end update form -->
                    </div>
                <!-- end container -->
                <?php
            }else{//if row not equal 0 show this message
                $msg = '<div class="alert error alert-danger">'.lang('Sorry There Is No Such ID').'</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Update'){//update page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//CEHCK that user coming through post request
                echo '<h1 class="text-center">'.lang('Update Absence').'</h1>';
                echo '<div class="container">';
                $teacherid = $_POST['teacher_id'];
                $attid     = $_POST['absid'];
                $absencedate = $_POST['absencedate'];
                $formerror = array();
                if(empty($absencedate)){
                    $formerror[] = lang('Attendance Date Can Not Be Empty');
                }
                foreach($formerror AS $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerror)){

                            $stmt = $con->prepare("UPDATE absence SET Date = ? 
                                    WHERE absence.TeacherID=? AND absence.ID=?");
                            $stmt->execute(array($absencedate,$teacherid,$attid));
                            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Update'). '</div>';
                            redirect($msg,'back');
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">'.lang('Sorry You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Add'){//add teachers page
            ?>
            <h1 class="text-center"><?php echo lang('Add Absence');?></h1>
            <!-- start container -->
            <div class="container">
                <!-- start add form -->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start teacher name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('Teacher Name')?></label>
                        <div class="col-sm-10 col-md-6 pull-right">
                            <select name="teacher">
                                <option value="0">..........</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM teachers ORDER BY TeacherID ASC ");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                foreach($rows as $row){
                                    echo '<option value="'.$row['TeacherID'].'">' . $row['TeacherName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end teacher name -->
                    <!-- start attend date -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2"><?php echo lang('Absence Date')?></label>
                        <div class="col-sm-10 col-md-6 pull-right">
                            <input type="date" class="form-control" required="required"
                                   name="absencedate" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- start submit button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-10">
                            <input type="submit" value="<?php echo lang('Save')?>" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                    <!-- end submit button -->
                </form>
                <!-- end add form -->
            </div>
            <!-- end container -->
        <?php
        }elseif($do == 'Insert'){//insert page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that user coming through post request
                echo '<h1 class="text-center">'.lang('Insert Absence').'</h1>';
                echo '<div class="container">';
                $teacherid  = $_POST['teacher'];//receive branchid and store it in var
                $absencedate = $_POST['absencedate'];//receive branchid and store it in var
                $formerror = array();
                if($teacherid == 0){
                    $formerror[] = lang('Teacher Name Can Not Be Empty');
                }
                if(empty($absencedate)){
                    $formerror[] = lang('Attendance Date Can Not Be Empty');
                }
                foreach($formerror AS $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerror)){
                        $check = checkda('absence',$absencedate,$teacherid);
                        if($check == 0){
                            $stmt = $con->prepare("INSERT INTO absence(TeacherID,Date) VALUES(:zid,:zdate)");
                            $stmt->execute(array(
                                'zid' => $teacherid,
                                'zdate'=> $absencedate
                            ));
                            $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Inserted') . '</div>';
                            redirect($msg,'back');
                        }else{
                            $msg = '<div class="alert alert-danger">' . lang('This Date Is Entered Before');
                            redirect($msg,'back');
                        }
                    }
                echo '</div>';
            }else{//if user not coming throgh post request
                $msg = '<div class="alert alert-danger">'.lang('Sorry You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Delete'){//delete page
            echo '<h1 class="text-center">'.lang('Delete Absence').'</h1>';
            echo '<div class="container">';
            //get teacher id and store it in var
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) :0;
            $check = checkitem('TeacherID','teachers',$teacherid);//check that this id exist
            $date = $_GET['date'];
            if($check == 1){//if exist delete it
                $stmt = $con->prepare("DELETE FROM absence WHERE absence.TeacherID =? AND absence.Date=?");
                $stmt->execute(array($teacherid,$date));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Deleted'). '</div>';
                redirect($msg,'back');//redirect user to previous page
            }else{//if not = 1 show this message
                $msg = '<div class="alert alert-danger">'.lang('Sorry There Is No Such ID').'</div>';
                redirect($msg);//redirect user to login page
            }
            echo '</div>';
        }elseif($do == 'DaysOfAbsence'){
                        $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) :0;
                        $stmt  = $con->prepare("SELECT  teachers.TeacherID,teachers.TeacherName,
                                                        COUNT(Date) AS days FROM teachers INNER JOIN 
                                                        absence ON teachers.TeacherID = absence.TeacherID 
                                                        WHERE teachers.TeacherID = ?");
                        $stmt->execute(array($teacherid));
                        $rows = $stmt->fetchAll();
                        if(!empty($rows)){
                        ?>
                        <h1 class="text-center"><?php echo lang('Days Of Absence')?></h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="table table-bordered main-table text-center">
                        <tr>
                            <td><?php echo lang('Teacher ID')?></td>
                            <td><?php echo lang('Teacher Name')?></td>
                            <td><?php echo lang('Days Of Absence');?></td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//for loop to show all data in table cell
                            echo '<tr>';
                            echo '<td>' . $row['TeacherID'] . '</td>';//show teacher id
                            echo '<td>' . $row['TeacherName'] . '</td>';//show teacher name
                            echo '<td>' . $row['days']     .'</td>';//show branch name
                            echo '</tr>';
                            }
                        ?>
                    </table>
                    <!-- end main table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- end container -->
        <?php
                        }else{
                            echo '<div class="container">
                                    <div class="alert alert-info nice-message">'
                                        .lang('Sorry There Is No Recored To Show').
                                    '</div>
                                    </div>';
                        }
        }elseif($do == 'viewabsence'){
                $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) :0;
                $stmt  = $con->prepare("SELECT  absence.*,teachers.TeacherName
                                                             FROM absence INNER JOIN 
                                                            teachers ON absence.TeacherID = teachers.TeacherID 
                                                            WHERE absence.TeacherID = ?");
                $stmt->execute(array($teacherid));
                $rows = $stmt->fetchAll();
                if(!empty($rows)){
                ?>
                <h1 class="text-center"><?php echo lang('View Absence')?></h1>
                <!-- start container -->
                <div class="container">
                    <!-- start table responsive -->
                    <div class="table-responsive">
                        <!-- start main table -->
                        <table class="table table-bordered main-table text-center">
                            <tr>
                                <td><?php echo lang('Teacher Name')?></td>
                                <td><?php echo lang('Date')?></td>
                                <td><?php echo lang('Control');?></td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {//for loop to show all data in table cell
                                echo '<tr>';
                                echo '<td>' . $row['TeacherName'] . '</td>';//show teacher id
                                echo '<td>' . $row['Date'] . '</td>';//show teacher name
                                echo '<td>';
                                    echo '<a href="?do=Edit&tid='.$row['TeacherID'].'" class="btn btn-success">
                                    <span class="glyphicon glyphicon-edit"></span> '.lang('Edit').'</a>
                                    <a href="?do=Delete&tid='.$row['TeacherID'].'&date='.$row['Date'].'" class="confirm btn btn-danger">
                                    <span class="confirm glyphicon glyphicon-remove"></span> '.lang('Delete').'</a>';
                                echo '</td>';//show branch name
                                echo '</tr>';
                            }
                            ?>
                        </table>
                        <!-- end main table -->
                    </div>
                    <!-- end table responsive -->
                </div>
                <!-- end container -->
        <?php
        }else{
                    echo '<div class="container">
                            <div class="alert alert-info nice-message">'.
                                lang('Sorry There Is No Recored To Show').
                                '</div>
                            </div>';
                }
        }
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//redirect user to login page if session not exist
        exit();//stop executing any script
    }
    ob_end_flush();//after page end go output