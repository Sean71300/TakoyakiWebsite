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

function firstConnect()
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
        $sql = "CREATE TABLE registered(
        id INT(10)  PRIMARY KEY,
        type VARCHAR(8), 
        full_name VARCHAR(70), 
        birthdate DATE, 
        age INT (3), 
        gender VARCHAR(10), 
        email VARCHAR(50), 
        phone_number VARCHAR(11),
        address VARCHAR(200), 
        password VARCHAR(255), 
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (mysqli_query(connect(), $sql))
        {
            $id = generateID();
            $password = 12345;
            $email = "dimaano@educue.com";
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO registered 
                    (id, type, full_name, birthdate, age, gender, email, phone_number, address, password) 
                    VALUES 
                    ($id,'Admin','Maam Kathleen Dimaano','',45,'Female','$email','09876543211','Valenzuela City','$hashed_password')";

            mysqli_query(connect(), $sql);

            echo "<br>The table registered is created successfully.";
        }
        else
        {
            echo "<br>There is an error in creating the table: " . connect()->connect_error;
        }
    }

    // ------------------------------------------------New Addition---------------------------------
    function createProducttable(){
        $query = " CREATE TABLE product(
            product_id INT(10) PRIMARY KEY,
            product_name VARCHAR(70)
            
            )";
                if (mysqli_query(connect(), $query))
                {
                    echo "<br> The product table is created successfully.";
                }else
                {
                    echo "<br> The is an error in creating table: " . connect() ->connect_error;
                }

    }
    
    function createRatingtable(){
        $query = " CREATE TABLE rating(
            id INT(10),
            full_name VARCHAR(70),
            product_id INT(10),
            star INT(5), 
            rate_date  TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            comment VARCHAR(100),
            FOREIGN KEY (id) REFERENCES registered(id),
            FOREIGN KEY (product_id) REFERENCES product(product_id)
            )";
                if (mysqli_query(connect(), $query))
                {
                    echo "<br> The rating table is created successfully.";
                }else
                {
                    echo "<br> The is an error in creating table: " . connect() ->connect_error;
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
?>

<?php
firstConnect();

// Check if the database exists
$db_check_query = "SHOW DATABASES LIKE 'registration_db'";
$result = mysqli_query(firstConnect(), $db_check_query);

if (mysqli_num_rows($result) == 0) {
    createDB();
}

// Check if the table exists
$table_check_query = "SHOW TABLES LIKE 'registered'";
$result = mysqli_query(connect(), $table_check_query);

if (mysqli_num_rows($result) == 0) {
    createTable();
}

$table_product_check_query = "SHOW TABLES LIKE 'product'";
$result = mysqli_query(connect(), $table_product_check_query);

if(mysqli_num_rows($result)==0){
    createProducttable();
}

$table_rate_check_query = "SHOW TABLES LIKE 'rating'";
$result = mysqli_query(connect(), $table_rate_check_query);

if (mysqli_num_rows($result) == 0) {
    createRatingtable();
}


?>