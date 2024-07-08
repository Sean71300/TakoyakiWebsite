<?php
function connect()
{
    // Configuration
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';
    $db_name = 'registration_db';

    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function checkConnect()
{
    // Configuration
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';

    $DBconn = new mysqli($db_host, $db_username, $db_password);

    if ($DBconn->connect_error) {
        die("Connection failed: " . $DBconn->connect_error);
    }

    return $DBconn;
}

function createDB()
{
    // Configuration
    $db_host = 'localhost';
    $db_username = 'root';
    $db_password = '';

    // Create connection
    $DBconn = new mysqli($db_host, $db_username, $db_password);

    // Check connection
    if ($DBconn->connect_error) {
        die("Connection failed: " . $DBconn->connect_error);
    }

    // Creating database
    $sql = "CREATE DATABASE registration_db";

    if ($DBconn->query($sql) === TRUE) {
        echo "The database registration_db is created successfully.";
    } else {
        echo "There is an error in creating the database: " . $DBconn->error;
    }

    $DBconn->close();
}

function createTable()
{
    // Creating table
    $sql = "CREATE TABLE registered (
        id INT(10)  PRIMARY KEY,
        position VARCHAR(8), 
        username VARCHAR(50), 
        password VARCHAR(255), 
        full_name VARCHAR(50), 
        email VARCHAR(50), 
        phone_number VARCHAR(11),
        gender VARCHAR(10), 
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $conn = connect();
    if ($conn->query($sql) === TRUE) {
        echo "<br>The table registered is created successfully.";
    } else {
        echo "<br>There is an error in creating the table: " . $conn->error;
    }

    $conn->close();
}

function generateID()
{
    $conn = connect();

    $query = "SELECT COUNT(*) as count FROM registered";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $rowCount = $row['count'];

    // Get the current year
    $currentYear = date("Y");

    // Generate the ID
    $genID = (int)($currentYear . str_pad(101, 3, "0", STR_PAD_LEFT) . str_pad($rowCount, 3, "0", STR_PAD_LEFT));

    $conn->close();
    return $genID;
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
function is_valid_ph_phone_number($phone_number) {
    // Remove any non-numeric characters
    $phone_number = preg_replace('/\D/', '', $phone_number);

    // Check if the number is 10 or 11 digits long
    if (strlen($phone_number) != 10 && strlen($phone_number) != 11) {
        return false;
    }

    // Check if the number starts with '0', '9', or a valid area code
    $valid_area_codes = array('02', '032', '033', '034', '035', '036', '037', '038', '039', '041', '042', '043', '044', '045', '046', '047', '048', '049', '052', '053', '054', '055', '056', '057', '058', '059', '063', '064', '065', '066', '067', '068', '077', '078', '082', '083', '084', '085', '086', '087', '088', '089');
    if (substr($phone_number, 0, 1) != '0' && substr($phone_number, 0, 2) != '9' && !in_array(substr($phone_number, 0, 2), $valid_area_codes)) {
        return false;
    }

    return true;
}
?>

<html>
    <head>
        <title>Register</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container1 w-50">
            <blockquote class="blockquote text-center mt-5">
                <h1>Registration Form</h1>
            </blockquote>

            <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" class="w-100">
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>First Name:</p>
                    <input type="text" id="firstN" name="firstN" class="form-control"  pattern="[A-Za-z]+" placeholder="ex. Juan" value="<?php retainInput('firstN'); ?>"   required>
                </div>
                <div class="form-group">
                    <p>Middle Name:</p>
                    <input type="text" id="middleN" name="middleN" class="form-control"  pattern="[A-Za-z]+" placeholder="ex. Marquez" value="<?php retainInput('middleN'); ?>"  >
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Last Name:</p>
                    <input type="text" id="lastN" name="lastN" class="form-control"  pattern="[A-Za-z]+" placeholder="ex. Dela Cruz" value="<?php retainInput('lastN'); ?>"  required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Email:</p>
                    <input type="email" id="email" name="email" class="form-control"placeholder="ex. JuanMDC@gmail.com" value="<?php retainInput('email'); ?>"  required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Phone Number:</p>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control"placeholder="ex. 09428541236" value="<?php retainInput('phone_number'); ?>"  required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Username:</p>
                    <input type="text" id="username" name="username" class="form-control"placeholder="ex. DCJuan541" value="<?php retainInput('username'); ?>" required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Password:</p>
                    <input type="password" name="password" class="form-control"placeholder="ex. !MarDCruz" required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Confirm Password:</p>
                    <input type="password" name="confirm_password" class="form-control"placeholder="ex. !MarDCruz" required>
                </div>
                <div class="form-group">
                    <p><span class="text-danger font-italic">*</span>Gender:</p>
                    <input type="radio" name="gender" value="Male" value="<?php retainInput('Male'); ?>" required>
                    <label for="male">Male</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="gender" value="Female" value="<?php retainInput('Female'); ?>" required>
                    <label for="female">Female</label>
                </div>
                <div class="form-group d-flex justify-content-center align-items-center">
                    <button type="submit" id="reg" class="btn btn-success ml-3 mr-3" style="width:100px;">Submit</button>
                    <button type="reset" id="clear" class="btn btn-secondary ml-3 mr-3" style="width:100px;">Clear</button>
                    
                    <a href="login.php" type="button" class="btn btn-danger ml-3 mr-3" style="width:100px;">Cancel</a>
                </div>
            </form>

            <?php         
                // Check if the database exists
                $db_check_query = "SHOW DATABASES LIKE 'registration_db'";
                $result = mysqli_query(checkConnect(), $db_check_query);

                if (mysqli_num_rows($result) == 0) {
                    createDB();
                }

                // Check if the table exists
                $table_check_query = "SHOW TABLES LIKE 'registered'";
                $result = mysqli_query(connect(), $table_check_query);

                if (mysqli_num_rows($result) == 0) {
                    createTable();
                }

                //Check Pass Length
                

                // Form submission         
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $gen_id = generateID();
                    $full_name = $_POST['firstN'] . ' ' . $_POST['middleN'] . ' ' . $_POST['lastN'];
                    $email = $_POST["email"];
                    $position = "Client";
                    $phone_number = $_POST["phone_number"];
                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $confirm_password = $_POST["confirm_password"];
                    $gender = $_POST["gender"];

                    // Validate form data
                    $errors = array();
                    
                    if (empty($full_name)) {
                        $errors[] = "Full name is required";
                    }

                    if (!isset($position)) {
                        $errors[] = "Position is required";
                    }

                    if (empty($email)) {
                        $errors[] = "Email is required";
                    }

                    if (empty($phone_number)) {
                        $errors[] = "Phone number is required";
                    }

                    if (empty($username)) {
                        $errors[] = "Username is required";
                    }
                    //Start of password lenght checker
                    if(strlen($password) < 8){
                        $errors[] = "Password should be more than 8 characters";
                    }
                    //End of password lenght checker
                    //Start Password special symbol and upper case checker
                    if(!specialChars($password)){
                        $errors[] = "Password should have special symbols";
                    }                     
                    if(cases($password)==0){
                        $errors[] = "Password should have a combination of upper and lower cases";
                    }               
                    //End of Password special symbol and upper case checker

                    if (empty($password)) {
                        $errors[] = "Password is required";
                    }

                    if (empty($confirm_password)) {
                        $errors[] = "Confirm password is required";
                    }

                    if ($password != $confirm_password) {
                        $errors[] = "Passwords do not match";
                    }

                    if (!isset($gender)) {
                        $errors[] = "Gender is required";
                    }

                    if (count($errors) == 0) {
                        // Hash password
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // Insert data into database
                        $conn = connect();
                        $sql = "INSERT INTO registered ( id, position, username, password, full_name, email, phone_number, gender) 
                                VALUES (' $gen_id', '$position', '$username', '$hashed_password', '$full_name', '$email', '$phone_number', '$gender')";

                        if ($conn->query($sql) === TRUE) {
                            echo "<p style='text-align: center'>Registered successfully!</p>";
                            header("Refresh: 2; url=login.php");
                            
                            $_POST['firstN'] = '';
                            $_POST['middleN'] = '';
                            $_POST['lastN'] = '';
                            $_POST['position'] = '';
                            $_POST['birthD'] = '';
                            $_POST['email'] = '';
                            $_POST['phone_number'] = '';
                            $_POST['username'] = '';
                            $_POST['password'] = '';
                            $_POST['confirm_password'] = '';
                            $_POST['gender'] = '';

                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                        $conn->close();
                    } else {
                        echo "Error: " . implode("<br>", $errors);
                    }
                }
                ?>
        </div>
    </body>
</html>