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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_todo'])) {
    $todo_id = $_POST['delete_todo'];

    $sql = "DELETE FROM todos WHERE id='$todo_id' AND user_id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Todo deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    exit();
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="img">
        <div class="container">
            <div class="box">
                <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1><br>
                <h2>To-Do List:</h2><br>
                <form method="POST">
                    <div class="input-group">
                        <div class="input-field">
                            <input type="text" name="todo_text" id="newTodoText" placeholder="Enter new task" required>
                        </div>
                        <div class="input-field">
                            <button type="button" id="addTodoButton" class="input-submit">Add Todo</button>
                        </div>
                    </div>
                </form>
                <ul id="todoList">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<li id='todo{$row['id']}'><span class='todoText'>{$row['todo_text']}</span> <button class='editTodo' data-id='{$row['id']}'>Edit</button> | <button class='updateTodo' data-id='{$row['id']}' style='display: none;'>Update</button> | <button class='deleteTodo' data-id='{$row['id']}'>Delete</button></li>";
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

    <script>
   $(document).ready(function() {
 
    $(document).on('click', '.editTodo', function(e) {
        e.preventDefault();
        var todoId = $(this).data('id');
        var todoTextElement = $('#todo' + todoId + ' .todoText');
        var todoText = todoTextElement.text().trim();

       
        todoTextElement.html('<input type="text" class="editInput" value="' + todoText + '">');

    
        $(this).siblings('.updateTodo').show();
        $(this).hide();
    });

 
    $(document).on('click', '.updateTodo', function(e) {
        e.preventDefault();
        var todoId = $(this).data('id');
        var newText = $('#todo' + todoId + ' .editInput').val();
        var $updateButton = $(this);

        $.ajax({
            url: 'edit_todo.php',
            type: 'POST',
            data: { id: todoId, text: newText },
            success: function(response) {
                $('#todo' + todoId + ' .todoText').text(response);
                $updateButton.hide();
             
                $('#todo' + todoId + ' .editTodo').show();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
    

    $(document).on('click', '.deleteTodo', function(e) {
    e.preventDefault();
    var todoId = $(this).data('id');


    var confirmDelete = confirm("Are you sure you want to delete this todo?");
    if (confirmDelete) {
        $.ajax({
            url: 'dashboard.php',
            type: 'POST',
            data: { delete_todo: todoId },
            success: function(response) {
                $('#todo' + todoId).remove();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
      }
    });

   
    $('#addTodoButton').click(function(e) {
    e.preventDefault();
    var newText = $('#newTodoText').val();

        $.ajax({
            url: 'dashboard.php',
            type: 'POST',
            data: { add_todo: true, todo_text: newText },
            success: function(response) {
                location.reload(); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                }
            });
        });
    });
    </script>
</body>
</html>

