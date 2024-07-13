<?php
    include 'setup.php';

    function get_Employee($employee_id) 
    {
        $conn = connect();

        $query = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
        $query->bind_param("i", $employee_id);
        $query->execute();
        $result = $query->get_result();
        $employee = $result->fetch_assoc();

        echo json_encode($employee);

        $query->close();
        $conn->close();
    }

    if (isset($_GET['employee_id'])) 
    {
        get_Employee($_GET['employee_id']);
    }
?>
