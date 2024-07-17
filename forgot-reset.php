<?php
require_once "setup.php";

#Get token from request
$token = $_GET["token"];
$token_hash = hash("sha256", $token);

#Get record from database
$sql = "SELECT * FROM customers
        WHERE reset_token_hash = ?";

$conn = connect();
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

#If no token found
if($user==null){
    $message_err = "No record found of user.";
    showError($message_err);
}

#If token expired
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $message_err = "Request expired, please try again.";
    showError($message_err);
}

echo
'<html>
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

            <form action="" method="post">
                <div class="form-group">
                    <p>Enter Password:</p>
                    <input type="password" name="new_password" class="form-control" value="">
                    <span class="invalid-feedback"><?php echo $new_password_err; ?>
                </div>

                <div class="form-group">
                    <p style="margin-top: 15px;">Confirm Password:</p>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary w-100" value="Submit">Submit</button>
                    <a href="edit.php" class="mt-3 btn btn-secondary w-100">Back</a>
                </div>
            </form>
            
            <div>
            </div>
        </div>
    </body>
</html>';
?>

<?php
#Error message
function showError($message_err) {
    echo '<html>';
    echo '<head>';
    echo '<title>Reset Password</title>';
    echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="h-100 container d-flex flex-column justify-content-center align-items-center">';
    echo '<div class="mt-4 alert alert-danger">' .$message_err. '</div>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}
?>

