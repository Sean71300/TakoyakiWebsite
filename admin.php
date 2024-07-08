<?php
session_start();

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
?>
<table></table>
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

function display()
{
    $conn = connect();
    $sql = "SELECT * FROM registered";
    $retval = $conn->query($sql);

    if (!$retval) {
        echo "Error: " . $conn->error;
    } else {
        if ($retval->num_rows > 0) {
            while ($row = $retval->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["birthdate"] . "</td>";
                echo "<td>" . $row["age"] . "</td>";
                echo "<td>" . $row["gender"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone_number"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["reg_date"] . "</td>";
                echo "</tr>";
            }
        }
    }
    $conn->close();
}

function validateData($arr, $pass, $confPass)
{
    $errors = array();

    foreach ($arr as $value);
    {
        if (empty($value))
        {
            $errors[] = "$value is required";
            echo "$value";  
        }
        else if ($pass != $confPass)
        {
            $errors[] = "Passwords do not match";
        }
    }

    return array(
        "error" => count($errors), 
        "err" => $errors, 
        "array" => $arr);
}

function getColumnName()
{
    $dbname = "registration_db";
    $table = "registered";

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
    $remove = ['id', 'type', 'birthdate', 'age', 'gender', 'reg_date'];
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
            $sql = "SELECT * FROM registered WHERE $columnName = '$val'";
            $result = mysqli_query($conn, $sql);
            $numRow = mysqli_num_rows($result);

            if ($numRow > 0) {
                // Add to duplicates array if a duplicate is found
                $duplicates[] = "The $columnName: '$val' already exists";
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
        return [
            "result" => count($duplicates),
            "duplicates" => $duplicates
        ];
    }
}


function generateID()
{
    $conn = connect();

    $query = "SELECT COUNT(*) as count FROM registered";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $rowCount = $row["count"];

    // Get the current year
    $currentYear = date("Y");

    // Generate the ID
    $genID = (int)($currentYear . str_pad(101, 3, "0", STR_PAD_LEFT) . str_pad($rowCount, 3, "0", STR_PAD_LEFT));

    $conn->close();
    return $genID;
}

function calculateAge($birthdate) 
{
    $birthDate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}
?>
<?php
// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gen_id = generateID();
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
            $sql = "INSERT INTO registered 
                    (id, type, full_name, birthdate, age, gender, email, phone_number, address, password) 
                    VALUES 
                    ($gen_id, '$type', '$unique[0]', '$birthdate', $age, '$gender', '$unique[1]', '$unique[2]', '$unique[3]', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='text-align: center'>Registered successfully!</p>";
                //header("Refresh: 2; url=login.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        } else {
            $err = $res['duplicates'];
            print_r($err);
        }
    } else {
        echo "Error: Passwords do not match";
    }
}
?>

<html>
    <head>
        <title>Admin | Menu</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <!-- Display Form -->
        <table>
            <tr>
                <td>
                    <h1 style = "font-family: Times New Roman">Registration Form</h1>
                    <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" class="w-100">
                        <div class="form-group">
                            <p>First Name:</p>
                            <input type="text" id="firstN" name="firstN" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Middle Name:</p>
                            <input type="text" id="middleN" name="middleN" class="form-control">
                        </div>
                        <div class="form-group">
                            <p>Last Name:</p>
                            <input type="text" id="lastN" name="lastN" class="form-control" required>
                        </div>
                        <div>
                            <p>Birthdate:</p>
                            <input type="date" id="birthD" name="birthD" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Gender:</p>
                            <input type="radio" name="gender" value="Male" required>
                            <label for="male">Male</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="gender" value="Female" required>
                            <label for="female">Female</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="gender" value="Others" required>
                            <label for="female">Others</label>
                        </div>
                        <div class="form-group">
                            <p>Email:</p>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Phone Number:</p>
                            <input type="tel" id="phone_number" name="phone_number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Address:</p>
                            <input type="text" id="address" name="address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Password:</p>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <p>Confirm Password:</p>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="form-group mt-5 d-flex justify-content-center align-items-center">
                            <button type="submit" id="reg" class="btn btn-success ml-3 mr-3" style="width:100px;">Submit</button>
                            <button type="reset" id="clear" class="btn btn-secondary ml-3 mr-3" style="width:100px;">Clear</button>
                            <a href="admin-search.php" class="btn btn-success ml-3 mr-3" style="width:100px;">Search</a>
                            <a href="logout.php" class="btn btn-danger ml-3 mr-3" style="width:100px;">Logout</a>
                        </div>
                    </form>
                </td>
                <td valign = "top">
                    <!-- File Handling -->
                    <h1>Registered Records</h1>
                    <table border = "2" cellspacing = "3" cellpadding = "3">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Full Name</th>
                            <th>Birthdate</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Date of Registration</th>
                        </tr>
                        
                        <!-- Display records -->
                        <?php
                            display();
                        ?>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>