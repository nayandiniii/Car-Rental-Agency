<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a form with fields 'model', 'number', 'capacity', 'rent', and 'image'
    $model = mysqli_real_escape_string($conn, $_POST["model"]);
    $number = mysqli_real_escape_string($conn, $_POST["number"]);
    $capacity = mysqli_real_escape_string($conn, $_POST["capacity"]);
    $rent = mysqli_real_escape_string($conn, $_POST["rent"]);

    // Get the agency_id from the session (assuming the user is logged in as an agency)
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'agency') {
        $agency_id = $_SESSION['user_id'];

        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not a valid image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // Insert car details into the 'cars' table using prepared statement to prevent SQL injection
        $sql = "INSERT INTO cars (model, number, capacity, rent, agency_id, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ssiiis", $model, $number, $capacity, $rent, $agency_id, $target_file);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Car added successfully!";
                header("Location: display_cars.html"); // Redirect to the page that displays all cars
                exit();
            } else {
                echo "Error executing SQL statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    } else {
        echo "Unauthorized access"; // This would happen if the user is not logged in as an agency
    }
}

$conn->close();
?>
