<?php
include('config.php');
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'customer') {
    $customer_id = $_SESSION['user_id'];

    $sql = "SELECT cars.*, bookings.id AS booking_id
            FROM cars
            INNER JOIN bookings ON cars.id = bookings.car_id
            WHERE bookings.customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="car-container">';
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            // Start a new row for every five booked cars
            if ($counter % 5 == 0) {
                echo '<div style="display: flex;">';
            }

            // Display booked car details
            echo '<div class="car-box">';
            echo '<img src="' . $row['image'] . '" alt="Booked Car Image" style="max-width: 100%; max-height: 150px;"><br>';
            echo "Model: " . $row['model'] . "<br>";
            echo "Number: " . $row['number'] . "<br>";
            echo "Capacity: " . $row['capacity'] . "<br>";
            echo "Rent: " . $row['rent'] . "<br>";
            echo "Booking ID: " . $row['booking_id'] . "<br>";
            echo '</div>';

            // End the row for every five booked cars
            if ($counter % 5 == 4) {
                echo '</div>';
            }

            $counter++;
        }

        // If the last row is not complete, close it
        if ($counter % 5 != 0) {
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo "No bookings found for the user.";
    }

    $stmt->close();
} else {
    echo "Unauthorized access";
}

$conn->close();
?>
