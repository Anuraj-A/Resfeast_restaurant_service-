<?php
// Database credentials
$servername = "localhost";  // your server
$username = "root";         // your username
$password = "";             // your password
$dbname = "resfeast";       // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session to handle login state
session_start();

// Check if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the form
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate the inputs
    if (empty($email) || empty($password)) {
        // Use output buffering to handle the alert before redirection
        echo "<script>alert('Please fill in both email and password.');</script>";
    } else {
        // Prepare the SQL query to find the user by email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("SQL query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);  // "s" means it's a string parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the email exists in the database
        if ($result->num_rows > 0) {
            // Fetch the user data from the result
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Login successful, store data in session
                $_SESSION['user_id'] = $user['id'];  // Store user ID in session
                $_SESSION['email'] = $user['email'];  // Store email in session
                
                // Redirect to the user's dashboard or home page after a short delay
                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'reservation.php';  // Redirect to reservation page
                      </script>";
            } else {
                // Incorrect password
                echo "<script>alert('Invalid password! Please try again.');</script>";
            }
        } else {
            // Email not found
            echo "<script>alert('No account found with that email address!');</script>";
        }

        // Close the prepared statement
        $stmt->close();
    }
    
    // Close the database connection
    $conn->close();
}
?>
