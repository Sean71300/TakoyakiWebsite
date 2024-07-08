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
    $remove = ['id', 'type', 'birthdate', 'age', 'reg_date'];
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

function edit($data)
{
    $conn = connect();

    $stmt = $conn->prepare("UPDATE registered SET full_name = ?, gender = ?, email = ?, phone_number = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $data['full_name'], $data['gender'], $data['email'], $data['phone_number'], $data['address'], $data['id']);

    if ($stmt->execute()) {
        echo "<h1>Record edited successfully!</h1><br>";

        echo "<a href='admin-search.php'><input type='button' style='width: 100' value='Back'></a>";
    } else {
        echo "Could not edit data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

function deleteRecord($id)
{
    $conn = connect();

    $stmt = $conn->prepare("DELETE FROM registered WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<h1>Record deleted successfully!</h1><br>";

        echo "<a href='admin-search.php'><input type='button' style='width: 100' value='Back'></a>";
    } else {
        echo "Could not delete data: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin | Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    // Search for records through ID
    if (isset($_POST['find'])) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $conn = connect();
            $stmt = $conn->prepare("SELECT * FROM registered WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $row = $res->fetch_assoc();

                $words = explode(' ', $row['full_name']);
                ?>

                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <table width="500" border="0" cellspacing="1" cellpadding="2">
                        <tr>
                            <td width="200">ID: </td>
                            <td><input name="id" type="text" id="id" value="<?php echo $row['id']; ?>" readonly></td>
                        </tr>
                        <tr>
                            <td width="200">First Name: </td>
                            <td><input name="first_name" type="text" id="first_name" value="<?php echo $words[0]; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Middle Name: </td>
                            <td><input name="middle_name" type="text" id="middle_name" value="<?php echo $words[1]; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Last Name: </td>
                            <td><input name="last_name" type="text" id="last_name" value="<?php echo $words[2]; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Gender: </td>
                            <td><input name="gender" type="text" id="gender" value="<?php echo $row['gender']; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Email: </td>
                            <td><input name="email" type="text" id="email" value="<?php echo $row['email']; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Phone Number: </td>
                            <td><input name="phone_number" type="text" id="phone_number" value="<?php echo $row['phone_number']; ?>"></td>
                        </tr>
                        <tr>
                            <td width="200">Address: </td>
                            <td><input name="address" type="text" id="address" value="<?php echo $row['address']; ?>"></td>
                        </tr>
                    </table><br>
                    <input type="submit" style="width: 100" id="edit" name="edit" value="Update">
                    <input type="submit" style="width: 100" id="delete" name="delete" value="Delete">
                    <a href="admin.php"><input type="button" style="width: 100" value="Cancel"></a><br><Br>
                    <table border="2" cellspacing="3" cellpadding="3">
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
                        ?>
                    </table>
                </form>

                <?php
                    $stmt->close();
                    $conn->close();
                } else {
                    echo "No matching records are found.";
                }
            }
        } elseif (isset($_POST['edit'])) {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $full_name = $_POST['first_name'] . ' ' . $_POST['middle_name'] . ' ' . $_POST['last_name'];
                $data = [
                    'id' => $_POST['id'],
                    'full_name' => $full_name,
                    'gender' => $_POST['gender'],
                    'email' => $_POST['email'],
                    'phone_number' => $_POST['phone_number'],
                    'address' => $_POST['address']
                    ];

                $checked = checkRecord($data);

                if ($checked['result'] == 0) {
                    edit($checked['array']);
                } else {
                    print_r($checked['duplicates']);
                }
            }
        } elseif (isset($_POST['delete'])) {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = $_POST['id'];
                deleteRecord($id);
            }
        } else {
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <table border="0" cellspacing="1" cellpadding="2">
                <tr>
                    <td><h1>Search</h1></td>
                    <td><h1>Registered Records</h1></td>
                </tr>
                <tr>
                    <td>ID: <input name="id" type="text" id="id"></td>
                    <td>
                        <table border="2" cellspacing="3" cellpadding="3">
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
                            <?php display(); ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input name="find" type="submit" id="find" value="Find ID" class="btn btn-success ml-3 mr-3" style="width:100px;">
                        <a href="admin.php" class="btn btn-danger ml-3 mr-3" style="width:100px;">Back</a>
                    </td>
                    
                </tr>
            </table>
        </form>
        <?php
        }
        ?>
    </body>
</html>