<?php
    ob_start(); //start output buffering store all output in memory
    session_start();//start the session
    if(isset($_SESSION['username'])){//check if there is session with this username
        $pagetitle = 'Teachers'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//check if there is get request with do
        if($do == 'Manage'){//manage page
            //select all data from teacher table
            $stmt = $con->prepare("SELECT teachers.* ,branch.B_Name 
                                            FROM teachers INNER JOIN branch 
                                            ON teachers.BranchID = branch.BranchID ORDER BY TeacherID ASC");
            $stmt->execute();//execute the query
            $rows = $stmt->fetchAll();//fethc all data from database
            if(!empty($rows)){//if rows not empty show manage page
            ?>
            <h1 class="text-center">Manage Teachers</h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="table table-bordered main-table text-center">
                        <tr>
                            <td>ID</td>
                            <td>Teacher Name</td>
                            <td>Branch Name</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//for loop to show all data in table cell
                            echo '<tr>';
                                echo '<td>' . $row['TeacherID'] . '</td>';//show teacher id
                                echo '<td>' . $row['TeacherName'] . '</td>';//show teacher name
                                echo '<td>' . $row['B_Name'] . '</td>';//show branch name
                                echo '<td>
                                    <a href="?do=Edit&tid='.$row['TeacherID'].'" class="btn btn-success">
                                    <span class="glyphicon glyphicon-edit"></span> Edit</a>
                                    <a href="?do=Delete&tid='.$row['TeacherID'].'" class="confirm btn btn-danger">
                                    <span class="confirm glyphicon glyphicon-remove"></span> Delete</a>
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
                    <div class="alert alert-info nice-message">Sorry There Is No Recored To Show</div>
                    </div>';
            }
            echo '<div class="container"><a href="?do=Add" class="btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add New Teahcer</a></div>';
        }elseif($do == 'Edit'){//edit page
            //if there is get request with tid get it and check if it's a number and store in teacher id
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) : 0;
            //select data from teacher table based on id selected
            $stmt = $con->prepare("SELECT teachers.*, branch.* 
                                            FROM teachers INNER JOIN branch 
                                            ON teachers.BranchID = branch.BranchID 
                                            WHERE teachers.TeacherID = ?");
            $stmt->execute(array($teacherid));//execute the query
            $rows = $stmt->fetch();//fetch data from db
            $count = $stmt->rowCount();//check number of rows matched the query
            if($count>0){//if number of row large than 0
                ?>
                    <h1 class="text-center">Edit Member</h1>
                    <!-- start the container -->
                    <div class="container">
                        <!-- start update form -->
                        <form class="form-horizontal" action="?do=Update" method="POST">
                            <!-- start teacher id -->
                            <input type="hidden" name="teacher_id" value="<?php echo $teacherid?>"/>
                            <!-- end teacher id -->
                            <!-- start teacher name -->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Teacher Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" class="form-control" required="required"
                                           name="teachername" value="<?php echo $rows['TeacherName'];?>"
                                           placeholder="Teacher Name" autocomplete="off"/>
                                </div>
                            </div>
                            <!-- end teacher name -->
                            <!-- start branch name -->
                            <div class="form-group form-group-lg">
                                <label class="control-label col-sm-2">Branch Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="branchid">
                                        <option value="0">........</option>
                                        <?php
                                        $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC ");
                                        $stmt->execute();
                                        $branchs = $stmt->fetchAll();
                                        foreach($branchs as $branch){
                                            echo '<option value="'.$branch['BranchID'].'"';
                                            if($rows['BranchID'] == $branch['BranchID']){echo 'selected';}
                                            echo '>' . $branch['B_Name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- end branch name -->
                            <!-- start submit button -->
                            <div class="form-group form-group-lg">
                                <div class="col-sm-10 col-md-offset-2">
                                    <input type="submit" value="Update" class="btn btn-primary btn-lg"/>
                                </div>
                            </div>
                            <!-- end submit button -->
                        </form>
                        <!-- end update form -->
                    </div>
                <!-- end container -->
                <?php
            }else{//if row not equal 0 show this message
                $msg = '<div class="alert error alert-danger">Sorry There Is No Such ID</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Update'){//update page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//CEHCK that user coming through post request
                echo '<h1 class="text-center">Update Teacher</h1>';
                echo '<div class="container">';
                $teacherid = $_POST['teacher_id'];//receive teacher id from form and store it in var
                $teachername = $_POST['teachername'];//receive teacher name and store it in var
                $branchid = $_POST['branchid'];//receive branch id and store it in var
                $formerror = array();//set formerror array to validate form and store all error
                if(empty($teachername)){//if teacher name is empty store this message in form erorr
                    $formerror[] = 'Teacher Name Can Not Be Empty';
                }
                if($branchid == 0){//if branchid empty show this messgae
                    $formerror[] = 'Branch Name Can Not Be Empty';
                }
                foreach($formerror as $error){//foreach loop to show all error message
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror array is empty execute this query
                    $stmt = $con->prepare("UPDATE teachers SET TeacherName=? ,BranchID=? WHERE TeacherID=?");
                    $stmt->execute(array($teachername,$branchid,$teacherid));//execute query and show success message
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated</div>';
                    redirect($msg,'back');//redirect user to previuos page
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Add'){//add teachers page
            ?>
            <h1 class="text-center">Add Teacher</h1>
            <!-- start container -->
            <div class="container">
                <!-- start add form -->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start teacher name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Teacher Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="teachername" required="required"
                                   class="form-control" autocomplete="off" placeholder="Teacher Name"/>
                        </div>
                    </div>
                    <!-- end teacher name -->
                    <!-- start branch name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Branch Name</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="branch">
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
                    <!-- end branch name -->
                    <!-- start submit button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-10 col-md-offset-2">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg"/>
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
                echo '<h1 class="text-center">Insert Teacher</h1>';
                echo '<div class="container">';
                $teachername = $_POST['teachername'];//receive teacher name and store it in var
                $branchnameid = $_POST['branch'];//receive branchid and store it in var
                $formerror = array();//set formerror array to validate form and store all error in it
                if(empty($teachername)){//if teacher name is empty store this message in array
                    $formerror[] = 'Teacher Name Can Not Be Empty';
                }
                if($branchnameid == 0){//if branch id is empty store this message in array
                    $formerror[] = 'Branch Name Can Not Be Empty';
                }
                foreach ($formerror as $error){//foreach loop to show all message
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                if(empty($formerror)){//if formerror is empty execute query
                    //check that teacher no exist before
                    $check = checkitem('TeacherName','teachers',$teachername);
                    if($check == 1){//if check == 1 this mean that teacher is exist show this message
                        $msg = '<div class="alert alert-danger">Sorry This Teacher Name is Exist</div>';
                        redirect($msg,'back');//redirect to previous page
                    }else{//else teacher name not exist execute query
                    $stmt = $con->prepare("INSERT INTO teachers (TeacherName,BranchID) VALUES(:zname,:zid)");
                    $stmt->execute(array('zname'=>$teachername,'zid'=>$branchnameid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Inserted</div>';
                    redirect($msg,'back');//redirect to previous page
                    }
                }
                echo '</div>';
            }else{//if user not coming throgh post request
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Delete'){//delete page
            echo '<h1 class="text-center">Delete Teacher</h1>';
            echo '<div class="container">';
            //get teacher id and store it in var
            $teacherid = isset($_GET['tid']) && is_numeric($_GET['tid']) ? intval($_GET['tid']) :0;
            $check = checkitem('TeacherID','teachers',$teacherid);//check that this id exist
            if($check == 1){//if exist delete it
                $stmt = $con->prepare("DELETE FROM teachers WHERE TeacherID =?");
                $stmt->execute(array($teacherid));
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Deleted</div>';
                redirect($msg,'back');//redirect user to previous page
            }else{//if not = 1 show this message
                $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
                redirect($msg);//redirect user to login page
            }
            echo '</div>';
        }

        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//redirect user to login page if session not exist
        exit();//stop executing any script
    }
    ob_end_flush();//after page end go output