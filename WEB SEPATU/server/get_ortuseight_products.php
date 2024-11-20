<?php

include('connection.php');

// Check if connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the statement
$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='Ortuseight' LIMIT 4");

// Check if the statement preparation was successful
if ($stmt === false) {
    die("Failed to prepare the statement: " . $conn->error);
}

// Execute the statement
if (!$stmt->execute()) {
    die("Execution failed: " . $stmt->error);
}

// Get the result
$ortuseight_product = $stmt->get_result();

// Check if the execution was successful
if ($ortuseight_product === false) {
    die("Failed to get the result: " . $stmt->error);
}
