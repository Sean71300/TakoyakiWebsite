<?php
    session_start();
    include_once 'edit_functions.php';
    

    if (isset($_POST['ChangePass'])) {
        header("location: change_Pass.php");
    }
 

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {               
        if (editdata()==true)
        {
            $Confirmation ='<h4 class="mt-3 text-success">Record edited successfully!</h4><br>';  
            $passcheck = "";  
        }    
        else if (editdata()==false && pass_Check()==false)
        {
            $passcheck ='<h5 class="mb-3 text-danger">Incorrect password</h5>';
            $Confirmation = "";
           
        }
        else if (editdata()==false && pass_Check()==true)
        {
            $Confirmation ='<h4 class="mt-3 text-danger">Record unsuccesfully edited!</h4><br>';  
        }
    }
        $checker = false;
        $id = htmlspecialchars($_SESSION["id"]);
        $conn = mysqli_connect("localhost", "root", "", "hentoki_db");
        $sql = "SELECT customer_img FROM customers WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$id );
        $stmt->execute();                
        $result = $stmt->get_result();
        $row = $result->fetch_array();'"/>';
        $value = $row ["customer_img"];
        if ($value != null && $value != "")
        {
            $checker = true;
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
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
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
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"
                      enctype="multipart/form-data">
                    <div class="profile-picture mx-auto">
                        
                        <?php 

                            if ($checker == true)
                            {
                                echo '<img src="data:image/jpeg;base64,'.base64_encode($row['customer_img']).'" id="picture" class="picture"/>';
                            }
                            else if ($checker == false)
                            {
                                echo '<img src="Images/authentic.png" alt="picture" id="picture" class="picture">';
                            }
                        
                        ?>
                        
                     
                    </div>
                    <label for="Dpict" class="btn btn-success add-picture-button mt-3">
                        <input type="file" id="Dpict" name="Dpict" accept=".jpg, .png, .jpeg" onchange="profilePicture(this)" style="display:none;"> Add Picture
                    </label>                        
                </a>
                <a href="change_Pass.php">
                        <button class="btn btn-primary edit-profile-button" name="ChangePass">Change Password</button>
                </a>
            </div>
        </div>
        <div class="col-md-9">        
            <!-- Edit Profile Form -->
            <div class="profile-section">
            
                    <div class="mb-0">
                        <label for="name" class="form-label">User ID: <?php echo htmlspecialchars($_SESSION["id"])?></label>
                    </div>                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="custoname" onkeypress="return isLetter(event)" onpaste="return false" name="custoname" class="form-control" value="<?php echo search_Value("full_name",htmlspecialchars($_SESSION["id"]))?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"  value="<?php echo search_Value("email",htmlspecialchars($_SESSION["id"]))?>"
                               readonly>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="number" min="0"   id="phone" name="phone" class="form-control" value="<?php echo search_Value("phone_number",htmlspecialchars($_SESSION["id"]))?>"
                               required>
                    </div>
                    <div id="phoneValidationMessage" class="error text-danger"></div>
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Birthdate:</label>
                        <input type="date" id="BirthD" name="BirthD" class="form-control"  value="<?php echo search_Value("birthdate",htmlspecialchars($_SESSION["id"]))?>"
                               required>
                    </div>                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea type="text" id="address" name="address" class="form-control" rows="" ><?php echo search_Value("address",htmlspecialchars($_SESSION["id"]))?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Gender:</label>
                        <br>
                        <input type="radio" name="gender" value="Male" required <?php echo gender_check(search_Value("gender",htmlspecialchars($_SESSION["id"])),"Male")?>>
                        <label for="male">Male</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="Female" required <?php echo gender_check(search_Value("gender",htmlspecialchars($_SESSION["id"])),"Female")?>>
                        <label for="female">Female</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="gender" value="Others" required <?php echo gender_check(search_Value("gender",htmlspecialchars($_SESSION["id"])),"Others")?>>
                        <label for="female">Others</label>
                    </div>        
                    <div class="mb-3">
                        <label for="address" class="form-label">Enter Password to save changes:</label>
                        <input type="Password" id="pass" name="pass" class="form-control" rows="" autocomplete="new-password"></input> 
                        <?php echo $passcheck?>                       
                    </div>
                    <button type="submit" name="update" class="btn btn-primary save-changes-button">Save Changes</button>
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
<script>
    function isLetter(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122) && charCode !== 32) {
        return false;
      }
      return true;
    }
    function profilePicture(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("picture").src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                document.getElementById("picture").src = "#";
            }
    }
    const phoneNumberInput = document.getElementById('phone');
    const phoneValidationMessage = document.getElementById('phoneValidationMessage');

    phoneNumberInput.addEventListener('focusout', validatePhoneNumber);

    function validatePhoneNumber() {
      const phoneNumber = phoneNumberInput.value.replace(/\D/g, '');
      const allowedPrefixes = [
        '02', '032', '033', '034', '035', '036', '037', '038', '039',
        '041', '042', '043', '044', '045', '046', '047', '048', '049',
        '052', '053', '054', '055', '056', '057', '058', '059',
        '063', '064', '065', '066', '067', '068',
        '077', '078', '09',
        '082', '083', '084', '085', '086', '087', '088', '089'
      ];
      const phoneNumberPattern = /^[0-9]{10,13}$/;

      if (phoneNumberPattern.test(phoneNumber) && allowedPrefixes.some(prefix => phoneNumber.startsWith(prefix))) {
        phoneValidationMessage.classList.remove('error');
      } else {
        phoneValidationMessage.textContent = 'Please enter a valid phone number';
        phoneValidationMessage.classList.add('error');
      }
    }
  </script>
</body>
</html>
