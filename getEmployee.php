<?php

    include 'setup.php';

    function get_Employee($employee_id) 
    {
        $conn = connect(); // Assuming connect() function is defined in setup.php to establish database connection

        // Prepare SQL query
        $query = $conn->prepare("SELECT employee_id, position, full_name, age, birthdate, gender, email, phone_number, address FROM employees WHERE employee_id = ?");
        $query->bind_param("i", $employee_id); // Bind employee_id parameter as integer

        // Execute query
        $query->execute();

        // Get result set
        $result = $query->get_result();

        // Fetch employee data as associative array
        $employee = $result->fetch_assoc();
    
        // Output JSON response
        header('Content-Type: application/json'); // Set JSON content type header
        echo json_encode($employee); // Encode the fetched data as JSON and output it

        // Clean up resources
        $query->close();
        $conn->close();
    }

    // Check if employee_id is provided via GET request
    if (isset($_GET['employee_id'])) 
    {
        get_Employee($_GET['employee_id']); // Call the function to fetch employee data
    } 
    else 
    {
        echo json_encode(array('error' => 'Employee ID not provided')); // Handle case where employee_id is not provided
    }
?>