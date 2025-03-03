<?php
// Database connection
$host = "localhost"; // Change if your database is hosted elsewhere
$user = "root"; // Your MySQL username
$pass = ""; // Your MySQL password
$dbname = "resfeast"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

// Insert into database
$sql = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Signup successful! Please Login!');
            window.location.href = 'login.php'; // Redirect to login page
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
