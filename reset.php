<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo '<div class="container d-flex justify-content-center align-items-center">Invalid access, please login.</div>';
    header("Refresh: 2; url=login.php");
    exit;
}

require_once "connect.php";

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 3){
        $new_password_err = "Password must have atleast 3 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE registered SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<html>
    <head>
        <title>Reset</title>
    </head>
    <body>
    <head>
        <title>Client | Login</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container1">
            <h2 style="margin-bottom: 35px;">Reset Passwords</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="form-group">
                    <p>Enter Password:</p>
                    <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?>
                </div>

                <div class="form-group">
                    <p style="margin-top: 15px;">Confirm Password:</p>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary w-100" value="Submit">Submit</button>
                    <a href="index.php" class="mt-3 btn btn-secondary w-100">Back</a>
                </div>
            </form>
            
            <div>
            </div>
        </div>
    </body>
</html>