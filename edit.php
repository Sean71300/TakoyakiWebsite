<?php
session_start();
include_once 'setup.php';
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
    </style>
</head>
<body class="bg-body-tertiary">

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">
            <img src="Images/Logo.jpg" class="logo ms-4 ms-lg-5" alt="Logo">
            <b>Hentoki</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav  ps-5 mb-2 mb-lg-0 col d-flex justify-content-between">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="About.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Pages.php">Personnel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Contact.php">Contact</a>
                </li>
                <?php
                if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="Login.php">Login</a>';
                    echo '</li>';
                } else {
                    echo '<li class="nav-item dropdown">';
                    echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">';
                    echo "Welcome " . htmlspecialchars($_SESSION["full_name"]);
                    echo '</a>';
                    echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                    echo '<li><a class="dropdown-item" href="edit.php">Edit Profile</a></li>';
                    echo '<li><a class="dropdown-item" href="rating.php">Rate</a></li>';
                    echo '<li><a class="dropdown-item" href="logout.php">Sign Out</a></li>';
                    echo '</ul>';
                    echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

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
                    <button class="btn btn-primary edit-profile-button mt-3">Edit Profile</button>
                </form>
            </div>
        </div>
        <div class="col-md-9">
            <!-- Edit Profile Form -->
            <div class="profile-section">
                <form action="update_profile.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="Current Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="user@example.com"
                               readonly>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="Current Phone Number"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" name="address" class="form-control" required>Current Address</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Enter new password">
                    </div>
                    <button type="submit" class="btn btn-primary save-changes-button">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

      <div class="forfooter">
        <div class="container-fluid text-light bg-black mt-5">          
          <div class="row ">
            <div class="col-12 text-center">
              <a href="index.php"><img src="Images/Logo.jpg" class="footer image-fluid my-4"></a>
            </div>           
            <div class="col-12">
              <div class="row">
                <div class="col-lg-3 col-md-1 col-sm-0"></div>
                <div class="col-lg-6 col-md-10 col-sm-12 d-flex justify-content-around pe-4">
                  <a href="index.php" class="text-decoration-none text-reset">Home</a>
                  <a href="About.php" class="text-decoration-none text-reset">About us</a>
                  <a href="Menu.php" class="text-decoration-none text-reset">Menu</a>
                  <a href="Contact.php" class="text-decoration-none text-reset">Contact</a>
                  <a href="Pages.php" class="text-decoration-none text-reset">Personnel</a>
                </div>
                <div class="col-lg-3 col-md-1 col-sm-0"></div>
              </div>              
            </div> 
            <div class="col-12 text-center my-3">
              <a href="https://www.facebook.com/hentokitakoyaki"><img src="Images/facebook.png" class="img-fluid footer1 m-2"></a>
              <a href="https://www.instagram.com/hentokitakoyaki/"><img src="Images/instagram.png" class="img-fluid footer1 m-2"></a> <br>  
              <span class="text-white-50">@copyright 2023 - hentoki</span>
            </div> 
          </div>          
        </div>
      </div>
<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
