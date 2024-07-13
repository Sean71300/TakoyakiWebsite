<?php
    session_start();
    include_once 'customer_functions.php';
    $Confirmation = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        editdata();
        $Confirmation='<h5 class="mt-3">Record Edited successfully!</h5><br>';
    }
?>

<html>
<head>
    <title>Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="customCodes/custom.css">
    <style>
        /* Custom styles */

        .logo {
            display: inline-block;
            max-width: 50px;
            width: auto;
            height: auto;
            border-radius: 50%;
            margin-left: 2%;
        }

        .footer {
            display: inline-block;
            max-width: 50px;
            width: auto;
            height: auto;
            border-radius: 50%;
        }

        .footer1 {
            display: inline-block;
            max-width: 35px;
            width: auto;
            height: auto;
        }

        body {
            background-color: #f8f9fa; /* Light gray background */
			min-height: 100vh; /* Full height background */

        }

        .profile-section {
            background-color: #fff; /* White background */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
            position: relative;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .edit-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }

        .add-picture-button,
        .edit-profile-button {
            margin-top: 10px;
            width: 100%;
        }

        .add-picture-button {
            background-color: #e0a800; /* color, can be customized */
            border-color: #e0a800;
            color: #fff; /* White text color */
        }

        .add-picture-button:hover {
            background-color: #ed7b7a;
            border-color: #ed7b7a;
        }

        .edit-profile-button {
            background-color: #eb5757; /* Red color */
            border-color: #eb5757;
        }

        .edit-profile-button:hover {
            background-color: #ed7b7a; /* Darker red on hover */
            border-color: #ed7b7a;
        }

        .save-changes-button {
            background-color: #e0a800;
            border-color: #e0a800;
            width: 100%;
        }

        .save-changes-button:hover {
            background-color: #ed7b7a;
            border-color: #ed7b7a;
        }
        .form-control:focus {
            border-color: #eb5757; /* Red border color on focus */
            box-shadow: 0 0 0 0.2rem rgba(235, 87, 87, 0.25); /* Red box shadow on focus */
        }        
        .loading-screen {
		  position: fixed;
		  top: 0;
		  left: 0;
		  width: 100%;
		  height: 100%;
		  display: flex;
		  background-color:white;
		  justify-content: center;
		  align-items: center;
		  opacity:1;
		  transition: opacity 1s ease-in-out;
		  z-index: 999;
		}
        .loading-screen img {
		  animation: spin 1s linear forwards;
		}
        @keyframes spin {
		  from { transform: rotate(0deg); }
		  to { transform: rotate(1000deg); }
		}
    </style>
</head>
<body class="bg-body-tertiary">
    <script>
        window.onload = function() {
        const loadingScreen = document.querySelector('.loading-screen');
        loadingScreen.style.opacity = 0;
        setTimeout(() => {
            loadingScreen.remove();
            document.body.style.display = 'block';
        }, 1000);
        }
    </script>
    <div class="loading-screen">
        <img src="Images/loading.png" alt="Loading...">
    </div>
<!-- Navbar -->
<?php include "Navigation.php"?>      

<!-- Edit Profile Section -->
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Picture -->
            <div class="profile-section text-center">
                <div class="profile-picture mx-auto">
                    <img src="Images/authentic.png" alt="Profile Picture">
                    <div class="edit-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                      enctype="multipart/form-data">
                    <label for="profile_picture" class="btn btn-success add-picture-button mt-3">
                        <input type="file" id="profile_picture" name="profile_picture" style="display:none;"> Add Profile
                    </label>  
                </form>
                <a href="reset.php">
                    <button class="btn btn-primary edit-profile-button mt-1 ">Change Password</button>
                </a>
            </div>
        </div>
        <div class="col-md-9">        
            <!-- Edit Profile Form -->
            <div class="profile-section">
                <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="POST" id="CustomerEdit">
                
                    <div class="mb-0">
                        <label for="name" class="form-label">User ID: <?php echo htmlspecialchars($_SESSION["id"])?></label>
                    </div>                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="custoname" name="custoname" class="form-control" value="<?php echo display_Value("full_name",htmlspecialchars($_SESSION["id"]))?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"  value="<?php echo display_Value("email",htmlspecialchars($_SESSION["id"]))?>"
                               readonly>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control"   value="<?php echo display_Value("phone_number",htmlspecialchars($_SESSION["id"]))?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Birthdate:</label>
                        <input type="date" id="BirthD" name="BirthD" class="form-control"  value="<?php echo display_Value("birthdate",htmlspecialchars($_SESSION["id"]))?>"
                               required>
                    </div>                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea type="text" id="address" name="address" class="form-control" rows="" ><?php echo display_Value("address",htmlspecialchars($_SESSION["id"]))?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Gender:</label>
                        <br>
                        <input type="radio" name="gender" value="Male" required <?php echo gender_check(display_Value("gender",htmlspecialchars($_SESSION["id"])),"Male")?>>
                        <label for="male">Male</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="Female" required <?php echo gender_check(display_Value("gender",htmlspecialchars($_SESSION["id"])),"Female")?>>
                        <label for="female">Female</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="Others" required <?php echo gender_check(display_Value("gender",htmlspecialchars($_SESSION["id"])),"Others")?>>
                        <label for="female">Others</label>
                    </div>        
                    <button type="submit" class="btn btn-primary save-changes-button">Save Changes</button>
                    <?php echo $Confirmation?>
                </form>
            </div>
        </div>
    </div>
</div>

      <!-- Footer -->
    <?php include "Footer.php"?>      
<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>