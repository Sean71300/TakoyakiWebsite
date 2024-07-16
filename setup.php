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

// -------------------------------------- CHECK FOR ID DUPLICATION -------------------------------------- //


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

// -------------------------------------- CUSTOMERS TABLE CREATION -------------------------------------- //
    function create_CustomersTable()
    {
        $conn = connect();


        $sql = "CREATE TABLE customers(
                customer_id INT(10) PRIMARY KEY,
                position VARCHAR(10),
                customer_img LONGBLOB,
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
            $img_path = "Images/Logo.jpg";
            $img_clean= file_get_contents($img_path);
            $customer_pic = mysqli_real_escape_string($conn, $img_clean);
            $id = generate_CustomerID();
            $password = 12345;
            $email = "tamayoyvonne0007@gmail.com";

            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO customers
                    (customer_id,position, customer_img, full_name,age,birthdate,gender,email,phone_number,address,password)
                    VALUES
                    ($id,'Client', '$customer_pic' ,'Maki',24,'','Male','$email','09123456789','Makati City','$hashed_pw')";

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
        
        $checkQuery = "SELECT customer_id FROM customers WHERE customer_id = ?";
        $genID = checkDuplication($genID,$checkQuery);
        $conn->close();
        return $genID;
    }
// -------------------------------------- EMPLOYEES TABLE CREATION -------------------------------------- //
    function create_EmployeesTable()
    {
        $conn = connect();

        $sql = "CREATE TABLE employees(
                employee_id INT(10) PRIMARY KEY,
                employee_img LONGBLOB,
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
            $img_path = "Images/logo.jpg";
            $img_clean= file_get_contents($img_path);
            $employee_pic = mysqli_real_escape_string($conn, $img_clean);
            $id = generate_EmployeeID();
            $password = "Hentoki@123";
            $email = "hentokitakoyaki.official@gmail.com";

            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO employees
                    (employee_id, employee_img, position,full_name,age,birthdate,gender,email,phone_number,address,password)
                    VALUES
                    ($id,'$employee_pic','Admin','Hentoki Owner',28,'','Female','$email','09987654321','Malabon City','$hashed_pw')";

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
        
        $checkQuery = "SELECT employee_id FROM employees WHERE employee_id = ?";
        $genID = checkDuplication($genID,$checkQuery);
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
            
            $checkQuery = "SELECT category_id FROM categories WHERE category_id = ?";
            $genID = checkDuplication($genID,$checkQuery);
            $conn->close();
            return $genID;
        }
// -------------------------------------- PRODUCTS TABLE CREATION -------------------------------------- //
function create_ProductTable()
{
    $conn = connect();

    $sql = "CREATE TABLE products(
            product_id INT(10) PRIMARY KEY AUTO_INCREMENT,
            product_img BLOB,
            product_name VARCHAR(50),
            category_id INT(10),
            category_type VARCHAR(100),
            status VARCHAR(30),
            price FLOAT(6),
            FOREIGN KEY (category_id) REFERENCES categories(category_id))";

    if (mysqli_query($conn, $sql))
    {
        // Prepare image data
        $imgArr = array(
            "Images/octobits.png", "Images/crab bits.png", "Images/cheese bits.png", "Images/bacon bits.png",
            "Images/assorted-barkada.png", "Images/cheesy-barkada.png", "Images/beef.png", "Images/pork.png"
        );

        $escapedImgDataArr = array();

        foreach ($imgArr as $imgPath) {
            $imgData = file_get_contents($imgPath);
            if ($imgData === false) {
                echo "Failed to read image: $imgPath";
                continue; 
            }
            $escapedImgDataArr[] = mysqli_real_escape_string($conn, $imgData);
        }

        // Generate IDs
        $id = generate_ProductID();
        $categoryID = generate_CategoryID();
        $catID = $categoryID - 4;

        // Insert products with image
        $sql = "INSERT INTO products
            (product_id, product_img, product_name, category_id, category_type, status, price)
            VALUES
            ($id, '{$escapedImgDataArr[0]}', 'Octo Bits', $catID , 'Takoyaki', 'Available', 39),
            (". (++$id) .", '{$escapedImgDataArr[1]}', 'Crab Bits', $catID , 'Takoyaki', 'Available', 39),
            (". (++$id) .", '{$escapedImgDataArr[2]}', 'Cheese Bits', $catID, 'Takoyaki', 'Available', 39),
            (". (++$id) .", '{$escapedImgDataArr[3]}', 'Bacon Bits', $catID, 'Takoyaki', 'Available', 39),
            (". (++$id) .", '{$escapedImgDataArr[0]}', 'Octo Bits', ". (++$catID) .", '10+1', 'Available', 100),
            (". (++$id) .", '{$escapedImgDataArr[1]}', 'Crab Bits', $catID , '10+1', 'Available', 100),
            (". (++$id) .", '{$escapedImgDataArr[2]}', 'Cheese Bits', $catID , '10+1', 'Available', 100),
            (". (++$id) .", '{$escapedImgDataArr[3]}', 'Bacon Bits', $catID , '10+1', 'Available', 100),
            (". (++$id) .", '{$escapedImgDataArr[4]}', 'Assorted Barkada', ". (++$catID) .", 'Barkada Platter', 'Available', 400),
            (". (++$id) .", '{$escapedImgDataArr[5]}', 'Cheesey Barkada', $catID, 'Barkada Platter', 'Available', 320),
            (". (++$id) .", '{$escapedImgDataArr[6]}', 'Beef Gyudon', ". (++$catID) .", 'Rice Meals', 'Available', 85),
            (". (++$id) .", '{$escapedImgDataArr[7]}', 'Pork Tonkatsun', $catID, 'Rice Meals', 'Available', 75)";

        mysqli_query($conn, $sql);

        // Check for errors
        if (mysqli_error($conn)) {
            echo "Error inserting image: " . mysqli_error($conn);
        }
    }
    else
    {
        echo "<br>There is an error in creating the table: " . mysqli_error($conn);
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

        $checkQuery = "SELECT product_id FROM products WHERE product_id = ?";
        $genID = checkDuplication($genID,$checkQuery);
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
                visibility_stat varchar(30) DEFAULT NULL,
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
                    (rating_id,customer_id,full_name,product_id,rating,comment, visibility_stat)
                    VALUES
                    ($ratingID,$custID,'Maki',$productID,4,'Yummy takoyaki', 'replied')";

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
        $checkQuery = "SELECT rating_id FROM ratings WHERE rating_id = ?";
        $genID = checkDuplication($genID,$checkQuery);
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
                phone_number VARCHAR(11),
                product_id INT(10),
                product_name VARCHAR(50),
                quantity INT(5),
                total_price FLOAT(6),
                transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
                FOREIGN KEY (product_id) REFERENCES products(product_id))";

        if (mysqli_query($conn, $sql))
        {
            $sql = "SELECT customer_id,full_name,phone_number FROM customers WHERE customer_id = 2024070000";

            $category = $conn->query($sql);
            $row = $category->fetch_assoc();

            $id = $row["customer_id"];
            $name = $row["full_name"];
            $phone_num = $row["phone_number"];

            $sql = "SELECT product_name FROM products WHERE product_id = 2024160000";

            $category = $conn->query($sql);
            $row = $category->fetch_assoc();

            $prod_name = $row["product_name"];
            $transacID = generate_TransactionID();
            $prodID = generate_ProductID();
            $productID = --$prodID;

            $sql = "INSERT INTO transaction_history
                    (transaction_id,customer_id,full_name,phone_number,product_id,product_name,quantity,total_price)
                    VALUES
                    ($transacID,$id,'$name','$phone_num',$productID,'$prod_name',3,320.00)";

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

        $checkQuery = "SELECT transaction_id FROM transaction_history WHERE transaction_id = ?";
        $genID = checkDuplication($genID,$checkQuery);
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

// -------------------------------------- REPLY TO RATINGS TABLE CREATION -------------------------------------- //

    function create_ReplytoCustomer()
    {
        $conn = connect();

        $sql = "CREATE TABLE IF NOT EXISTS ReplytoCustomer (
            rating_id INT(10), 
            reply VARCHAR(550),
            date_reply TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
            FOREIGN KEY (rating_id) REFERENCES ratings(rating_id) ON DELETE CASCADE
        )"; 

        if (mysqli_query($conn, $sql)) {
            $reply = "Thank you for your review! Hope to see you again in Hentoki!";
            $rate = generate_RatingID();
            $ratingID = --$rate;
            
            // Insert into the table
            $sql = "INSERT INTO ReplytoCustomer (
                    rating_id, 
                    reply) VALUES 
                    ($ratingID, 'aus ang sarap')";

            mysqli_query($conn, $sql);
            
        } else {
            echo "<br>There is an error in creating the table: " . $conn->error;
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