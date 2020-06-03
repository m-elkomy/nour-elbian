<?php
    ob_start();//start output buffering to store all output in memory
    session_start();//start session
    if(isset($_SESSION['username'])){//check if session register with user name show page
        $pagetitle = 'Salary'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//if there is get request with do store request in do var
        if($do == 'Manage'){//manage page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT salary.*,teachers.TeacherID,teachers.TeacherName,
                                            branch.B_Name 
                                            FROM teachers INNER JOIN salary 
                                            ON teachers.TeacherID = salary.TeacherID 
                                            INNER JOIN branch ON teachers.BranchID = branch.BranchID
                                            ORDER BY salary.TeacherID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
                ?>
            <h1 class="text-center"><?php echo lang('Manage Salary')?></h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td><?php echo lang('Teacher ID')?></td>
                            <td><?php echo lang('Teacher Name')?></td>
                            <td><?php echo lang('Salary')?></td>
                            <td><?php echo lang('Branch Name')?></td>
                            <td><?php echo lang('Take Salary Or Not')?></td>
                            <td><?php echo lang('Control')?></td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['TeacherID'] . '</td>';//student id
                                echo '<td>' . $row['TeacherName'] . '</td>';//student name
                                echo '<td>' . $row['Salary'] . '</td>';//class name
                                echo '<td>' . $row['B_Name'] . '</td>';//branch name
                                echo '<td>' ;
                                        if($row['SalaryCollect'] == 1){echo lang('Salary Collected');}else{echo lang('Salary Not Collected');};
                                echo  '</td>';//pay or not pay
                                echo '<td>
                                      <a class="btn btn-success" href="?do=Edit&tid='
                                    .$row['TeacherID'].'"><span class="glyphicon glyphicon-edit"></span> '
                                    .lang('Edit').'</a>  
                                      <a class="btn btn-danger confirm" href="?do=Delete&tid='
                                    .$row['TeacherID'].'"><span class="glyphicon glyphicon-remove"></span> '
                                    .lang('Delete').'</a>
                                      <a class="btn btn-info" href="?do=TotalSalary&tid='
                                    .$row['TeacherID'].'"><span class="glyphicon glyphicon-briefcase"></span> '
                                    .lang('Total Salary').'</a>  
                                      </td>';//control buttons
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <!-- end main table -->
                </div>
                <!-- end table responsive -->
            </div>
            <!-- end responsive table -->
        <?php
            }else{//if rows is empty show this message
                echo '<div class="container">
                <div class="alert alert-info nice-message">'
                    .lang('Sorry There Is No Recored To Show').
                    '</div>
                </div>';
            }?>
            <div class="container"><a href="?do=Add" class="add btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span>
                    <?php echo lang('Add Salary')?></a>
                <form method="post" action="salary_report.php" class="pdf form-horizontal">
                    <input type="submit" name="create_pdf" class="btn btn-primary btn-lg add" value="<?php echo lang('Create PDF')?>" />
                    <div class="form-group form-group-lg sort">
                        <label class="col-sm-1 control-label"><?php echo lang('Branch')?></label>
                        <div class="col-sm-10 col-md-6">
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
            //receive student id and check that is number and store it's value in student id var
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) : 0;
            //select student data with this id
            $stmt = $con->prepare("SELECT salary.*,teachers.TeacherID,teachers.TeacherName 
                                            FROM salary INNER JOIN teachers 
                                            ON salary.TeacherID = teachers.TeacherID 
                                            WHERE teachers.TeacherID = ?");
            $stmt->execute(array($teacherid));//execute query
            $row = $stmt->fetch();//fetch data
            $count = $stmt->rowCount();//check for number of row matched query
            if($count > 0){//if count large than 0
            ?>
                <h1 class="text-center"><?php echo lang('Edit Salary')?></h1>
                <!-- start container -->
                <div class="container">
                    <!-- start edit form -->
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <!-- start student id -->
                        <input type="hidden" name="t_id" value="<?php echo $teacherid;?>"/>
                        <!-- end student id -->
                        <!-- start student name -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('Teacher Name')?></label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="teachername" class="form-control"
                                       value="<?php echo $row['TeacherName']?>"
                                       placeholder="<?php echo lang('Teacher Name')?>"
                                       disabled autocomplete="off"/>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('Salary')?></label>
                            <div class="col-sm-10 col-md-6">
                                <input type="number" min="0" name="salary" class="form-control"
                                       value="<?php echo $row['Salary']?>"
                                       placeholder="<?php echo lang('Salary')?>"
                                       required="required" autocomplete="off"/>
                            </div>
                        </div>
                        <!-- end student name -->
                        <!-- start pay or not pay -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('Take Salary Or Not')?></label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="col-yes" type="radio" name="col"
                                           value="1" <?php if($row['SalaryCollect'] == 1) echo 'checked'?>/>
                                    <label for="col-yes"><?php echo lang('Yes')?></label>
                                </div>
                                <div>
                                    <input id="col-no" type="radio" name="col"
                                           value="0" <?php if($row['SalaryCollect'] == 0) echo 'checked'?>/>
                                    <label for="col-no"><?php echo lang('No')?></label>
                                </div>
                            </div>
                        </div>
                        <!-- end pay or not -->
                        <!-- start submit button -->
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('Update')?>"/>
                            </div>
                        </div>
                        <!-- end submit button -->
                    </form>
                    <!-- end edit form -->
                </div>
                <!-- end container -->
            <?php
            }else{//if count not large than 0 show this error message
                $msg = '<div class="alert alert-danger">'.lang('Sorry There Is No Such ID').'</div>';
                redirect($msg);//redirect message
            }
        }elseif($do == 'Update'){//update page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that user coming through post request
                echo '<h1 class="text-center">'.lang('Update Salary').'</h1>';
                echo'<div class="container">';
                $teacherid = $_POST['t_id'];//receive student name
                $salary    = $_POST['salary'];//receive student id
                $collect   = $_POST['col'];//receive branch id
                $formerror = array();//set formerror array to validate form and store all error in form
                if(empty($salary)){//if student name is empty store this message in formerror array
                    $formerror[] = lang('Salary Can Not Be Empty');
                }
                foreach ($formerror as $error){//foreach loop to show all error message stored in formerror array
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror is empty  execute query
                    $stmt = $con->prepare("UPDATE salary SET Salary = ? ,SalaryCollect = ? 
                                                    WHERE TeacherID = ?");
                    $stmt->execute(array($salary,$collect,$teacherid));//execute query
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Update') . '</div>';//success msg
                    redirect($msg,'back');//redirect user to previous page
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">'.lang('You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect message
            }
        }elseif($do == 'Add'){//add page
            ?>
            <h1 class="text-center"><?php echo lang('Add Students')?></h1>
            <!-- start container -->
            <div class="container">
                <!-- start edit form -->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start student name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('Teacher Name')?></label>
                        <div class="col-sm-10 col-md-6">
                            <select name="teacherid">
                                <option value="0">..........</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM teachers 
                                                                WHERE TeacherID 
                                                                NOT IN(SELECT TeacherID FROM salary) 
                                                                ORDER BY teachers.TeacherID");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                foreach($rows as $row){
                                    echo '<option value="'.$row['TeacherID'].'">' . $row['TeacherName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('Salary')?></label>
                        <div class="col-sm-10 col-md-6">
                            <input type="number" min="0" name="salary" class="form-control"
                                   placeholder="<?php echo lang('Salary')?>"
                                   required="required" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end student name -->
                    <!-- start pay or not pay -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label"><?php echo lang('Take Salary Or Not')?></label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="col-yes" type="radio" name="col" value="1" checked/>
                                <label for="col-yes"><?php echo lang('Yes')?></label>
                            </div>
                            <div>
                                <input id="col-no" type="radio" name="col" value="0"/>
                                <label for="col-no"><?php echo lang('No')?></label>
                            </div>
                        </div>
                    </div>
                    <!-- end pay or not -->
                    <!-- start submit button -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg"
                                   value="<?php echo lang('Save')?>"/>
                        </div>
                    </div>
                    <!-- end submit button -->
                </form>
                <!-- end edit form -->
            </div>
            <!-- end container -->
            <?php
        }elseif($do == 'Insert'){//insert page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that use coming through pos request
                echo '<h1 class="text-center">'.lang('Insert Salary').'</h1>';
                echo '<div class="container">';
                $teachername = $_POST['teacherid'];//receive student name
                $salary      = $_POST['salary'];//receive branch id
                $collect     = $_POST['col'];//receive class id
                $formerror = array();//set formerror array to validate form and store all error message in it
                if($teachername == 0){//if student name is empty store this message in formerror array
                    $formerror[] = lang('Teacher Name Can Not Be Empty');
                }
                if(empty($salary)){
                    $formerror[] = lang('Salary Can Not Be Less Than 4 Character');
                }
                if(empty($collect)){
                    $formerror[] = lang('Salary Collect Can Not Be Empty');
                }
                foreach ($formerror as $error){//foreach loop to show all error message
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror is empty execute query
                    $check = checkitem("TeacherID","salary",$teachername);
                    if($check == 0){
                        $stmt = $con->prepare("INSERT INTO 
                                                                salary(TeacherID,Salary,SalaryCollect) 
                                                                VALUES(:zid,:zsalary,:zcol)");
                        $stmt->execute(array('zid'=>$teachername,'zsalary'=>$salary,'zcol'=>$collect));
                        $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Inserted'). '</div>';
                        redirect($msg,'back');//redirect user to previous page
                    }else{
                        $msg = '<div class="alert alert-danger">' . lang('Sorry This Teacher Has Salary') . '</div>';
                        redirect($msg,'back');
                    }
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">'.lang('You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect to login page
            }
        }elseif($do == 'Delete'){//delete page
            echo '<h1 class="text-center">'.lang('Delete ٍSalary').'</h1>';
            echo '<div class="container">';
            //receive student id and check that it's number and get it's numeric value
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) : 0;
            $check = checkitem('TeacherID','teachers',$teacherid);//cehck that this id exist
            if($check == 1){//if == 1 this mean this id is exist execute query
                $stmt = $con->prepare("DELETE FROM salary WHERE TeacherID =?");
                $stmt->execute(array($teacherid));
                $msg = '<div class="alert alert-success">'.$stmt->rowCount() . ' ' . lang('Row Deleted'). '</div>';
                redirect($msg,'back');//redirect message to previous page
            }else{//if not = 1 this mean that it's not exist show this message
                $msg = '<div class="alert alert-danger">'.lang('Sorry There Is No Such ID').'</div>';
                redirect($msg,'back');//redirect user to previous page
            }
            echo '</div>';
        }elseif($do == 'TotalSalary'){
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) : 0;
            $stmt2 = $con->prepare("SELECT COUNT(absence.Date) 
                                            as abs,teachers.TeacherID,teachers.TeacherName 
                                            FROM teachers INNER JOIN absence 
                                            ON teachers.TeacherID = absence.TeacherID 
                                            WHERE teachers.TeacherID = ?");
            $stmt2->execute(array($teacherid));
            $rows = $stmt2->fetchAll();
            ?>
            <h1 class="text-center"><?php echo lang('Total Salary')?></h1>
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td><?php echo lang('Teacher ID')?></td>
                            <td><?php echo lang('Teacher Name')?></td>
                            <td><?php echo lang('Total Salary')?></td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                            echo '<td>' . $row['TeacherID'] . '</td>';//student id
                            echo '<td>' . $row['TeacherName'] . '</td>';//student name
                            echo '<td>';
                                echo calc($teacherid);
                            echo '</td>';//class name
                            echo '</tr>';
                        }
                        ?>
                    </table>
                    <!-- end main table -->
                </div>
                <!-- end table responsive -->
            </div>

            <?php
        }
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if there is no session was set redirect to login page
        exit();//stop executing any script
    }
    ob_end_flush();// after page end go output