<?php
    ob_start();//start output buffering store all output in memory
    session_start();//start session
    if(isset($_SESSION['username'])){//check that session is set with this username
        $pagetitle = 'Branch'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//if there is get request with do store it in do var
        if($do == 'Manage'){ // manage page
            $stmt = $con->prepare("SELECT * FROM branch ORDER BY BranchID ASC");//select all branch from table branch
            $stmt->execute();//execute query
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){//if row not empty show all data
            ?>
            <h1 class="text-center"><?php echo lang('Manage Branch')?></h1>
                <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="table text-center table-bordered main-table">
                        <tr>
                            <td><?php echo lang('Branch ID');?></td>
                            <td><?php echo lang('Branch Name');?></td>
                            <td><?php echo lang('Control');?></td>
                        </tr>
                            <?php
                                foreach($rows as $row){//foreach loop to show all data
                                    echo '<tr>';
                                    echo '<td>' . $row['BranchID'] . '</td>';//branch id
                                    echo '<td>' . $row['B_Name']   . '</td>';//branch name
                                    echo '<td>
                                            <a href="?do=Edit&bid='.$row['BranchID'].'" class="btn btn-success">
                                            <span class="glyphicon glyphicon-edit"> '.lang('Edit').'</a>
                                            <a href="?do=Delete&bid='.$row['BranchID'].'" class="confirm btn btn-danger">
                                            <span class="glyphicon glyphicon-remove"> '.lang('Delete').'</a>
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
            }else{//if rows is empty show this message
                echo '<div class="container">
                <div class="alert alert-info nice-message">'
                    .lang('Sorry There Is No Recored To Show').
                    '</div>
                </div>';
            }?>
            <div class="container"><a href="?do=Add" class="add btn btn-lg btn-primary">
                <span class="glyphicon glyphicon-plus"></span> <?php echo lang('Add New Branch')?></a>
                <form method="post" action="branch_report.php" class="pdf form-horizontal">
                    <input type="submit" name="create_pdf" class="btn btn-primary btn-lg add" value="<?php echo lang('Create PDF')?>" />
                </form>

            </div>
        <?php
        }elseif($do == 'Edit'){ // edit page
            // check that coming id is number
            $b_id = isset($_GET['bid']) & is_numeric($_GET['bid']) ? intval($_GET['bid']) : 0;
            $stmt = $con->prepare("SELECT * FROM branch WHERE BranchID = ?");//select data from table
            $stmt->execute(array($b_id));//execute query
            $row = $stmt->fetch();//fetch all data matched
            $count = $stmt->rowCount();//check for number of row matched
            if($count>0){//if count large than 0
            ?>
            <h1 class="text-center"><?php echo lang('Edit Branch');?></h1>
                <!-- start container -->
            <div class="container">
                <!-- start edit form -->
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <!-- start branch id -->
                    <input type="hidden" name="bid" value="<?php echo $row['BranchID'];?>"/>
                    <!-- end branch id -->
                    <!-- start branch name -->
                    <div class="form-group form-group-lg">
                        <label class="control-label col-sm-2"><?php echo lang('Branch Name');?></label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="B_Name" required="required"
                                   class="form-control" placeholder="<?php echo lang('Branch Name')?>"
                                   autocomplete="off" value="<?php echo $row['B_Name'];?>"/>
                        </div>
                    </div>
                    <!-- end branch name -->
                    <!-- start submit button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('Update');?>"/>
                        </div>
                    </div>
                    <!-- end submit button -->
                </form>
                <!-- end edit form -->
            </div>
                <!-- end container -->
        <?php
            }else{//if count not large than 0 show this message
                $msg = '<div class="alert alert-danger">'. lang("Sorry There Is No Such ID").'</div>';
                redirect($msg);//redirect usert to login page
            }
        }elseif($do == 'Update'){//update page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){// check that user coming through post request
                echo '<h1 class="text-center">'.lang('Update Branch').'</h1>';
                echo '<div class="container">';
                $branchid = $_POST['bid'];//redeive branch id
                $branchname= $_POST['B_Name'];//receive branch name
                $formerror = array();
                if(empty($branchname)){
                    $formerror[] = lang('Sorry You Can Not Leave Branch Name Empty');
                }
                if(strlen($branchname)<4){
                    $formerror[] = lang('Sorry Branch Name Can Not Be Less Than 4 Character');
                }
                foreach($formerror as $error){
                    $msg = '<div class="alert alert-danger">' . $error . '</div>';
                    redirect($msg,'back');
                }
                if(empty($formerror)){
                    $stmt = $con->prepare("UPDATE branch SET B_Name = ? WHERE BranchID= ?");//execute query
                    $stmt->execute(array($branchname,$branchid));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Update') . '</div>';
                    redirect($msg,'back');//redirect user to previous page
                }
                echo '</div>';
            }else{//if not coming using post request show this message
                $msg = '<div class="alert alert-danger">'.lang('Sorry You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect to login page
            }
        }elseif($do == 'Add'){// add branch page
            ?>
                <h1 class="text-center"><?php echo lang('Add Branch');?></h1>
            <!-- start container -->
                <div class="container">
                    <!-- start add form -->
                    <form class="form-horizontal"  action="?do=Insert" method="POST">
                        <!-- start branch name -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label"><?php echo lang('Branch Name');?></label>
                            <div class="col-md-6 col-sm-1o">
                                <input type="text" name="B_Name" required="required"
                                       class="form-control" placeholder="<?php echo lang('Branch Name')?>" autocomplete="off"/>
                            </div>
                        </div>
                        <!-- end branch name -->
                        <!-- start submit button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" value="<?php echo lang('Save')?>" class="btn btn-primary btn-lg"/>
                        </div>
                    </div>
                        <!-- end submit button -->
                    </form>
                    <!-- end add form -->
                </div>
            <!-- end container -->
            <?php
        }elseif($do == 'Insert'){// insert page
            if($_SERVER['REQUEST_METHOD'] == 'POST'){// check that user coming through post request
                echo '<h1 class="text-center">'.lang('Insert Branch').'</h1>';
                echo '<div class="container">';
                $br_name = $_POST['B_Name'];//receive branch name
                if(!empty($br_name)){//if branch name not empty execute query
                    $check = checkitem('B_Name','branch',$br_name);
                    if($check == 1){//check if branch name is exist
                        $msg = '<div class="alert alert-danger">'.lang('Sorry This Name Is Exist').'</div>';
                        redirect($msg,'back');//redirect user to preious page
                    }else{//if not exist execute query
                        $stmt = $con->prepare("INSERT INTO branch (B_Name) VALUES(:zname)");
                        $stmt->execute(array('zname'=>$br_name));
                        $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' .lang('Row Inserted'). '</div>';
                        redirect($msg,'back');//redirect user to previous page
                    }
                }else{//if branch name is empty show this message
                    $msg = '<div class="alert alert-danger">'.lang('Sorry You Can Not Leave Branch Name Empty').'</div>';
                    redirect($msg,'back');//redirect to previous page
                }
                echo '</div>';
            }else{//if user not coming to this page usin post request
                $msg = '<div class="error alert alert-danger">'.lang('Sorry You Can Not Browse This Page Directly').'</div>';
                redirect($msg);//redirect user to login page
            }
        }elseif($do == 'Delete'){//delete page
                echo '<h1 class="text-center">'.lang('Delete Branch').'</h1>';
                echo '<div class="container">';
                //receive branch id and check that it's number and get it's numeric value
                $branch = isset($_GET['bid']) && is_numeric($_GET['bid']) ? intval($_GET['bid']) : 0;
                $check = checkitem('BranchID','branch',$branch);//cehck that this id exist
                if($check==1){//if exist execute query
                    $stmt1 = $con->prepare("SET FOREIGN_KEY_CHECKS = 0;");
                    $stmt1->execute();
                    $stmt = $con->prepare("DELETE FROM branch WHERE BranchID = ?");
                    $stmt->execute(array($branch));
                    $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Row Deleted') . '</div>';
                    redirect($msg,'back');//rediret to preious page
                    $stmt2 = $con->prepare("SET FOREIGN_KEY_CHECKS = 1;");
                    $stmt2->execute();
                }else{
                    $msg = '<div class="alert alert-danger">'.lang('Sorry There Is No Such ID').'</div>';
                    redirect($msg,'back');
                }
                echo '</div>';
        }
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if session not set redirect to login page
        exit();//stop executing any script
    }
    ob_end_flush();//after page end go ouput