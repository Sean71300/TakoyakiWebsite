<?php
function connect()
{
    // Configuration
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'hentoki_db';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function getColumnName()
{
    $dbname = "hentoki_db";
    $table = "customers";

    $conn = connect();
    // Run a simple query to get the table structure
    $result = mysqli_query($conn, "SELECT * FROM $table LIMIT 1");

    // Get the number of columns
    $fieldCount = $result->field_count;

    // Fetch column names
    $columns = [];
    $fields = $result->fetch_fields();
    
    foreach ($fields as $field) 
    {
        $columns[] = $field->name;
    }

    $conn->close();
    return $columns;
}

function checkRecord($row)
{
    // Remove specific columns
    $remove = ['id', 'position', 'birthdate', 'age', 'gender', 'reg_date'];
    $column = array_diff(getColumnName(), $remove);
    
    // Establish a single database connection
    $conn = connect();
    $rec = $row;
    $duplicates = [];

    // Loop through each column and check for duplicates
    foreach ($column as $columnName) {
        foreach ($rec as $val) {
            // Escape the value to prevent SQL injection
            $val = mysqli_real_escape_string($conn, $val);
            $sql = "SELECT * FROM customers WHERE $columnName = '$val'";
            $result = mysqli_query($conn, $sql);
            $numRow = mysqli_num_rows($result);

            if ($numRow > 0) {
                // Add to duplicates array if a duplicate is found
                $duplicates[$columnName][] = $val;
            }
        }
    }
    
    // Close the database connection
    $conn->close();

    // Return the result
    if (empty($duplicates)) {
        return [
            "result" => 0,
            "array" => $rec
        ];
    } else {
        $output = "";
        foreach ($duplicates as $field => $values) {
            $output .= "An existing record in " . $field . " already exist." . "<br>";
        }
        return [
            "result" => count($duplicates),
            "duplicates" => $output
        ];
    }
}

function generateID()
{
    $conn = connect();

    $query = "SELECT COUNT(*) as count FROM customers";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $rowCount = $row["count"];

    // Get the current year
    $currentYear = date("Y");
    $currentMonth = date("m");

    // Generate the ID
    $genID = (int)($currentYear . $currentMonth . str_pad($rowCount, 4, "0", STR_PAD_LEFT));

    $conn->close();
    return $genID;
}

function checkDuplication($id, $checkQuery) {
    $conn = connect();
    // Function to check for duplicate ID
    while (true) {
        // Prepare the query to check for the duplicate ID
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            break;
        }
        $id++;

        $stmt->close();
    }
    $stmt->close();
    $conn->close();
    return $id;
}

//function for password symbol checker
function specialChars($password) {
    return preg_match('/[^a-zA-Z0-9]/', $password) > 0;
}
//function for password lower and upper cases checker
function cases($password) { 
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z]).*$/', $password) === 1;
} 

function retainInput($fieldName, $type = 'text', $radioValue = '') {
    if (isset($_POST[$fieldName])) {
        $value = htmlspecialchars($_POST[$fieldName]);
        if ($type === 'radio') {
            if ($_POST[$fieldName] === $radioValue) {
                echo 'checked';
            }
        } else {
            echo $value;
        }
    }
}

function calculateAge($birthdate) {
    $dob = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($dob)->y;
    return $age;
}

function checkPhone($phone_number) {
    $phone_number = preg_replace('/\D/', '', $phone_number);

    if (strlen($phone_number) != 10 && strlen($phone_number) != 11) {
        return false;
    }
    $valid_area_codes = array('02', '032', '033', '034', '035', '036', '037', '038', '039', '041', '042', '043', '044', '045', '046', '047', '048', '049', '052', '053', '054', '055', '056', '057', '058', '059', '063', '064', '065', '066', '067', '068', '077', '078', '082', '083', '084', '085', '086', '087', '088', '089');
    if (substr($phone_number, 0, 1) != '0' && substr($phone_number, 0, 2) != '9' && !in_array(substr($phone_number, 0, 2), $valid_area_codes)) {
        return false;
    }


    return true;
}
?>

<script>
  function validateInput(input) {
    input.value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
  }

</script>

<?php
// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkQuery = "SELECT customer_id FROM customers WHERE customer_id = ?";
    // HOW TO USE THE CHECK UNIQUE ID
    $gen_id = checkDuplication(generateID(),$checkQuery);
    $type = "Client";
    $first_name = $_POST["firstN"];
    $middle_name = $_POST["middleN"];
    $last_name = $_POST["lastN"];
    $birthdate = $_POST["birthD"];
    $age = calculateAge($birthdate);
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $email_pattern = "/^[a-zA-Z0-0._%+-]+@[a-zA-Z0-9.-]+\\.com$/";

        $errors = 0;
        $error_display = "";

        if (!preg_match($email_pattern, $email) ) {
            $error_display = "Please enter a valid email address with a .com extension.";
            $errors++;
        }
        if(checkPhone($phone_number) == false){
            $error_display = "Invalid phone number, please check or try again.";
            $errors++;
        }
        if(strlen($password) < 8){
            $error_display = "Password should be more than 8 characters.";
            $errors++;
        }
        if(!specialChars($password)){
            $error_display = "Password should have special symbols.";
            $errors++;
        }
        if(cases($password)==0){
            $error_display = "Password should have a combination of upper and lower cases.";
            $errors++;
        } 
        if ($password != $confirm_password) {
            $error_display = "Passwords do not match";
            $errors++; 
        }  

        if ($errors == 0) {
            if ($password == $confirm_password) {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $full = $first_name . ' ' . $middle_name . ' ' . $last_name;
                $record = array($full,$email,$phone_number,$address);

                $res = checkRecord($record);

                if ($res["result"] == 0) {
                    $unique = $res['array'];
                    // Insert data into database
                    $conn = connect();
                    $sql = "INSERT INTO customers 
                            (customer_id, position, full_name, birthdate, age, gender, email, phone_number, address, password) 
                            VALUES 
                            ($gen_id, '$type', '$unique[0]', '$birthdate', $age, '$gender', '$unique[1]', '$unique[2]', '$unique[3]', '$hashed_password')";
                    if ($conn->query($sql) === TRUE) {
                        $message = "Successfully Registered!";
                        header("Refresh: 3; url=login.php");
                    } else {
                        echo "Error occured in connecting to the database, please try again.";
                    } 
                    $conn->close();
                } else {
                    $errors++;
                    $error_display = $res['duplicates'];
                }
            }
        }
    }
?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <style>
            body {
            background: url('images/register1-bg.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            }
        </style>
    </head>
    <body style = "height: auto">
        <div class="container1 w-50 m-5" style = "height: auto">
            <blockquote class="blockquote text-center mt-5">
                <h1>Registration Form</h1>
            </blockquote>

            <form action="<?php echo $_SERVER["PHP_SELF"]?>" id="registrationForm" method="post" class="w-100">
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>First Name:</p>
                    <input type="text" id="firstN" name="firstN" class="form-control" pattern="[A-Za-z\s]+" placeholder="ex. Juan" value="<?php retainInput('firstN'); ?>" required>
                </div>
                <div class="form-group">
                    <p>Middle Name:</p>
                    <input type="text" id="middleN" name="middleN" class="form-control" pattern="[A-Za-z\s]+" placeholder="ex. Marquez" value="<?php retainInput('middleN'); ?>">
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Last Name:</p>
                    <input type="text" id="lastN" name="lastN" class="form-control" pattern="[A-Za-z\s]+" placeholder="ex. Dela Cruz" value="<?php retainInput('lastN'); ?>" required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Birthdate:</p>
                    <input type="date" id="birthD" name="birthD" class="form-control" 
                        value="<?php echo isset($_POST['birthD']) ? htmlspecialchars($_POST['birthD']) : ''; ?>"
                        min="<?php echo date('Y-m-d', strtotime('-50 years')); ?>"
                        max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>"
                    required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Age:</p>
                    <input type="text" id="age" name="age" class="form-control" readonly>
                </div>
                <div class="form-group" style="margin-top:20px;">
                    <p><span class="text-danger font-italic">*</span>Gender:</p>
                    <input type="radio" name="gender" value="Male" <?php retainInput('gender', 'radio', 'Male'); ?> required>
                    <label for="male">Male</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="gender" value="Female" <?php retainInput('gender', 'radio', 'Female'); ?> required>
                    <label for="female">Female</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="gender" value="Others" <?php retainInput('gender', 'radio', 'Others'); ?> required>
                    <label for="female">Others</label>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Email:</p>
                    <input type="email" id="email" name="email" class="form-control" value= "<?php retainInput('email');?>" placeholder="local-part@domain" required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Phone Number:</p>
                    <input type="text" maxlength="11" id="phone_number" name="phone_number" class="form-control" value = "<?php retainInput('phone_number');?>" placeholder="09xxxxxxxxx" inputmode="numeric" required oninput="validateInput(this)">
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Address:</p>
                    <input type="text" id="address" name="address" class="form-control" value=  "<?php retainInput('address');?>" placeholder="ex. 123 Mabini Street Barangay San Lorenzo Makati City, Metro Manila 1223 Philippines" required>
                </div>
                <div class="form-group">
				    <p><span class="text-danger font-italic">*</span>Password:</p>
				    <div class="password-toggle" style="position: relative;">
				        <input id="password" type="password" name="password" class="form-control" required>
				        <span class="show-hide-icon clickable" onclick="togglePassword('password')" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
				            <i class="fas fa-eye"></i>
				        </span>
			        </div>
		        </div>
		        <div class="form-group">
		            <p><span class="text-danger font-italic">*</span>Confirm Password:</p>
                    <div class="password-toggle" style="position: relative;">
                        <input id="confirm_password" type="password" name="confirm_password" class="form-control" required>
                        <span class="show-hide-icon clickable" onclick="togglePassword('confirm_password')" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-center align-items-center">
                    <button type="submit" id="reg" class="btn btn-success ml-3 mr-3" style="width:100px;">Submit</button>
                    <button type="reset" id="clearForm" class="btn btn-secondary ml-3 mr-3" style="width:100px;">Clear</button>
                    <a href="login.php" type="button" class="btn btn-danger ml-3 mr-3" style="width:100px;">Cancel</a>
                </div>

                <?php
                if(!empty($message)){
                    echo '<div class="mt-4 alert alert-success">' . $message . '</div>';
                } 
                if(!empty($error_display)){
                    echo '<div class="mt-4 alert alert-danger">' . $error_display . '</div>';
                }        
                ?>
            </form>
        </div>
    </body>
    <script>
        document.getElementById('birthD').addEventListener('change', function() {
            var birthdate = this.value;
            if (birthdate) {
                var dob = new Date(birthdate);
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                
                // Adjust for the case where the birth month/day has not occurred yet this year
                var monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                document.getElementById('age').value = age;
            }
        });
            
        function togglePassword(inputId)
        {
            var passwordInput = document.getElementById(inputId);
            var icon = passwordInput.nextElementSibling.querySelector('i');

            if (passwordInput.type === "password")
            {
                passwordInput.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
            else
            {
                passwordInput.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</html>