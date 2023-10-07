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
        if(isset($_GET['hash'])){
            $hash = $_GET['hash'];
            if($hash == ''){
                unset($hash);
            }
        }
        
        if(!empty($email) && !empty($token)){
            $get_users = "select * from users where user_email='$email'";
            $run_users = mysqli_query($con,$get_users);
            $row = mysqli_fetch_array($run_users);
            $count_users = mysqli_num_rows($run_users);
            if($count_users == 1){
                $update = "update users set token='', user_pass='$hash' where user_email='$email'";
                $run_update = mysqli_query($con,$update);
                if($run_update){
                    echo "<script>alert('Password updated successfully!')</script>";
                    echo "<script>window.location.href='index.php'</script>";
                }
            }
           
        }
    }
?>