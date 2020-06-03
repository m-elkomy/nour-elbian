<?php
    ob_start();//output buffering start save all output in memory except header
    session_start();//start session
    if(isset($_SESSION['username'])){//if session is register with username
        $pagetitle = 'Classes'; // set page title
        include 'init.php';// include initialize page
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//if there is get request with do register it in do var
        if($do == 'Manage'){ // manage page
            //select all classes and related data in other table
            $stmt = $con->prepare("SELECT 
                                                classes.*, branch.B_Name 
                                            FROM classes INNER JOIN branch 
                                            ON classes.BranchID = branch.BranchID ORDER BY ClassID ASC");
            $stmt->execute();//execute the query
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){//if rows not empty show all data
            ?>
            <h1 class="text-center">Manage Classes</h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="main-table table table-bordered text-center">
                        <!-- start table row -->
                        <tr>
                            <!-- start table cell -->
                            <td>ID</td>
                            <td>Class Name</td>
                            <td>Branch Name</td>
                            <td>Control</td>
                            <!-- end table cell -->
                        </tr>
                        <!-- end table row -->
                        <?php
                            foreach($rows as $row){//foreach loop to show all data in table cell
                                echo '<tr>';
                                    echo '<td>' . $row['ClassID'] . '</td>';//show classid
                                    echo '<td>' . $row['ClassName'] .'</td>';//show class name
                                    echo '<td>' . $row['B_Name'] . '</td>';//show branch name
                                    echo '<td>
                                              <a class="btn btn-success" href="?do=Edit&cid='.$row['ClassID'].'">
                                              <span class="glyphicon glyphicon-edit"></span> Edit</a>
                                              <a class="btn btn-danger confirm" href="?do=Delete&cid='.$row['ClassID'].'">
                                              <span class="glyphicon glyphicon-remove"></span> Delete</a>
                                            </td>';//control buttons
                                echo '</tr>';
                            }
                        ?>
                    </table>
                    <!-- end main table -->
                </div>
                <!-- end responsive table -->
            </div>
            <!-- end container -->
        <?php
            }else{
                //if rows is empty show this message
                echo '<div class="container">
                       <div class="nice-message alert alert-info">There Is No Recored To Show</div>
                    </div>';
            }
            echo '<div class="container"><a href="?do=Add" class="btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span>
                    Add New Class</a>';
        }elseif($do == 'Edit'){//edit page
        //get class id and check if class id is number and if there is string get int value
        $cid = isset($_GET['cid']) && is_numeric($_GET['cid']) ? intval($_GET['cid']) : 0;
        //select class data from class table where class id = cid
        $stmt = $con->prepare("SELECT 
                                            classes.*,branch.* 
                                        FROM classes INNER JOIN 
                                            branch ON 
                                            classes.BranchID = branch.BranchID 
                                        WHERE classes.ClassID = ?");
        $stmt->execute(array($cid));//execute the query
        $rows = $stmt->fetch();//fetch the data
        $count = $stmt->rowCount();//check number of row matched the query
        if($count > 0){//if number of row is more than 0
        ?>
            <h1 class="text-center">Edit Class</h1>
            <!-- start container -->
            <div class="container">
                <!-- start edit form -->
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <!-- start class id -->
                    <input type="hidden" name="cid" value="<?php echo $cid?>"/>
                    <!-- end class id -->
                    <!-- start class name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Class Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="cname"
                                   placeholder="Class Name" autocomplete="off"
                                   value="<?php echo $rows['ClassName'];?>" required="required"/>
                        </div>
                    </div>
                    <!-- end class name -->
                    <!-- start branch name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2">Branch Name</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="branchid">
                                <option value="0">........</option>
                                <?php
                                    //select all branch form branch table
                                    $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC ");
                                    $stmt->execute();//execute the query
                                    $branchs = $stmt->fetchAll();//fetch the data
                                    foreach($branchs as $branch){//foreach loop to show data
                                        //check for stored value in db to show it
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
                <!-- end form -->
            </div>
            <!-- end container -->
        <?php
        }else{
            //else if count = 0 this mean that this id not exist in database show error message
            $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
            redirect($msg);
        }
        }elseif($do == 'Update'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){//check that user coming through post request
                echo '<h1 class="text-center">Update Class</h1>';
                echo '<div class="container">';//start container
                $c_id = $_POST['cid']; //receive class id in cid va
                $cname = $_POST['cname'];//receive class name in cname var
                $branch = $_POST['branchid'];//receive branceid in branch var
                $formerror = array();//set formerror array to store all form error in it
                if(empty($cname)){//if class name is empty store this message in array
                    $formerror[] = 'Class Name Can Not Be Empty';
                }
                if($branch == 0){//if branch name is empty store this message in array
                    $formerror[] = 'Branch Name Can Not Be Empty';
                }
                foreach ($formerror as $error){//make foreach loop to show all data
                    $msg = '<div class="alert alert-danger">' . $error . ' </div>';
                    redirect($msg,'back');//redirect to previous page
                }
                if(empty($formerror)){//if formerror array is empty
                    //update data in class table
                    $stmt = $con->prepare("UPDATE classes SET ClassName= ?, BranchID=? WHERE ClassID=?");
                    $stmt->execute(array($cname,$branch,$c_id));//execute the query
                    //success message
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Updated</div>';
                    redirect($msg,'back');//redirect to previous page
                }
                echo '</div>';
            }else{
                //if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Direclty</div>';
                redirect($msg);//redirect the user to login page
            }
        }elseif($do == 'Add'){//add page ?>
            <h1 class="text-center">Add Class</h1>
            <!-- start container -->
            <div class="container">
                <!-- start add class form -->
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!-- start class name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Class Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="cname" class="form-control"
                                   placeholder="Class Name" autocomplete="off" required="required"/>
                        </div>
                    </div>
                    <!-- end class name -->
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
                    <div class="form-group">
                        <div class="col-sm-10 col-md-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="Save"/>
                        </div>
                    </div>
                    <!-- end submit button -->
                </form>
                <!-- end insert form -->
            </div>
            <!-- end container -->
        <?php
        }elseif($do == 'Insert'){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){// check that user coming through post request
                echo '<h1 class="text-center">Insert Class</h1>';
                echo '<div class="container">';//start the container
                $classname = $_POST['cname'];//receive class name in classname var
                $bname     = $_POST['branch'];//receive branch name in branch var
                $formerror = array();//make array for store form error
                if(empty($classname)){//if class name is empty store this message in array
                    $formerror[] = 'Class Name Can Not Be Empty';
                }
                if($bname == 0){//if brnach name is empty store this message in array
                    $formerror[] = 'Branch Name Can Not Be Empty';
                }
                foreach ($formerror as $error){//foreach loop to show all error message
                    $msg = '<div class="alert alert-danger">' . $error . ' </div>'; //message show inside this div
                    redirect($msg,'back');//redirect the user to preivous page
                }
                if(empty($formerror)){//if formerror array is empty
                    $check = checkitem('ClassName','classes',$classname);//check that this class name not exist before
                    if($check == 0){//if not exist
                        //insert it to class table
                        $stmt = $con->prepare("INSERT INTO classes(ClassName,BranchID) VALUES(:zname,:zid)");
                        $stmt->execute(array('zname'=>$classname,'zid'=>$bname));//execute the query
                        //success message to show
                        $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Inserted </div>';
                        redirect($msg,'back');//redirect the user to preivous page
                    }else{//if the class name is exist show this error message
                        $msg = '<div class="alert alert-danger">Sorry This Class Name Is Exist</div>';
                        redirect($msg,'back');//redirect the user to preious page
                    }
                }
                echo '</div>';
            }else{//if user not coming to this page using post request show this message
                $msg = '<div class="alert alert-danger">Sorry You Can Not Browse This Page Directly</div>';
                redirect($msg);//redirect the user to login page
            }
        }elseif($do == 'Delete'){
            echo '<h1 class="text-center">Delete Class</h1>';
            echo '<div class="container">';//start the container
            //receive the class user want to delete
            $cid = isset($_GET['cid']) && is_numeric($_GET['cid']) ? intval($_GET['cid']) :0;
            $check = checkitem('ClassID','classes',$cid);//check that this id is exist in class table
            if($check == 1){//if exist execute the query
                $stmt = $con->prepare("DELETE FROM classes WHERE ClassID = ?");
                $stmt->execute(array($cid));//execute query
                //show this message
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Row Deleted</div>';
                redirect($msg,'back');//redirect the user back to previous page
            }else{//if this id not exist show this message
                $msg = '<div class="alert alert-danger">Sorry There Is No Such ID</div>';
                redirect($msg);//redirect user to login page
            }
            echo '</div>';
        }

        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if there is no session redirect user to login page
        exit();//stop running any scirpt
    }
    ob_end_flush();//after page end go output