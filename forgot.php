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
                        <form action="forgot-send.php" method="post">
                            <input type="email" name="email" id="email" class="form-control">
                            <input type="submit" value="Submit" class="mt-4 btn btn-primary w-100">
                        </form>
                        <a class="btn btn-secondary w-100" href="login.php">Go back</a>
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>