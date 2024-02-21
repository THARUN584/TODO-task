<?php
include 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (strlen($username) < 8 && !preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,}$/", $username)) {
        echo "Username should conatain atleast 8 characters";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    if (strlen($username) < 8 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        echo "special_characters_not_allowed";
        exit;
    }
    
  

    $email_check_sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $email_check_result = $conn->query($email_check_sql);

    if ($email_check_result->num_rows > 0) {
    
        echo "<script>alert('User already exist Please Login.');window.location='login.html';</script>";
    } else {
       
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
           
            $_SESSION['user_id'] = $conn->insert_id; 
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>
