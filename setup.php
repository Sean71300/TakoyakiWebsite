<?php
// -------------------------------------- CONNECTION -------------------------------------- //
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
// -------------------------------------- DATABASE CREATION -------------------------------------- //
    function createDB()
    {
        // Configuration
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '';

        // Create connection
        $conn = new mysqli($db_host, $db_username, $db_password);

        // Check connection
        if ($conn->connect_error) 
        {
            die("Connection failed: " . $conn->connect_error);
        }

        // Creating database
        $sql = "CREATE DATABASE hentoki_db";

        if ($conn->query($sql) === TRUE) 
        {
        } 
        else 
        {
            echo "There is an error in creating the database: " . $conn->error;
        }

        $conn->close();
    }
// -------------------------------------- CUSTOMERS TABLE CREATION -------------------------------------- //
    function create_CustomersTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE customers(
                customer_id INT(10) PRIMARY KEY,
                position VARCHAR(10),
                customer_img BLOB,
                full_name VARCHAR(100),
                age INT(3),
                birthdate DATE,
                gender VARCHAR(10),
                email VARCHAR(50),
                phone_number VARCHAR(11),
                address VARCHAR(300),
                password VARCHAR(255),
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

        if (mysqli_query($conn, $sql))
        {
            $id = generate_CustomerID();
            $password = 12345;
            $email = "tamayoyvonne0007@gmail.com";

            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers
                    (customer_id,position,full_name,age,birthdate,gender,email,phone_number,address,password)
                    VALUES
                    ($id,'Client','Maki',24,'','Male','$email','09123456789','Makati City','$hashed_pw')";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }

    function position(){

        return Client;
    }
// -------------------------------------- CUSTOMER ID CREATION -------------------------------------- //
    function generate_CustomerID()
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
// -------------------------------------- EMPLOYEES TABLE CREATION -------------------------------------- //
    function create_EmployeesTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE employees(
                employee_id INT(10) PRIMARY KEY,
                employee_img BLOB,
                position VARCHAR(30),
                full_name VARCHAR(100),
                age INT(3),
                birthdate DATE,
                gender VARCHAR(10),
                email VARCHAR(50),
                phone_number VARCHAR(11),
                address VARCHAR(300),
                password VARCHAR(255),
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)";

        if (mysqli_query($conn, $sql))
        {
            $id = generate_EmployeeID();
            $password = 12345;
            $email = "myntchaos@gmail.com";

            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO employees
                    (employee_id,position,full_name,age,birthdate,gender,email,phone_number,address,password)
                    VALUES
                    ($id,'Admin','Maloi',24,'','female','$email','09987654321','Makati City','$hashed_pw')";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }
// -------------------------------------- EMPLOYEE ID CREATION -------------------------------------- //
    function generate_EmployeeID()
    {
        $conn = connect();

        $query = "SELECT COUNT(*) as count FROM employees";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $rowCount = $row["count"];

        // Get the current year
        $currentYear = date("Y");

        // Generate the ID
        $genID = (int)($currentYear . str_pad(5, 2, "0", STR_PAD_LEFT) . str_pad($rowCount, 4, "0", STR_PAD_LEFT));

        $conn->close();
        return $genID;
    }
    // -------------------------------------- CATEGORIES TABLE CREATION -------------------------------------- //
        function create_CategoryTable()
        {
            $conn = connect();
    
            $sql = "CREATE TABLE categories(
                    category_id INT(10) PRIMARY KEY,
                    category_type VARCHAR(100))";
    
            if (mysqli_query($conn, $sql))
            {
                $categoryID = generate_CategoryID();
    
                $sql = "INSERT INTO categories
                        (category_id,category_type)
                        VALUES
                        ($categoryID,'Takoyaki'),
                        (". (++$categoryID) .",'10+1'),
                        (". (++$categoryID) .",'Barkada Platters'),
                        (". (++$categoryID) .",'Rice Meals')";
    
                mysqli_query($conn, $sql);
            }
            else
            {
                echo "<br>There is an error in creating the table: " . $conn->connect_error;
            }
    
            $conn->close();
        }
// -------------------------------------- CATEGORY ID CREATION -------------------------------------- //
        function generate_CategoryID()
        {
            $conn = connect();
    
            $query = "SELECT COUNT(*) as count FROM categories";
            $result = $conn->query($query);
            $row = $result->fetch_assoc();
            $rowCount = $row["count"];
    
            // Get the current year
            $currentYear = date("Y");
    
            // Generate the ID
            $genID = (int)($currentYear . str_pad(3, 2, "0", STR_PAD_LEFT) . str_pad($rowCount, 4, "0", STR_PAD_LEFT));
    
            $conn->close();
            return $genID;
        }
// -------------------------------------- PRODUCTS TABLE CREATION -------------------------------------- //
     function create_ProductTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE products(
                product_id INT(10) PRIMARY KEY,
                product_img BLOB,
                product_name VARCHAR(50),
                category_id INT(10),
                category_type VARCHAR(100),
                status VARCHAR(30),
                price FLOAT(6),
                FOREIGN KEY (category_id) REFERENCES categories(category_id))";

        if (mysqli_query($conn, $sql))
        {
            $id = generate_ProductID();
            $categoryID = generate_CategoryID();
            $catID = $categoryID - 4;

            $sql = "INSERT INTO products
                (product_id, product_name, category_id, category_type, status, price)
                VALUES
                ($id, 'Octo Bits', $catID , 'Takoyaki', 'Available', 39),
                (". (++$id) .", 'Crab Bits', $catID , 'Takoyaki', 'Available', 39),
                (". (++$id) .", 'Cheese Bits', $catID, 'Takoyaki', 'Available', 39),
                (". (++$id) .", 'Bacon Bits', $catID, 'Takoyaki', 'Available', 39),
                (". (++$id) .", 'Octo Bits', ". (++$catID) .", '10+1', 'Available', 100),
                (". (++$id) .", 'Crab Bits', $catID , '10+1', 'Available', 100),
                (". (++$id) .", 'Cheese Bits', $catID , '10+1', 'Available', 100),
                (". (++$id) .", 'Bacon Bits', $catID , '10+1', 'Available', 100),
                (". (++$id) .", 'Assorted Barkada', ". (++$catID) .", 'Barkada Platter', 'Available', 400),
                (". (++$id) .", 'Cheesey Barkada', $catID, 'Barkada Platter', 'Available', 320),
                (". (++$id) .", 'Beef Gyudon', ". (++$catID) .", 'Rice Meals', 'Available', 85),
                (". (++$id) .", 'Pork Tonkatsun', $catID, 'Rice Meals', 'Available', 75)";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }
// -------------------------------------- PRODUCTS ID CREATION -------------------------------------- //  
    function generate_ProductID()
    {
        $conn = connect();

        $query = "SELECT COUNT(*) as count FROM products";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $rowCount = $row["count"];

        // Get the current year
        $currentYear = date("Y");

        // Generate the ID
        $genID = (int)($currentYear . str_pad(6, 2, "1", STR_PAD_LEFT) . str_pad($rowCount, 4, "0", STR_PAD_LEFT));

        $conn->close();
        return $genID;
    }   

// -------------------------------------- RATINGS TABLE CREATION -------------------------------------- //
    function create_RatingsTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE ratings(
                rating_id INT(10) PRIMARY KEY,
                customer_id INT(10),
                full_name VARCHAR(100),
                product_id INT(10),
                rating INT(2),
                comment VARCHAR(550), 
                rate_timedate TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
                FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
                FOREIGN KEY (product_id) REFERENCES products(product_id))";

        if (mysqli_query($conn, $sql))
        {
            $ratingID = generate_RatingID();
            $id = generate_CustomerID();
            $custID = --$id;
            $prodID = generate_ProductID();
            $productID = --$prodID;

            $sql = "INSERT INTO ratings
                    (rating_id,customer_id,full_name,product_id,rating,comment)
                    VALUES
                    ($ratingID,$custID,'Maki',$productID,4,'Yummy takoyaki')";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }
// -------------------------------------- RATING ID CREATION -------------------------------------- //
    function generate_RatingID()
    {
        $conn = connect();

        $query = "SELECT COUNT(*) as count FROM ratings";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $rowCount = $row["count"];

        // Get the current year
        $currentYear = date("Y");

        // Generate the ID
        $genID = (int)($currentYear . str_pad(8, 2, "1", STR_PAD_LEFT) . str_pad($rowCount, 4, "0", STR_PAD_LEFT));

        $conn->close();
        return $genID;
    }
    
// -------------------------------------- TRANSACTION TABLE CREATION -------------------------------------- //
    function create_TansactionTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE transaction_history(
                transaction_id INT(10) PRIMARY KEY,
                customer_id INT(10),
                full_name VARCHAR(100),
                total_price FLOAT(6),
                transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(customer_id))";

        if (mysqli_query($conn, $sql))
        {
            $id = generate_CustomerID();
            $custID = --$id;
            $transacID = generate_TransactionID();
            $prodID = generate_ProductID();
            $productID = --$prodID;

            $sql = "INSERT INTO transaction_history
                    (transaction_id,customer_id,full_name,total_price)
                    VALUES
                    ($transacID,$custID,'Maki',300.00)";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }
// -------------------------------------- TRANSACTION ID CREATION -------------------------------------- //
    function generate_TransactionID()
    {
        $conn = connect();

        $query = "SELECT COUNT(*) as count FROM transaction_history";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $rowCount = $row["count"];

        // Get the current year
        $currentYear = date("Y");

        // Generate the ID
        $genID = (int)($currentYear . str_pad(0, 2, "1", STR_PAD_LEFT) . str_pad($rowCount, 4, "0", STR_PAD_LEFT));

        $conn->close();
        return $genID;
    }

// -------------------------------------- SHIPPING ADDRESS TABLE CREATION -------------------------------------- //
    function create_ShippingAddressTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE shipping_address(
                customer_id INT(10) PRIMARY KEY,
                shipping_address VARCHAR(550), 
                FOREIGN KEY (customer_id) REFERENCES customers(customer_id))";

        if (mysqli_query($conn, $sql))
        {
            $ratingID = generate_RatingID();
            $id = generate_CustomerID();
            $custID = --$id;
            $prodID = generate_ProductID();
            $productID = --$prodID;

            $sql = "INSERT INTO shipping_address
                    (customer_id,shipping_address)
                    VALUES
                    ($custID,'Malabon City')";

            mysqli_query($conn, $sql);
        }
        else
        {
            echo "<br>There is an error in creating the table: " . $conn->connect_error;
        }

        $conn->close();
    }
?>

<?php
    // Check if the database exists
    $conn = new mysqli('localhost','root','');
    $db_check_query = "SHOW DATABASES LIKE 'hentoki_db'";

    $result = mysqli_query($conn, $db_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        createDB();

        $conn->close();
    }

    // Check if the table exists
    $conn = connect();
    $table_check_query = "SHOW TABLES LIKE 'customers'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_CustomersTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'employees'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_EmployeesTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'categories'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_CategoryTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'products'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_ProductTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'ratings'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_RatingsTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'transaction_history'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_TansactionTable();
    }

    // Check if the table exists
    $table_check_query = "SHOW TABLES LIKE 'shipping_address'";
    $result = mysqli_query($conn, $table_check_query);

    if (mysqli_num_rows($result) == 0) 
    {
        create_ShippingAddressTable();
    }

    $conn->close();
?>