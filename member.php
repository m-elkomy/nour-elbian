<?php
    ob_start(); //start output buffering store all output in memory
    session_start();//start the session
    if(isset($_SESSION['username'])){// if there is session registerd with username
        $pagetitle = 'المستخدمين'; // set page title
        include 'init.php';//include initialize file
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';//if there is get request with do store get in do var
    if($do=='Manage'){// manage page
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 ORDER BY UserID ASC");//select all user form user table
        $stmt->execute();//execute the query
        $rows = $stmt->fetchAll();//fetch all data from user table
        if(!empty($rows)){//if rows not empty show all data in manage page
        ?>
        <h1 class="text-center"><?php echo lang('Manage Members')?></h1>
        <!-- start container -->
        <div class="container">
            <!-- start table responvie -->
            <div class="table-responsive">
                <!--- start main table -->
                <table class="main-table table text-center table-bordered">
                    <tr>
                        <td><?php echo lang('User ID')?></td>
                        <td><?php echo lang('User Name')?></td>
                        <td><?php echo lang('Full Name')?></td>
                        <td><?php echo lang('Email')?></td>
                        <td><?php echo lang('Registered Date')?></td>
                        <td><?php echo lang('Control')?></td>
                    </tr>
                    <?php
                        foreach($rows as $row){//foreach loop to show all data in table cell
                            echo '<tr>';
                                echo '<td>' . $row['UserID']   . '</td>';//show user id
                                echo '<td>' . $row['UserName'] . '</td>';//show user name
                                echo '<td>' . $row['FullName'] . '</td>';//show user full name
                                echo '<td>' . $row['Email']    . '</td>';//show user email
                                echo '<td>' .$row['Date']      . '</td>';//show user registration date
                                echo '<td>
                                        <a href="member.php?do=Edit&id='.$row['UserID'].'" class="btn btn-success">
                                        <span class="glyphicon glyphicon-edit"></span> '.lang('Edit').'</a>
                                        <a href="member.php?do=Delete&id='.$row["UserID"].'" class="confirm btn btn-danger">
                                        <span class="glyphicon glyphicon-remove"></span> '.lang('Delete').'</a>
                                    </td>';//control buttons
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
        }else{//if rows empty show this message
            echo '<div class="container">
                    <div class="alert alert-info nice-message">'.lang('Sorry There Is No Recored To Show').'</div>
                  </div>';
        }
        echo '<div class="container"><a href="?do=Add" class="add btn btn-lg btn-primary">
                    <span class="glyphicon glyphicon-plus"></span>
                    '.lang('Add New Member').'</a>';
    }elseif($do == 'Edit'){// edit page
        // get user id and get it's numeric value
        $user = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
         // select user from database depending of user id coming throug edit profile page
        $stmt = $con->prepare("SELECT * FROM Users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($user));//execute query
        $row = $stmt->fetch();// fetch data from database
        $count = $stmt->rowCount();// check number of coloumn that matched the user id in database
        if($count>0){// if greater than one the mean that user id is exist
        ?>
        <h1 class="text-center"><?php echo lang('Edit Member')?></h1>
        <!-- start container -->
        <div class="container">
            <!-- start edit form -->
            <form class="form-horizontal" action="?do=Update" method="POST">
                <!-- start user id -->
                <input type="hidden" name="User_ID" value="<?php echo $user ?>"/>
                <!-- end user id -->
                <!-- start user name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('User Name')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="text" value="<?php echo $row['UserName'];?>"
                               required="required" name="username" class="form-control"
                               autocomplete="off" placeholder="<?php echo lang('User Name')?>"/>
                    </div>
                </div>
                <!-- end user name field -->
                <!-- start full name field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Full Name')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="text" value="<?php echo $row['FullName'];?>"
                               required="required" name="fullname" class="form-control"
                               autocomplete="off" placeholder="<?php echo lang('Full Name')?>"/>
                    </div>
                </div>
                <!-- end user full field -->
                <!-- start Email field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Email')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="email" value="<?php echo $row['Email'];?>"
                               required="required" name="email" class="form-control"
                               autocomplete="off" placeholder="<?php echo lang('Eail')?>"/>
                    </div>
                </div>
                <!-- end Email field -->
                <!-- start password field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Password')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="hidden" name="oldpass" class="form-control" value="<?php echo $row['Password']?>"/>
                        <input type="password" name="newpass" class="form-control"
                               autocomplete="new-password" placeholder="<?php echo lang('Leave This Blank If you Do Not Want To change')?>"/>
                    </div>
                </div>
                <!-- end password field -->
                <!-- start submit button -->
                <div class="form-group">
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('Save')?>"/>
                    </div>
                </div>
                <!-- end submit button -->
            </form>
    <?php
            }else{ // else this mean that user id not exist in database show this message
               $msg = '<div class="alert alert-danger error">'.lang('There Is No Such ID').'</div>';
               redirect($msg);//redirect uset to login page
            }
            echo '</div>';
    }elseif($do == 'Update'){// update page
        if($_SERVER['REQUEST_METHOD'] == 'POST'){// check if user coming throug post request
            echo '<h1 class="text-center">'.lang('Update Member').'</h1>';
            echo '<div class="container">';//start the container
            $id        = $_POST['User_ID'];// receive user id in id var
            $user_name = $_POST['username'];//receive user name in username var
            $full_name = $_POST['fullname'];//receive full name in fullname var
            $email     = $_POST['email'];//receive email in email var
            //update password password trick
            $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);
            $formerror = array();//set formerror array to validate form and store all error in it
            if(empty($user_name)){//if user name is empty store this message in array
                $formerror[] = lang('UserName Can Not Be Empty');
            }
            if(strlen($user_name)<4){//if user name less than 4 chars store this message in array
                $formerror[] = lang('User Name Can Not Be Less Than 4 Characters');
            }
            if(strlen($user_name)>20){//if user name is large than 20 chars store this message in array
                $formerror[] = lang('User Name Can Not Be Greater Than 20 Characters');
            }
            if(empty($full_name)){//if full name is empty store this message in array
                $formerror[] = lang('Full Name Can Not Be Empty');
            }
            if(empty($email)){//if email is empty store this message in array
                $formerror[] = lang('Email Can Not Be Empty');
            }
            foreach ($formerror as $error){//foreach loop to show all message stored in array
                $msg = '<div class="alert alert-danger">' . $error . '</div>';
                redirect($msg,'back');//redirect user to previous page
            }
            if(empty($formerror)){//check if there is no error update database
                $stmt1 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
                $stmt1->execute(array($user_name,$id));
                $count = $stmt1->rowCount();
                if($count == 1){
                    $msg = '<div class="alert alert-danger">'.lang('Sorry This User Exist').'</div>';
                    redirect($msg,'back');
                }else{
                    // update the database with this info
                    $stmt = $con->prepare("UPDATE Users SET 
                                                        UserName = ? , FullName= ? , Email = ?, 
                                                        Password =? WHERE UserID= ?");
                //execute the query
                $stmt->execute(array($user_name,$full_name,$email,$pass,$id));
                // success messgae
                $msg = '<div class="alert alert-success">' . $stmt->rowCount() . ' ' . lang('Record Updated') .'</div>';
                redirect($msg,'back');//redirect user to previous page
                }
            }
        }else{//if user not coming through post request show error message
            $msg = '<div class="alert alert-danger">'.lang('You Can Not Browse This Page Directly').'</div>';
            redirect($msg);//recirect user to login page
        }
        echo '</div>';
    }elseif($do == 'Add'){//add page ?>
        <h1 class="text-center"><?php echo lang('Add New Member')?></h1>
        <!-- start container -->
        <div class="container">
            <!-- start add form -->
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- start User Name -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('User Name')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="text" name="username" class="form-control"
                        required="required" autocomplete="off" placeholder="<?php echo lang('User Name')?>"/>
                    </div>
                </div>
                <!-- end user name -->
                <!-- start Full Name -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Full Name')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="text" name="fullname" class="form-control"
                        required="required" autocomplete="off" placeholder="<?php echo lang('Full Name')?>"/>
                    </div>
                </div>
                <!-- end Full name -->
                <!-- start Email -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Email')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="email" name="email" class="form-control"
                        required="required" autocomplete="off" placeholder="<?php echo lang('Email')?>"/>
                    </div>
                </div>
                <!-- end Email -->
                <!-- start Password -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label"><?php echo lang('Password')?></label>
                    <div class="col-sm-10 col-md-6 pull-right">
                        <input type="password" name="password" class="password form-control"
                        required="required" autocomplete="new-password" placeholder="<?php echo lang('Password')?>"/>
                        <i class="show-pass glyphicon glyphicon-eye-open"></i>
                    </div>
                </div>
                <!-- end password -->
                <!-- start submit -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-10">
                        <input type="submit" class="btn btn-primary btn-lg" value="<?php echo lang('Add New Member')?>"/>
                    </div>
                </div>
                <!-- end submit -->
            </form>
        </div>
        <!-- end container -->
    <?php
    }elseif($do == 'Insert'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){//insert page check if user coming through post request
            echo '<h1 class="text-center">'.lang('Insert Member').'</h1>';
            echo '<div class="container">';
            $user = $_POST['username'];//receive user name form form in user var
            $password = $_POST['password'];//receive password in password var
            $hashedpass = sha1($password);//hashed the password with sha1 method
            $fullname = $_POST['fullname'];//receive full name in full name var
            $email    = $_POST['email'];//receive email in email var
            $formerror = array();//set formerror array to validate form and sotre all error
            if(empty($user)){//if user name is empty store this message in array
                $formerror[] = lang('UserName Can Not Be Empty');
            }
            if(strlen($user) <4){//if user name is less than 4 chars store this message in array
                $formerror[] = lang('User Name Can Not Be Less Than 4 Characters');
            }
            if(strlen($user)>20){//if user name large than 20 chars store this message in array
                $formerror[] = lang('User Name Can Not Be Greater Than 20 Characters');
            }
            if(empty($fullname)){//if full name is empty store this message in array
                $formerror[] = lang('Full Name Can Not Be Empty');
            }
            if(empty($email)){//if email is empty store this message in array
                $formerror[] = lang('Email Can Not Be Empty');
            }
            if(empty($password)){//if password is empty store this message in array
                $formerror[] = lang('Password Can Not Be Empty');
            }
            foreach ($formerror as $error){//foreach loop to show all message stored in array
                $msg = '<div class="alert alert-danger">' . $error . '</div>';
                redirect($msg,'back');//redirect the user to preivous page
            }
            if(empty($formerror)){//if form error is empty
                $check = checkitem("UserName","users",$user);//check if user exist in database
                if($check == 1){//if check = 1 this mean that user exist show this message
                    $msg = '<div class="alert alert-danger">'.lang('Sorry This User Is Exist').'</div>';
                    redirect($msg,'back');//redirect the user to preivous page
                }else{//if not = 1 this mean that usre not exist execute the query
                    $stmt = $con->prepare("INSERT INTO 
                                                            users (UserName,FullName,Email,Password,Date) 
                                                            VALUES(:zuser,:zfull,:zmail,:zpass,Now())");
                    $stmt->execute(array(
                        'zuser' => $user,
                        'zfull' => $fullname,
                        'zmail'=> $email,
                        'zpass' => $hashedpass
                    ));//execute the query and shwo success message
                    $msg = '<div class="alert alert-success">' . $stmt->rowcount() . ' ' .lang('Row Inserted').' </div>';
                    redirect($msg,'back');//redirect user to preivous page
                }
            }
            echo '</div>';
        }else{//if user not coming to this page using post request show this message
            $msg = '<div class="alert alert-danger error">'.lang('You Can Not Browse This Page Directly').'</div>';
            redirect($msg);//redirect user to login page
        }
    }elseif($do == 'Delete'){ // delete page
            echo '<h1 class="text-center">'.lang('Delete Member').'</h1>';
            echo '<div class="container">';
            // get user id and get it's numeric value
            $user = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
            // select user from database depending of user id coming throug edit profile page
            $check = checkitem('UserID','users',$user);
            // fetch data from databas
            if ($check == 1) {// if greater than one the mean that user id is exists
                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zid");
                $stmt->bindParam(":zid",$user);//bind parameter to value
                $stmt->execute();//execute query and show success message
                $msg = '<div class="alert alert-success">' . $stmt->rowcount() . ' ' .lang('Row Deleted').'</div>';
                redirect($msg,'back');//redirect message to preiovus page
            }else{//if check not = 1 this mean that there is no such id show this message
                $msg = '<div class="alert alert-danger">'.lang('Sorry This ID Not Exist').'</div>';
                redirect($msg); //redirect user to login page
            }
            echo '</div>';
    }
    include $temp . 'footer.php';//include footer
}else{
    header('Location:index.php');//redirect to login page
    exit();//stop running any scirpt
}
    ob_end_flush();//start go output after page end