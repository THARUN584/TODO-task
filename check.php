<?php
include 'config.php';
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $usernameExists = checkUsernameExists($username);

    if ($usernameExists) {
        echo "exists";
    } else {
        echo "Accepted";
    }
} else {
       echo "error";
}
    function checkUsernameExists($username) {
    global $conn;     
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
     if ($stmt) {
                  $stmt->bind_param("s", $username);
    
                  $stmt->execute();              
            $stmt->store_result();    
                        if ($stmt->num_rows > 0) {
                        return true;
           } else {              
                return false;
            }   

            $stmt->close();
        } else {
           
            return false;
        }
    }
?>
