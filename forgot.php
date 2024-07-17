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

    <body>
        <div class="container-fluid d-flex flex-row justify-content-center align-items-center w-100" style="height: 100vh">
            <div class="col d-flex flex-row justify-content-center align-items-center h-100 w-100" style="margin-left: 300px;"> 
                <center>
                <video width="85%" height="95%" autoplay muted loop>
                    <source src="Images/ipad-takoyaki.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                </center>
            </div>
            
            <div class="col w-100 h-100 d-flex flex-column justify-content-center align-items-center" style="margin-right: 300px;">
                <a href="index.php"><img src="Images/Logo.jpg" class="logo mb-5" alt=""></a>
                <div class="container-fluid w-75 d-flex align-items-center">
                    <div class="form-group border w-100 border-black p-5">
                        <h3 class="text-center mt-3 mb-3">Forgot Password</h3>
                        <p class="text-secondary">Enter your email address below. We will send you a link to reset your password.</p>
                        <p>Email:</p>
                        <input type="email" name="email" id="email" class="form-control">
                        <input type="submit" value="Submit" class="mt-4 btn btn-primary w-100">
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>