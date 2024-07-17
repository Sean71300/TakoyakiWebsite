<?php
session_start();
require_once "setup.php";
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

<?php
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

$_SESSION['result'] = $user;

#If no token found
if($user==null){
    $message_err = "No record found of user.";
    showError($message_err);
    exit;
}

#If token expired
if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $message_err = "Request expired, please try again.";
    showError($message_err);
    exit;
}

header("Location: forgot-form.php");
?>