<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('Nour-ElBian')?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="Branche.php"><?php echo lang('Branches');?> <span class="sr-only">(current)</span>
                    </a>
                </li>
                <?php
                if($_SESSION['GroupID'] == 1){
                    include "temp.php";
                }
                ?>
                <li><a href="students.php"><?php echo lang('Students')?></a></li>
                <li><a href="class.php"><?php echo lang('Classes')?></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                       role="button" aria-haspopup="true" aria-expanded="false">
                        <?php echo lang('View')?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="view.php?do=Students"><?php echo lang('Student')?></a></li>
                        <li><a href="view.php?do=Classes"><?php echo lang('Classes')?></a></li>
                        <li><a href="view.php?do=Teachers"><?php echo lang('Teachers')?></a></li>
                        <li><a href="view.php?do=Branch"><?php echo lang('Branch')?></a></li>
                    </ul>
                </li>
                <li><a href="attendance.php"><?php echo lang('Attendance');?></a></li>
                <li><a href="absence.php"><?php echo lang('Absence');?></a></li>
                <li><a href="salary.php"><?php echo lang('Salary');?></a></li>
                <li><a href="member.php"><?php echo lang('Users');?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-left">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username'];?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="member.php?do=Edit&id=<?php echo $_SESSION['ID']; ?>"><?php echo lang('Edit Profile')?></a></li>
                        <li><a href="../index.php"><?php echo lang('Arabic')?></a></li>
                        <li><a href="logout.php"><?php echo lang('Logout')?></a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>