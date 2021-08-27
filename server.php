
<?php
    session_start(); 
    $username = "";
    $email = "";
    $errors = array(); 
    //connect to the database
    $db = mysqli_connect('localhost','root','','business'); 

    //if registration button is clicked 

    if(isset($_POST['register'])){
        $username = mysqli_real_escape_string($db,$_POST['username']);
        $email = mysqli_real_escape_string($db,$_POST['email']);
        $password_1 = mysqli_real_escape_string($db,$_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db,$_POST['password_2']);

        //ensure that form field are filled out properly 

        if(empty($username)){
            array_push($errors, "username is required"); 
        }
        if(empty($email)){
            array_push($errors, "email is required"); 
        }

        if(empty($password_1)){
            array_push($errors, "password is required"); 
        }
        if($password_1 != $password_2){
            array_push($errors, "The two passwords do not match");
        }
        //if there are no errors, save user into the database 
        
        if(count($errors) == 0){
            $password = md5($password_1); //encrypt password before storing into the database 
            $sql = "INSERT INTO users(username,email,password)
                VALUES ('$username','$email','$password')"; 

            mysqli_query($db,$sql); 

            $_SESSION['username'] = $username;
            //$_SESSION['success'] = "You are logged in"; 
            header('location: login.php'); //redirecting to home page 
        }
    }

    // log user in from login page
    if(isset($_POST['login'])){
        $username = mysqli_real_escape_string($db,$_POST['username']);
        $password = mysqli_real_escape_string($db,$_POST['password']);

        //ensure that form field are filled out properly 

        if(empty($username)){
            array_push($errors, "username is required"); 
        }
        if(empty($password)){
            array_push($errors, "password is required here"); 
        }
        if(count($errors) == 0){
            $password = md5($password);//ncrypt password before comparing with one in database 
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($db, $query); 
            if(mysqli_num_rows($result) == 1){
                // log user in 
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are logged in"; 
                header('location: index.php'); //redirecting to home page
            }else{
                array_push($errors, "wrong username/password combination"); 
            }
        }
    }


    //logout 
    if(isset($_GET['logout'])){
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
    }


?>