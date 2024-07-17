<?php
session_start();
require_once "setup.php";
?>

<?php
if(isset($_POST['reset'])){
$user = $_SESSION['result'];

#Get Passwords
$password = $_POST["new_password"];
$confirm_password = $_POST["confirm_password"];

#Error handling
$error_messages = [];

function specialChars($password) {
    return preg_match('/[^a-zA-Z0-9]/', $password) > 0;
}

function cases($password) { 
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z]).*$/', $password) === 1;
} 

    if (strlen($password) < 8) 
    {
        $error_messages[] = "Password should be more than 8 characters.";
    }

    // Validate special characters in password
    if (!specialChars($password)) 
    {
        $error_messages[] = "Password should have special symbols.";
    }

    // Validate upper and lower case in password
    if (cases($password) == 0) 
    {
        $error_messages[] = "Password should have a combination of upper and lower cases.";
    }

    // Validate password match
    if ($password != $confirm_password) 
    {
        $error_messages[] = "Passwords do not match.";
    }
    
#No errors
    if ($password == $confirm_password){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE customers 
                SET password = ?,
                    reset_token_hash = NULL,
                    reset_token_expires_at = NULL
                WHERE customer_id = ?";
        
        $conn = connect();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $user['customer_id']);
        $stmt->execute();
        $messages = "Password successfully updated!";
    }
} 
?>

<html>
    <head>
        <title>Reset</title>
    </head>
    <body>
    <head>
        <title>Reset Password</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container1">
            <h2 style="margin-bottom: 35px;">Reset Password</h2>

            <form action= <?php echo $_SERVER["PHP_SELF"]?> method="post">
                <div class="form-group">
                    <p>Enter Password:</p>
                    <input type="password" name="new_password" class="form-control" value="">
                </div>

                <div class="form-group">
                    <p style="margin-top: 15px;">Confirm Password:</p>
                    <input type="password" name="confirm_password" class="form-control">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary w-100" name="reset" value="Submit"></input>
                    <a href="index.php" class="mt-3 btn btn-secondary w-100">Back</a>
                </div>
            </form>
            <div>
                <?php
                // Display errors
                if (!empty($error_messages)) 
                {
                    echo '<div class="mt-4 alert alert-danger">';
                    echo '<ul>';
                    foreach ($error_messages as $error_message) 
                    {
                        echo '<li>' . $error_message . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                }

                if (!empty($messages)) 
                {
                    echo '<div class="mt-4 alert alert-success">';
                        echo '<p>'.$messages.'</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>