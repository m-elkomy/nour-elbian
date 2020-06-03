<?php
    ob_start();//start output buffering to store all output in memory
    session_start();//start session
    if(isset($_SESSION['username'])){//check if session register with user name show page
        $pagetitle = 'View'; // set page title
        include 'init.php';//include initialize file
        $do = isset($_GET['do']) ? $_GET['do'] : 'Branch';//if there is get request with do store request in do var
        if($do == 'Branch'){//manage page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT * FROM branch ORDER BY branch.BranchID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
            ?>
            <h1 class="text-center">View Branch</h1>
            <!-- start container -->
            <div class="container">
                <!-- start table responsive -->
                <div class="table-responsive">
                    <!-- start main table -->
                    <table class="main-table text-center table table-bordered" id="branchname">
                        <tr>
                            <td>ID</td>
                            <td>Branch Name</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {//foreach loop to show all data
                            echo '<tr>';
                                echo '<td>' . $row['BranchID'] . '</td>';//student id
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
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
            echo '<div class="container">
                    <div class="col-sm-10 col-md-6">
                                <select name="branchid" id="selec2" onchange="showbranch(this.value)">
                                    <option value="ID">Branch ID</option>
                                    <option value="Name">Branch Name</option>
                                </select>
                            </div>
                </div>';
        }elseif($do == 'Classes'){//edit page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT classes.*, branch.* 
                                            FROM classes INNER JOIN branch 
                                            ON classes.BranchID = branch.BranchID
                                            ORDER BY classes.ClassID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
                ?>
                <h1 class="text-center">View Classes</h1>
                <!-- start container -->
                <div class="container">
                    <!-- start table responsive -->
                    <div class="table-responsive">
                        <!-- start main table -->
                        <table class="main-table text-center table table-bordered" id="cname">
                            <tr>
                                <td>Class ID</td>
                                <td>Class Name</td>
                                <td>Branch ID</td>
                                <td>Branch Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {//foreach loop to show all data
                                echo '<tr>';
                                echo '<td>' . $row['ClassID'] . '</td>';//student id
                                echo '<td>' . $row['ClassName'] . '</td>';//student id
                                echo '<td>' . $row['BranchID'] . '</td>';//student id
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
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
            echo '<div class="container">
                    <div class="col-sm-10 col-md-6">
                                <select name="branchid" id="selec2" onchange="showclass(this.value)">
                                    <option value="BID">Branch ID</option>
                                    <option value="BName">Branch Name</option>
                                    <option value="CID">Class ID</option>
                                    <option value="CName">Class Name</option>
                                </select>
                            </div>
                </div>';
        }elseif($do == 'Teachers'){//update page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT teachers.*, branch.* 
                                            FROM teachers INNER JOIN branch 
                                            ON teachers.BranchID = branch.BranchID
                                            ORDER BY teachers.TeacherID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
                ?>
                <h1 class="text-center">View Teachers</h1>
                <!-- start container -->
                <div class="container">
                    <!-- start table responsive -->
                    <div class="table-responsive">
                        <!-- start main table -->
                        <table class="main-table text-center table table-bordered" id="tname">
                            <tr>
                                <td>Teacher ID</td>
                                <td>Teacher Name</td>
                                <td>Branch Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {//foreach loop to show all data
                                echo '<tr>';
                                echo '<td>' . $row['TeacherID'] . '</td>';//student id
                                echo '<td>' . $row['TeacherName'] . '</td>';//student id
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
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
            echo '<div class="container">
                    <div class="col-sm-10 col-md-6">
                                <select name="branchid" id="selec3" onchange="showteachers(this.value)">
                                    <option value="BID">Branch ID</option>
                                    <option value="BName">Branch Name</option>
                                    <option value="TID">Teacher ID</option>
                                    <option value="TName">Teacher Name</option>
                                </select>
                            </div>
                </div>';
        }elseif($do == 'Students'){//add page
            //select all data from student table and related data in other table
            $stmt = $con->prepare("SELECT students.*, branch.*, classes.* 
                                            FROM students INNER JOIN branch 
                                            ON students.BranchID = branch.BranchID
                                            INNER JOIN classes ON students.ClassID = classes.ClassID
                                            ORDER BY students.StudentID ASC");
            $stmt->execute();//execute script
            $rows = $stmt->fetchAll();//fetch all data
            if(!empty($rows)){// if rows not empty show data
                ?>
                <h1 class="text-center">View Students</h1>
                <!-- start container -->
                <div class="container">
                    <!-- start table responsive -->
                    <div class="table-responsive">
                        <!-- start main table -->
                        <table class="main-table text-center table table-bordered" id="sname">
                            <tr>
                                <td>Student ID</td>
                                <td>Student Name</td>
                                <td>Branch ID</td>
                                <td>Branch Name</td>
                                <td>Class ID</td>
                                <td>Class Name</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach ($rows as $row) {//foreach loop to show all data
                                echo '<tr>';
                                echo '<td>' . $row['StudentID'] . '</td>';//student id
                                echo '<td>' . $row['StudentName'] . '</td>';//student id
                                echo '<td>' . $row['BranchID'] . '</td>';//student name
                                echo '<td>' . $row['B_Name'] . '</td>';//student name
                                echo '<td>' . $row['ClassID'] . '</td>';//student name
                                echo '<td>' . $row['ClassName'] . '</td>';//student name
                                echo '<td>
                                      <a class="btn btn-success" href=""><span class="glyphicon glyphicon-edit"></span> Edit</a>  
                                      <a class="btn btn-danger confirm" href=""><span class="glyphicon glyphicon-remove"></span> Delete</a>  
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
            echo '<div class="container">
                    <div class="col-sm-10 col-md-6">
                                <select name="branchid" id="selec3" onchange="showstudents(this.value)">
                                    <option value="BID">Branch ID</option>
                                    <option value="BName">Branch Name</option>
                                    <option value="SID">Student ID</option>
                                    <option value="SName">Student Name</option>
                                    <option value="CID">Class ID</option>
                                    <option value="CName">Class Name</option>
                                </select>
                            </div>
                            <div>
                            </div>
                </div>';
        }
        include $temp . 'footer.php';//include footer
    }else{
        header('Location:index.php');//if there is no session was set redirect to login page
        exit();//stop executing any script
    }
    ob_end_flush();// after page end go output