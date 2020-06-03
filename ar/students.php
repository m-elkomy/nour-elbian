<?php
    ob_start();//start output buffering to store all output in memory
    session_start();//start session
    if(isset($_SESSION['username'])){//check if session register with user name show page
        $pagetitle = 'Student'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//if there is get request with do store request in do var
        if($do == 'Manage'){//manage page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT students.*,
                                            branch.B_Name,
                                            classes.ClassName 
                                                FROM 
                                            students 
                                                INNER JOIN 
                                            branch 
                                                ON 
                                            students.BranchID = branch.BranchID 
                                                INNER JOIN 
                                            classes 
                                                ON 
                                            students.ClassID = classes.ClassID ORDER BY StudentID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
            ?>
            <h1 class="text-center">Manage Student</h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Student Name</td>
                            <td>Class Name</td>
                            <td>Branch Name</td>
                            <td>Pay Or Not</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['StudentID'] . '</td>';//student id
                                echo '<td>' . $row['StudentName'] . '</td>';//student name
                                echo '<td>' . $row['ClassName'] . '</td>';//class name
                                echo '<td>' . $row['B_Name'] . '</td>';//branch name
                                echo '<td>' ;
                                        if($row['Paid'] == 1){echo 'Pay';}else{echo 'Not Pay';};
                                echo  '</td>';//pay or not pay
                                echo '<td>
                                      <a class="btn btn-success" href="?do=Edit&sid='.$row['StudentID'].'"><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href="?do=Delete&sid='.$row['StudentID'].'"><span class="glyphicon glyphicon-remove"></span> Delete</a>  
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
                <div class="alert alert-info nice-message">Sorry There Is No Recored To Show</div>
                </div>';
            }
            echo '<div class="container"><a href="?do=Add" class="btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add New Student</a></div>';
        }elseif($do == 'Edit'){//edit page
            //receive student id and check that is number and store it's value in student id var
            $studentid = isset($_GET['sid']) && is_numeric($_GET['sid']) ? intval($_GET['sid']) : 0;
            //select student data with this id
            $stmt = $con->prepare("SELECT students.*,
                                            branch.B_Name,classes.ClassName 
                                                FROM 
                                            students 
                                                INNER JOIN  
                                            branch 
                                                ON 
                                            students.BranchID = branch.BranchID 
                                                INNER JOIN 
                                            classes 
                                                ON 
                                            students.ClassID = classes.ClassID 
                                                WHERE 
                                            students.StudentID = ?");
            $stmt->execute(array($studentid));//execute query
            $row = $stmt->fetch();//fetch data
            $count = $stmt->rowCount();//check for number of row matched query
            if($count > 0){//if count large than 0
            ?>
                <h1 class="text-center">Edit Member</h1>
                <!-- start container -->
                <div class="container">
                    <!-- start edit form -->
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <!-- start student id -->
                        <input type="hidden" name="s_id" value="<?php echo $studentid;?>"/>
                        <!-- end student id -->
                        <!-- start student name -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Student Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="studentname" required="required" class="form-control"
                                       value="<?php echo $row['StudentName']?>"
                                       placeholder="Student Name" autocomplete="off"/>
                            </div>
                        </div>
                        <!-- end student name -->
                        <!-- start branch name -->
                        <div class="form-group form-group-lg">
                            <label class="control-label col-sm-2">Branch Name</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="branchid" id="selec1">
                                    <option value="0">........</option>
                                    <?php
                                    //select all data from branch table
                                    $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC ");
                                    $stmt->execute();//execute query
                                    $branchs = $stmt->fetchAll();//fetch all data
                                    foreach($branchs as $branch){//foreach loopt to show all data
                                        echo '<option value="'.$branch['BranchID'].'"';
                                        if($row['BranchID'] == $branch['BranchID']){echo 'selected';}
                                        echo '>' . $branch['B_Name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- end branch name -->
                        <!-- start class name -->
                        <div class="form-group form-group-lg">
                            <label class="control-label col-sm-2">Class Name</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="classid" id="select2">
                                    <option value="0">........</option>
                                    <?php
                                    //select all data from class table
                                    $stmt = $con->prepare("SELECT * FROM classes ORDER BY ClassID ASC ");
                                    $stmt->execute();//execute query
                                    $classes = $stmt->fetchAll();//fetch all data
                                    foreach($classes as $class){//foreach loopt to show all data
                                        echo '<option value="'.$class['ClassID'].'"';
                                        if($row['ClassID'] == $class['ClassID']){echo 'selected';}
                                        echo '>' . $class['ClassName'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- end branch name -->
                        <!-- start pay or not pay -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Paid</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="pay-yes" type="radio" name="pay"
                                           value="1" <?php if($row['Paid'] == 1) echo 'checked'?>/>
                                    <label for="pay-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="pay-no" type="radio" name="pay"
                                           value="0" <?php if($row['Paid'] == 0) echo 'checked'?>/>
                                    <label for="pay-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end pay or not -->
                        <!-- start submit button -->
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="submit" class="btn btn-primary btn-lg" value="Save"/>
                            </div>
                        </div>
                        <!-- end submit button -->
                    </form>
                    <!-- end edit form -->
                </div>
                <!-- end container -->
            <?php
            }else{//if count not large than 0 show this error message
                $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
                redirect($msg);//redirect message
            }
        }elseif($do == 'Update'){//update page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that user coming through post request
                echo '<h1 class="text-center">Update Student</h1>';
                echo'<div class="container">';
                $studentname = $_POST['studentname'];//receive student name
                $studentid   = $_POST['s_id'];//receive student id
                $branchid = $_POST['branchid'];//receive branch id
                $classid  = $_POST['classid'];//receive class id
                $pay      = $_POST['pay'];//receive pay or not
                $formerror = array();//set formerror array to validate form and store all error in form
                if(empty($studentname)){//if student name is empty store this message in formerror array
                    $formerror[] = 'Student Name Can Not Be Empty';
                }
                if($branchid == 0){//if branch id is empty store this message in formerror array
                    $formerror[] = 'Branch Name Can Not Be Empty';
                }
                if($classid == 0){//if class id is empty store this message in formerror array
                    $formerror[] = 'Class Name Can Not Be Empty';
                }
                foreach ($formerror as $error){//foreach loop to show all error message stored in formerror array
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror is empty  execute query
                    $stmt = $con->prepare("UPDATE students SET StudentName=? , 
                                                        BranchID = ? ,ClassID=?, Paid =? 
                                                        WHERE StudentID=?");
                    $stmt->execute(array($studentname,$branchid,$classid,$pay,$studentid));//execute query
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated</div>';//success msg
                    redirect($msg,'back');//redirect user to previous page
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
                redirect($msg);//redirect message
            }
        }elseif($do == 'Add'){//add page
            ?>
            <h1 class="text-center">Add Students</h1>
            <!-- start container -->
            <div class="container">
                <!-- start add form -->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start student name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Student Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="studentname" required="required"
                                   class="form-control" placeholder="Student Name" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- end student name -->
                    <!-- start branch name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Branch Name</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="branchid" onchange="showCustomer(this.value)">
                                <option value="0">........</option>
                                <?php
                                $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC ");
                                $stmt->execute();
                                $branchs = $stmt->fetchAll();
                                foreach($branchs as $branch){
                                    echo '<option value="'.$branch['BranchID'].'">' . $branch['B_Name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- end branch name -->
                    <!-- start class name -->
                    <div class="form-group form-group-lg" id="classname">

                    </div>
                    <!-- end class name -->
                    <!-- start pay or not -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Paid</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="pay-yes" type="radio" name="pay" value="1" checked/>
                                <label for="pay-yes">Yes</label>
                            </div>
                            <div>
                                <input id="pay-no" type="radio" name="pay" value="0"/>
                                <label for="pay-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!-- end pay or not -->
                    <!-- start submit button -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Save"/>
                        </div>
                    </div>
                    <!-- end submit button -->
                </form>
                <!-- end form -->
            </div>
            <!-- end container -->
            <?php
        }elseif($do == 'Insert'){//insert page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that use coming through pos request
                echo '<h1 class="text-center">Insert Student</h1>';
                echo '<div class="container">';
                $studentname = $_POST['studentname'];//receive student name
                $branchid  = $_POST['branchid'];//receive branch id
                $classid   = $_POST['classid'];//receive class id
                $paid       = $_POST['pay'];//receive pay or not
                $formerror = array();//set formerror array to validate form and store all error message in it
                if(empty($studentname)){//if student name is empty store this message in formerror array
                    $formerror[] = 'Student Name Can Not Be Empty';
                }
                if($branchid == 0 ){//if branch id is empty store this message in formerror array
                    $formerror[] = 'Brnach Name Can Not Be Empty';
                }
                if($classid == 0){//if classid is empty store this messge in formerror array
                    $formerror[] = 'Class Name Can Not Be Empty';
                }
                foreach ($formerror as $error){//foreach loop to show all error message
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror is empty execute query
                    $check = checkitem('StudentName','students',$studentname);//check that user is not exist
                    if($check == 0){//if check == 0 this mean that this name not exist execute query
                        $stmt = $con->prepare("INSERT INTO 
                                                                students(StudentName,BranchID,ClassID,Paid) 
                                                                VALUES(:zname,:zid,:zclass,:zpay)");
                        $stmt->execute(array('zname'=>$studentname,'zid'=>$branchid,'zclass'=>$classid,'zpay'=>$paid));
                        $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Inserted</div>';
                        redirect($msg,'back');//redirect user to previous page
                    }else{//if check not = 0 this mean that this user is exist show this message
                        $msg = '<div class="alert alert-danger">Sorry This Student Name Is Exist</div>';
                        redirect($msg,'back');//redirect user to previous page
                    }
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">You Can Not Browse This Page Directly</div>';
                redirect($msg);//redirect to login page
            }
        }elseif($do == 'Delete'){//delete page
            echo '<h1 class="text-center">Delete Student</h1>';
            echo '<div class="container">';
            //receive student id and check that it's number and get it's numeric value
            $studentid = isset($_GET['sid']) && is_numeric($_GET['sid']) ? intval($_GET['sid']) : 0;
            $check = checkitem('StudentID','students',$studentid);//cehck that this id exist
            if($check == 1){//if == 1 this mean this id is exist execute query
                $stmt = $con->prepare("DELETE FROM students WHERE StudentID =?");
                $stmt->execute(array($studentid));
                $msg = '<div class="alert alert-success">'.$stmt->rowCount() .' Row Deleted</div>';
                redirect($msg,'back');//redirect message to previous page
            }else{//if not = 1 this mean that it's not exist show this message
                $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
                redirect($msg,'back');//redirect user to previous page
            }
            echo '</div>';
        }
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if there is no session was set redirect to login page
        exit();//stop executing any script
    }
    ob_end_flush();// after page end go output