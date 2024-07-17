<?php
#Files
require_once "connect.php";
require_once "setup.php";
$message = "";

if(isset($_POST['Submit'])){
    #Set variables
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    

    #TOKEN will expire in 5 minutes [change 5 if needed]
    $expire = date("Y-m-d H:i:s", time() + 60 * 5);

    $conn = connect();

    #add TOKEN and set EXPIRE
    $sql = "UPDATE customers 
            SET reset_token_hash = '$token_hash', reset_token_expires_at = '$expire'
            WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        $mail = require_once "forgot-mailer.php";
        
        $mail->setFrom("noreply@email.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END
        <h4>Hello!</h4>
        <br>
        <p>We've received a request to reset your password for your account on our website. If you did not make this request, please ignore this email.</p>
        <br>
        <p>Click this link to reset your password!</p>
        <br>
        <a href="http://localhost/finals-takoyaki/Takoyaki/TakoyakiWebsite/forgot-reset.php?token=$token">CLICK ME!</a> 
        END;

        try {
            $mail->send();
            $message = "Password reset has been sent to your inbox, please check.";
            $success = true;
        } catch (Exception $e) {
            $message = "Error:  {$mail->ErrorInfo}";
            $success = false;
        }
    } else {
        $message = "Critical error regarding with the database.";
        $success = false;
    }
}
?>

<html>
    <head>       
        <title>Forgot Password</title>
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <style>
            .logo {
                display: inline-block;
                width: 95px;
                height: 95px;
                border-radius: 50%;
                margin-left: 2%;
            }
        </style>
    </head>

    <body style="background: rgb(255,165,0); background: linear-gradient(90deg, rgba(255,165,0,1) 0%, rgba(255,165,0,1) 34%, rgba(255,255,255,1) 35%, rgba(255,255,255,1) 100%);">
        <div class="container-fluid d-flex flex-row justify-content-center align-items-center w-100" style="height: 100vh">

            <div class="col d-flex flex-row justify-content-center align-items-center h-100 w-100" style="margin-left: 50px;"> 
                <center>
                <img src="images/ipad-takoyaki.gif" class="h-100 w-100" alt="">
                </center>
            </div>
            
            <div class="col w-100 h-100 d-flex flex-column justify-content-center align-items-center" style="margin-right: 50px;">
                <a href="index.php"><img src="Images/Logo.jpg" class="logo" alt=""></a>
                <div class="container-fluid d-flex align-items-center">
                    <div class="form-group border w-100 border-black p-5 m-5">
                        <h3 class="text-center mt-3 mb-3">Forgot Password</h3>
                        <p class="text-secondary">Enter your email address below. We will send you a link to reset your password.</p>
                        <p>Email:</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <input type="email" name="email" id="email" class="form-control" required>
                            <input type="submit" name="Submit" value="Submit" class="mt-4 btn btn-primary w-100">
                        </form>
                        <a class="btn btn-secondary w-100" href="login.php">Go back</a>
                    </div>
                </div>
                <?php
                if(isset($_POST['Submit'])){
                    if ($success = true) {
                        echo '<div class="alert alert-success w-75 text-center">
                                '. $message .'
                                </div>';
                    }
                    if ($success = false){
                        echo '<div class="alert alert-danger text-center">
                                '. $message .'
                            </div>';
                    }
                }
                ?>
            </div>

        </div>
    </body>
</html>