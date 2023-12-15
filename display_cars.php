<?php
include('config.php');

// Assuming you have a table named 'cars' with columns 'id', 'model', 'number', 'capacity', 'rent', 'agency_id', 'image'
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        // Start a new row for every five cars
        if ($counter % 5 == 0) {
            echo '<div style="display: flex;">';
        }

        // Display car details
        echo '<div style="margin: 10px; padding: 10px; border: 1px solid #ccc; text-align: center;">';
        echo '<img src="' . $row['image'] . '" alt="' . $row['model'] . '" style="max-width: 100%; max-height: 150px;"><br>';
        echo "Model: " . $row['model'] . "<br>";
        echo "Number: " . $row['number'] . "<br>";
        echo "Capacity: " . $row['capacity'] . "<br>";
        echo "Rent: " . $row['rent'] . "<br>";
        // Add a button for booking
        echo '<form action="add_booking.php" method="post">';
        echo '<input type="hidden" name="car_id" value="' . $row['id'] . '">';
        echo '<input type="submit" value="Book">';
        echo '</form>';
        echo '</div>';
        // End the row for every five cars
        if ($counter % 5 == 4) {
            echo '</div>';
        }

        $counter++;
    }

    // If the last row is not complete, close it
    if ($counter % 5 != 0) {
        echo '</div>';
    }
} else {
    echo "No cars found.";
}

$conn->close();
?>
