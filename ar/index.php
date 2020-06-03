<?php
    session_start(); // start the session of the $count > 0
    $pagetitle = 'login'; // page title
    $nonavbar  = ''; // not include navbar in this page
    if(isset($_SESSION['username'])){
        header('Location:dashboard.php');//redirect to dashboard page
    }
    include 'init.php'; // include initialize file to include header and routes
    if($_SERVER['REQUEST_METHOD'] == 'POST'){//check if user coming through post request
        $username = $_POST['user']; //receive user name from form in username variable
        $password = $_POST['pass'];//receive password from form in password variable
        $hashedpass = sha1($password);//hash the passowrd using sha1 method
        // check if user exist in database
        $stmt = $con->prepare("SELECT 
                                            UserID, UserName,Password 
                                        FROM Users WHERE UserName=? 
                                        AND Password=? AND GroupID = 1 LIMIT 1");
        $stmt->execute(array($username,$hashedpass));// execute query
        $row = $stmt->fetch();//fetch data from database
        $count = $stmt->rowcount();// number of row matched the query
        if($count>0){//if count > 0 this mean that this username and password exist so register and start session
            $_SESSION['username'] = $username; // register session with user name
            $_SESSION['ID']       = $row['UserID']; //register session with User ID
            header('location:dashboard.php'); // redirect me if member exist to dashboard
            exit();                                // stop script
        }
    }
?>
    <!-- start login form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input type="text" name="user" placeholder="User Name" autocomplete="off" class="form-control" required="required"/>
        <input type="password" name="pass" placeholder="Password" autocomplete="new-password" class="form-control" required="required"/>
        <input type="submit" class="btn btn-primary btn-block" value="Login"/>
    </form>
    <!-- end login form -->
<?php
    include $temp . 'footer.php';