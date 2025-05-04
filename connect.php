<?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $conn = new mysqli("localhost", "root", "", "project");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $stmt = $conn->prepare("insert into message(name, email, subject, message)
    values(?, ?, ?, ?)");;
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        $stmt->execute();
        echo "New record created successfully";
        $stmt->close();
        $conn->close();
    }
?>
