<?php
session_start();

$session_id = session_id();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo '<html>';
        echo '<head>';
            echo '<title>INVALID ACCESS</title>';
            echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';
        echo '</head>';
    echo '<body>';
        echo '<div class="h-100 container d-flex flex-column justify-content-center align-items-center">';
        echo '<div class="mt-4 alert alert-danger"> Invalid access, please login. </div>';
        echo '</div>';
    echo '</body>';
    echo '</html>';
    header("Refresh: 2; url=login.php");
    exit;
}

if(!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = -1;
    $_SESSION['last_access'] = time();
}

$_SESSION['visit_count']++;

date_default_timezone_set('Asia/Singapore');

$current_time = time();
$current_time_formatted = date('m-d-y', $current_time);

$last_access_time = isset($_SESSION['last_access']) ? $_SESSION['last_access'] : 0;
$last_access_time_formatted = date('h:i:s A', $last_access_time);

$_SESSION['last_access'] = $current_time;
?>

<html>
    <head>
        <title>Welcome Page</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <style>
            .signout_btn {
            background: #FF4742;
            border: 1px solid #FF4742;
            border-radius: 6px;
            box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: nunito,roboto,proxima-nova,"proxima nova",sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 16px;
            min-height: 40px;
            outline: 0;
            padding: 12px 14px;
            text-align: center;
            text-rendering: geometricprecision;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            text-decoration: none;
            width: 240px;
            }

            .signout_btn:hover,
            .signout_btn:active {
            background-color: initial;
            background-position: 0 0;
            color: #FF4742;
            text-decoration: none;
            }

            .signout_btn:active {
            opacity: .5;
            text-decoration: none;
            }

            .reset_btn {
            background: #228B22;
            border: 1px solid #228B22;
            border-radius: 6px;
            box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: nunito,roboto,proxima-nova,"proxima nova",sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 16px;
            min-height: 40px;
            outline: 0;
            padding: 12px 14px;
            text-align: center;
            text-rendering: geometricprecision;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            text-decoration: none;
            width: 240px;
            }

            .reset_btn:hover,
            .reset_btn:active {
            background-color: initial;
            background-position: 0 0;
            color: #228B22;
            text-decoration: none;
            }

            .reset_btn:active {
            opacity: .5;
            text-decoration: none;
            }

            .rate_btn {
            background: #FFA500;
            border: 1px solid #FFA500;
            border-radius: 6px;
            box-shadow: rgba(0, 0, 0, 0.1) 1px 2px 4px;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: nunito, roboto, proxima-nova, "proxima nova", sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 16px;
            min-height: 40px;
            outline: 0;
            padding: 12px 14px;
            text-align: center;
            text-rendering: geometricprecision;
            text-transform: none;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            vertical-align: middle;
            text-decoration: none;
            width: 240px;
        }

        .rate_btn:hover,
        .rate_btn:active {
            background-color: initial;
            background-position: 0 0;
            color: #FFA500;
            text-decoration: none;
        }

        .rate_btn:active {
            opacity: .5;
            text-decoration: none;
        }


        </style>
    </head>
    <body>
        <div class="h-100 container d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <h1 class="text-center">Welcome <b><?php echo htmlspecialchars($_SESSION["full_name"]); ?></b></h1><hr>
                <h5 class="text-center">Session ID: <?php echo $session_id; ?></h5>
                <h5 class="text-center">You have visited: <?php echo $_SESSION['visit_count'];?>  times.</h5>
                <h5 class="text-center">Time of last access: <?php echo $last_access_time_formatted;?>.</h5>
            </div>
            <div class="mt-4 d-flex justify-content-center align-items-center">
                <a class="reset_btn" href="reset.php?$full_name">Change Password</a>
                <a class="signout_btn" href="logout.php" style="margin-left: 25px;">Sign Out</a>
                <a class="rate_btn" href="rating.php" style="margin-left: 25px;">Rate</a>

            </div>
        </div>

        <script>
            
        </script>

       
    </body>
</html>