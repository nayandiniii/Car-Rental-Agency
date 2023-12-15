<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'customer') {
        $customer_id = $_SESSION['user_id'];
        $car_id = mysqli_real_escape_string($conn, $_POST['car_id']);

        // Assuming you have a table named 'bookings' with columns 'id', 'car_id', 'customer_id', 'start_date', 'end_date'
        $sql = "INSERT INTO bookings (car_id, customer_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ii", $car_id, $customer_id);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Car booked successfully!";
                header("Location: view_booked_cars.php?booking_id=" . $stmt->insert_id);
                exit();
            } else {
                echo "Error executing SQL statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    } else {
        echo "Unauthorized access"; // This would happen if the user is not logged in as a customer
    }
}

$conn->close();
?>
