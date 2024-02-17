<?php
include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_todo'])) {
    $todo_text = $_POST['todo_text'];

    $sql = "INSERT INTO todos (user_id, todo_text) VALUES ('$user_id', '$todo_text')";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_GET['delete_todo'])) {
    $todo_id = $_GET['delete_todo'];

    $sql = "DELETE FROM todos WHERE id='$todo_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM todos WHERE user_id='$user_id'";
$result = $conn->query($sql);

if ($result === FALSE) {
    echo "Error fetching todos: " . $conn->error;
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style1.css">
    
</head>
<body>
    <div class="img">
    <div class="container">
    <div class="box">
              
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <h2>To-Do List:</h2>
        <form method="POST">
            <div class="input-group">
                <div class="input-field">
            <input type="text" name="todo_text" placeholder="Enter new task" required>
        </div>
<div class="input-field">
            <button type="submit" class="input-submit" name="add_todo">Add Todo</button>
</div>
            </div>
        </form>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['todo_text']} <a href='dashboard.php?delete_todo={$row['id']}'>Delete</a></li>";
                }
            } else {
                echo "<li>No todos yet.</li>";
            }
            ?>
        </ul>
        <a href="logout.php">Logout</a>
    </div>
    </div>
    </div>
</body>
</html>
