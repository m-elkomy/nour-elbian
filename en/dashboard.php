<?php
    ob_start(); // output buffering start save all output in memory except header
    session_start();//start session
    if(isset($_SESSION['username'])){//check if session set with this user name
        $pagetitle = 'Dashboard'; // page title set
        include 'init.php';//include initialize page
        $latestuser = 5;
        $thelatesttuser = getlatest('*', 'users', 'UserID');
        $latestteacher = 5;
        $thelaestteacher = getlatest('*','teachers','TeacherID');
        $lateststudent  = 5;
        $thelateststudent = getlatest('*','students','StudentID');
        $latestclasses  = 5;
        $thelatestclasses = getlatest('*','classes','ClassID');
        ?>
        <!-- start dashboard page -->
        <!-- start statstics -->
        <div class="home-state">
            <!-- start container -->
            <div class="container text-center">
                <h1 class="text-center"><?php echo lang('Dashboard');?></h1>
                <div class="row">
                    <div class="col-md-3">
                        <!-- end state -->
                        <div class="state st-member">
                            <?php echo lang('Total Member');?>
                            <span>
                                <a href="member.php">
                                    <?php echo countnum("UserID","users");?>
                                </a>
                            </span>
                        </div>
                        <!-- end state -->
                    </div>
                    <div class="col-md-3">
                        <!-- start state -->
                        <div class="state st-teacher">
                            <?php echo lang('Total Teachers');?>
                            <span>
                                <a href="teachers.php">
                                    <?php echo countnum('TeacherID','teachers')?>
                                </a>
                            </span>
                        </div>
                        <!-- end state -->
                    </div>
                    <div class="col-md-3">
                        <!-- start state -->
                        <div class="state st-student">
                            <?php echo lang('Total Students');?>
                            <span>
                                <a href="students.php.php">
                                    <?php echo countnum('StudentID','students')?>
                                </a>
                            </span>
                        </div>
                        <!-- end state -->
                    </div>
                    <div class="col-md-3">
                        <!-- start state -->
                        <div class="state st-classe">
                            <?php echo lang('Total Classes');?>
                            <span>
                                <a href="class.php.php.php">
                                    <?php echo countnum('ClassID','classes')?>
                                </a>
                            </span>
                        </div>
                        <!-- end state -->
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end home state -->
        <!-- end statstics -->
        <!-- start banel -->
        <div class="latest">
            <!-- end container -->
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <!-- start panel heading -->
                            <div class="panel-heading">
                                <span class="glyphicon glyphicon-user"></span>
                                 <?php echo lang('Latest') . ' ' . $latestuser .' '. lang('Registerd Users') ?>
                                <span class="pull-right toggle-info">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </span>
                            </div>
                            <!-- end panel heading -->
                            <!-- start penel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled list-user">
                                <?php
                                if(!empty($thelatesttuser)){//if not empty show all data
                                    foreach ($thelatesttuser as $user){
                                        echo '<li>' . $user['UserName'] .
                                            '<a href="member.php?do=Edit&id='.$user['UserID'].'">
                                            <span class="btn btn-success pull-right">
                                            <span class="glyphicon glyphicon-edit"></span> ' . lang('Edit') .'</span>
                                            </a></li>';
                                    }
                                }else{//if empty show this message
                                    echo '<div class="alert alert-info nice-message">' .
                                            lang('Sorry There Is No Recored To Show') .'</div>';
                                }
                                ?>
                                </ul>
                            </div>
                            <!-- end panel body -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <!-- start penel heading -->
                            <div class="panel-heading">
                                <span class="glyphicon glyphicon-user"></span>
                                 <?php echo lang('Latest') . ' ' . $latestteacher . ' ' . lang('Registerd Teachers')?>
                                <span class="pull-right toggle-info">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </span>
                            </div>
                            <!-- start panel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled list-user">
                                    <?php
                                    if(!empty($thelaestteacher)){//if not empty show all data
                                        foreach ($thelaestteacher as $teacher){
                                            echo '<li>' . $teacher['TeacherName'] .
                                                '<a href="teachers.php?do=Edit&tid='.$teacher['TeacherID'].'">
                                                 <span class="btn btn-success pull-right">
                                                 <span class="glyphicon glyphicon-edit"></span> ' . lang('Edit') .'</span>
                                                 </a></li>';
                                        }
                                    }else{//if empty show this message
                                        echo '<div class="alert alert-info nice-message">' .
                                            lang('Sorry There Is No Recored To Show') .'</div>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!-- end panel body-->
                        </div>
                        <!-- end panel defual -->
                    </div>
                </div>
            </div>
            <!-- end container -->
        </div>
        <!-- end latest -->
        <!-- end banel -->
        <div class="latest">
            <!-- start container -->
            <div class="container">
                <!-- start row -->
                <div class="row">
                    <div class="col-sm-6">
                        <!-- start panel -->
                        <div class="panel panel-default">
                            <!-- start panel heading -->
                            <div class="panel-heading">
                                <span class="glyphicon glyphicon-user"></span>
                                 <?php echo lang('Latest'). ' ' .$lateststudent . ' ' . lang('Registerd Student')?>
                                <span class="pull-right toggle-info">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </span>
                            </div>
                            <!-- end panel heading -->
                            <!-- start panel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled list-user">
                                    <?php
                                    if(!empty($thelateststudent)){//if not empty make for loop to show data
                                        foreach ($thelateststudent as $student){
                                            echo '<li>' . $student['StudentName'] .
                                                '<a href="students.php?do=Edit&sid='.$student['StudentID'].'">
                                                <span class="btn btn-success pull-right">
                                                <span class="glyphicon glyphicon-edit"></span> ' . lang('Edit') .'</span>
                                                </a></li>';
                                        }
                                    }else{
                                        echo '<div class="alert alert-info nice-message">'.
                                            lang('Sorry There Is No Recored To Show').'</div>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!-- end panel body -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <!-- start panel heading -->
                            <div class="panel-heading">
                                <span class="glyphicon glyphicon-user"></span>
                                <?php echo lang('Latest') . ' ' .  $latestclasses . ' ' . lang('Registerd Classes')?>
                                <span class="pull-right toggle-info">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </span>
                            </div>
                            <!-- end panel heading -->
                            <!-- start panel body -->
                            <div class="panel-body">
                                <ul class="list-unstyled list-user">
                                    <?php
                                    if(!empty($thelatestclasses)){//if not empty show all data
                                        foreach ($thelatestclasses as $class){
                                            echo '<li>' . $class['ClassName'] .
                                                '<a href="class.php?do=Edit&cid='.$class['ClassID'].'">
                                                 <span class="btn btn-success pull-right">
                                                 <span class="glyphicon glyphicon-edit"></span> ' . lang('Edit') .'</span></a></li>';
                                        }
                                    }else{
                                        echo '<div class="alert alert-info nice-message">'.
                                            lang('Sorry There Is No Recored To Show').'</div>';
                                    }
                                    ?>
                                </ul>
                            </div>
                            <!-- end panel body -->
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end containenr -->
        </div>
        <!-- end latest -->
        <!-- end daashboard page -->
        <?php
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if session not exist redirect to login page
        exit();//stop executing any script
    }
    ob_end_flush(); // after page end go output