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
                <button class="login">Forget Password</button>
            </div>

            <!-- Form section that contains the login and the signup form -->
            <div class="form-section">

                <!-- signup form -->
                <form class="signup-box" action="forget_password.php" method="POST" enctype="multipart/form-data">
                    <input type="email" class="name ele" name="reg_email" placeholder="Enter your name" required>
                    <input type="text" class="email ele" name="reg_contact" placeholder="Enter Contact Number" required>
                    <input type="password" class="password ele" name="reg_pass" placeholder="Enter New assword" required>
                    <input type="password" class="password ele" name="c_reg_pass" placeholder="Confirm password" required>
                    <button type="submit" name="update_pass" class="clkbtn">Click</button>
                </from>
                <?php 

                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\SMTP;
                    use PHPMailer\PHPMailer\Exception;

                    require 'vendor/autoload.php';

                    $mail = new PHPMailer(true);



                    if(isset($_POST['update_pass'])){   
                        $c_email = $_POST['reg_email'];  
                        $c_contact = $_POST['reg_contact'];
                        $new_pass = $_POST['reg_pass'];
                        $new_pass2 = $_POST['c_reg_pass'];
                
                        


                        function getToken($len=32){
                            return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
                        }
                        $token = getToken(10);

                        
                        $select_user = "select * from users where user_email='$c_email' AND user_contact='$c_contact'";    
                        $run_user = mysqli_query($con,$select_user);
                        $check_user = mysqli_num_rows($run_user);
                        $row_user = mysqli_fetch_array($run_user);
                        $user_id = $row_user['user_id']; 

                        $get_ip = getRealIpUser();

                        if($new_pass == $new_pass2){

                            $password = password_hash($new_pass, PASSWORD_DEFAULT);

                            if($check_user==0){
                                echo "<script>alert('No User Found!')</script>"; 
                                echo "<script>window.open('forget_password.php','_self')</script>"; 
                            }
                            else{

                                $update_token = "update users set token='$token' where user_id='$user_id'";
                                $run_token = mysqli_query($con,$update_token);

                                try {

                                    $send_to = $_POST['reg_email'];
                        
                                    $mail->isSMTP();                                            
                                    $mail->Host       = 'smtp.gmail.com';                     
                                    $mail->SMTPAuth   = true;                                   
                                    $mail->Username   = 'jacquelinechavezkh@gmail.com';                     
                                    $mail->Password   = '';                               
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
                                    $mail->Port       = 465;                                    
                                
                        
                                    $mail->setFrom('jacquelinechavezkh@gmail.com', 'Password Reset');
                                    $mail->addAddress($send_to);     
                                
                                
                        
                                    $mail->isHTML(true);                                  
                                    $mail->Subject = 'Request for password reset';
                                    $mail->Body = 'click the link to recover your password. <a href="http://localhost/messenger_clone/update.php?email=' . $send_to . '&token=' . $token . '&hash=' . $password . '"> Click here</a>';
                                
                                    $mail->send();
                                    $output =  'Message has been sent';
                                } 
                                catch (Exception $e) {
                                    $output =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }


                                if($run_token){
                                    echo "<script>alert('Link for update Password sent to your email!')</script>";
                                    echo "<script>window.location.href='index.php'</script>";
                                }

                            }
                        }
                        else{
                            echo "<script>alert('Recheck Password!')</script>";
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

