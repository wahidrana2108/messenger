<?php
    include("db.php");

    if($_GET){
        if(isset($_GET['email'])){
            $email = $_GET['email'];
            if($email == ''){
                unset($email);
            }
        }
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            if($token == ''){
                unset($token);
            }
        }
        
        if(!empty($email) && !empty($token)){
            $get_user = "select * from users where user_email='$email'";
            $run_user = mysqli_query($con,$get_user);
            $row = mysqli_fetch_array($run_user);
            $count_user = mysqli_num_rows($run_user);
            if($count_user == 1){
                $update = "update users set verified=1, token='' where user_email='$email'";
                $run_update = mysqli_query($con,$update);
                if($run_update){
                    echo "<script>alert('Email address successfully verified!')</script>";
                    echo "<script>window.location.href='index.php'</script>";
                }
            }
           
        }
    }
?>