<?php
session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION["position"] === "client"){
        header("location: index.php");
        exit;
    } elseif($_SESSION["position"] === "admin"){
        header("location: Admin_dashboard.php");
        exit;
    }
}

require_once "connect.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty($email_err) && empty($password_err)){
        // unite employees & customers tables to find user
        $sql = "
            SELECT id, email, full_name, password, position, phone_number, address FROM (
                SELECT customer_id AS id, email, full_name, password, position, phone_number, address FROM customers
                UNION
                SELECT employee_id AS id, email, full_name, password, position, phone_number, address FROM employees
            ) AS combined
            WHERE email = ?
        ";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $email, $full_name, $hashed_password, $position, $phone_number, $address);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["full_name"] = $full_name;
                            $_SESSION["email"] = $email;
                            $_SESSION["position"] = $position;
                            $_SESSION["phone_number"] = $phone_number;
                            $_SESSION["address"] = $address;

                            if($position === "Client"){
                                header("location: index.php");
                            } else if($position === "Admin"){
                                header("location: Admin_dashboard.php");
                            } else {
                                header("location: index.php");
                            }
                        } else{
                            $login_err = "Incorrect password, please try again.";
                        }
                    }
                } else{
                    $login_err = "Incorrect email, please try again.";
                }
            } else{
                $login_err = "Something went wrong, please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container1">
            <h2>Login</h2>
            <hr>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="form-group">
                    <p>Email:</p>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>

                <div class="form-group">
                    <p style="margin-top: 15px;">Password:</p>
                    <input type="password" name="password" class ="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>

                <?php 
                if(!empty($login_err)){
                    echo '<div class="mt-4 alert alert-danger">' . $login_err . '</div>';
                }        
                ?>

                <div class="buttons">
                    <button class = "btn btn-danger w-100" type="submit">Login</button>
                    <a class="btn btn-secondary text-light w-100" href="register.php">Register</a>
                </div>
            </form>
        </div>
    </body>
</html>