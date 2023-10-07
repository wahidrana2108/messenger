<?php
    include("function.php");
    include("db.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger</title>
    <link rel="icon" type="image/x-icon" href="images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/fedd93fc11.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">

</head>
    <body>
        <header>
            <h1 class="heading">Messenger</h1>
        </header>

        <!-- container div -->
        <div class="container">

            <!-- upper button section to select the login or signup form -->
            <div class="slider"></div>
            <div class="btn">
                <button class="login">Login</button>
                <button class="signup">Signup</button>
            </div>

            <!-- Form section that contains the login and the signup form -->
            <div class="form-section">
                <!-- login form -->
                <form class="login-box" action="index.php" method="POST" enctype="multipart/form-data">
                    <input type="email" class="email ele" name="user_email" placeholder="Enter Email" required>
                    <input type="password" class="password ele" name="user_pass" placeholder="Enter Password" required>
                    <button type="submit" name="login" class="clkbtn">LogIn</button>
                    <div class="forget">Forget Passord? <a class="text-decoration-none" href="forget_password.php">Click here</a></div>
                </form>
                <?php 
                    if(isset($_POST['login'])){   
                        $user_email = $_POST['user_email'];  
                        $user_pass = $_POST['user_pass'];   
                        $select_user = "select * from users where user_email='$user_email'";
                        $run_user = mysqli_query($con,$select_user);
                        $count_user = mysqli_num_rows($run_user);


                        if($count_user == 1){

                            $row_user = mysqli_fetch_array($run_user);
                            
                            $email = $row_user['user_email'];
                            $pass = $row_user['user_pass'];
                            $verified = $row_user['verified'];

                            $get_ip = getRealIpUser();

                            if(password_verify($user_pass, $pass)){
                                if($verified == 1){
                                    $_SESSION['user_email']=$user_email;        
                                    echo "<script>alert('You are Logged in Successfully!')</script>";          
                                    echo "<script>window.open('test.php','_self')</script>";
                                }
                                else{
                                    echo "<script>alert('Verify your email first!')</script>";          
                                    echo "<script>window.open('index.php','_self')</script>";
                                }
                            
                            }
                            else{
                                echo "<script>alert('Your email or password is wrong!')</script>";      
                                exit();   
                            }
                        } 
                    }
                ?>

                <!-- signup form -->
                <form class="signup-box" action="index.php" method="POST" enctype="multipart/form-data">
                    <input type="text" class="name ele" name="reg_name" placeholder="Enter your name" required>
                    <input type="email" class="email ele" name="reg_email" placeholder="youremail@email.com" required>
                    <input type="password" class="password ele" name="reg_pass" placeholder="password" required>
                    <input type="password" class="password ele" name="c_reg_pass" placeholder="Confirm password" required>
                    <input type="file" class="ele" name="reg_image" required>
                    <button type="submit" name="signup" class="clkbtn">Click</button>
                </from>
                <?php

                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\SMTP;
                    use PHPMailer\PHPMailer\Exception;

                    require 'vendor/autoload.php';

                    $mail = new PHPMailer(true);


                    if(isset($_POST['signup'])){
                        $reg_ip = getRealIpUser();
                        function getToken($len=32){
                            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
                        }
                        $token = getToken(10);  

                        $reg_name = $_POST['reg_name'];  
                        $reg_email = $_POST['reg_email'];  
                        $reg_pass = $_POST['reg_pass'];  
                        $c_reg_pass = $_POST['c_reg_pass'];  
                        $reg_dp = $_FILES['reg_image']['name'];  
                        $reg_dp_tmp = $_FILES['reg_image']['tmp_name']; 
                        
                        move_uploaded_file($reg_dp_tmp,"images/user_photo/$reg_dp");

                        if($reg_pass == $c_reg_pass){
                            $reg_pass_hash =  password_hash($reg_pass, PASSWORD_DEFAULT);

                            $get_user = "select * from users where user_email = '$reg_email'";
                            $run_user = mysqli_query($con, $get_user);
                            $count = mysqli_num_rows($run_user);

                            if($count > 0){
                                echo "<script>alert('The Email already exist!')</script>";
                                echo "<script>window.location.href='index.php'</script>";
                            }
                            else{
                                $insert_user = "insert into users (user_ip, token, user_name, user_email, user_pass, user_dp)  values ('$reg_ip','$token','$reg_name','$reg_email','$reg_pass_hash','$reg_dp')";
                                $run = mysqli_query($con, $insert_user);

                                try {
        
                                    $send_to = $_POST['reg_email'];
                        
                                    $mail->isSMTP();                                            
                                    $mail->Host       = 'smtp.gmail.com';                     
                                    $mail->SMTPAuth   = true;                                   
                                    $mail->Username   = 'jacquelinechavezkh@gmail.com';                     
                                    $mail->Password   = '';                               
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                                    $mail->Port       = 465;                                    
                                  
                        
                                    $mail->setFrom('jacquelinechavezkh@gmail.com', 'Email Confirmation');
                                    $mail->addAddress($send_to);     
                                  
                                  
                        
                                    $mail->isHTML(true);                                  
                                    $mail->Subject = 'Account Activation';
                                    $mail->Body    = 'click the link to activate you account. <a href="http://localhost/messenger_clone/verification.php?email=' . $send_to . '&token=' . $token . '"> Click here</a>';
                                  
                                    $mail->send();
                                    $output =  'Message has been sent';
                                } 
                                catch (Exception $e) {
                                    $output =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
        
                                if($run){
                                    echo "<script>alert('Registration Successful!')</script>";
                                    echo "<script>window.location.href='index.php'</script>";
                                }
                            }
                        }                            
                        else{
                            echo "<script>alert('Recheck your password!')</script>";
                            echo "<script>window.location.href='index.php'</script>";
                        }
                    }  
                ?>
            </div>
        </div>



    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>

    </body>
</html>

