<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            overflow: hidden; /* Prevent scrolling caused by video overflow */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f0f0f0; /* Fallback color */
            background-size: cover;
            background-position: center;
        }
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        video {
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .forgot-form-container {
            position: absolute;
            top: 50%;
            right: 25.8%; /* Adjust right spacing as needed */
            transform: translate(0, -50%);
            width: 50%; /* Adjust width as needed */
            max-width: 450px; /* Limit maximum width */
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
		        .notification {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Background MP4 Video -->
    <div class="video-container">
        <video autoplay muted loop>
            <source src="Images/forgot-bg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="forgot-form-container">
        <h2 class="text-center mb-4">Forgot Password</h2>
        <p class="text-muted">Enter your email address below. We will send you a link to reset your password.</p>
        <form action="send_reset_link.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>
	    <!-- Notification -->
    <div id="notification" class="notification">
        Email submitted successfully!
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            var email = document.getElementById('email').value;
            if (validateEmail(email)) {
                document.getElementById('notification').style.display = 'block';
                setTimeout(function() {
                    document.getElementById('notification').style.display = 'none';
                    document.getElementById('forgotForm').submit(); // Submit the form after notification
                }, 2000); // Notification display time in (2 seconds in this case)
            } else {
                alert('Please enter a valid email address.');
            }
        });

        function validateEmail(email) {
            // Simple validation to check for '@' and '.com'
            return email.includes('@') && email.includes('.com');
        }
    </script>

</body>
</html>
