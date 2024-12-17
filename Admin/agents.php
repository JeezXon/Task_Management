<?php
require_once __DIR__ . '/../db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $agent_name = trim($_POST['agent_name']);
    $agent_uname = trim($_POST['agent_uname']);
    $agent_pass = $_POST['agent_pass'];

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT * FROM agents WHERE agent_uname = ?");
    $check_stmt->bind_param("s", $agent_uname);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<p style='color: red;'>Username already exists. Please choose a different username.</p>";
    } else {
        // Insert the new agent into the database
        $stmt = $conn->prepare("INSERT INTO agents (agent_name, agent_uname, agent_pass) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $agent_name, $agent_uname, $agent_pass);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Registration successful!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $check_stmt->close();
}



if (isset($_POST['delete'])) {
    $agent_id = $_POST['agent_id'];
    $stmt = $conn->prepare("DELETE FROM agents WHERE id=?");
    $stmt->bind_param("s", $agent_id);
    if ($stmt->execute()) {
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
    header("Location: Homepage.php?page=agents");
    exit();
}

$result = $conn->query("SELECT * FROM agents");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: blanchedalmond;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Press Start 2P', cursive;
        }

        form.f {
            display: flex;
            justify-content: center;
            align-items: left;
            width: 300px;
            border: 5px solid #654321;
            padding: 25px;
            border-radius: 10px;
            flex-direction: column;
            margin-top: 25px;
            background-color: #A87C41;
            box-shadow: 2px 2px 0 #000, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000;
        }

        button {
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        input{
            padding-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<div class="container">
    <form method="POST" action="" class="f">
        <h1>AGENTS</h1>
        <label>Agent Name:</label>
        <input name="agent_name" type="text" placeholder="Agent Name" required><br>
        <label>Agent Username:</label>
        <input name="agent_uname" type="text" placeholder="Agent Username" required><br>
        <label>Password:</label>
        <input name="agent_pass" type="password" placeholder="Password" required><br>  
        <button type="submit" name="action" value="add">Register</button>
    </form>
</div>


    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['agent_name']); ?></td>
                <td><?php echo htmlspecialchars($row['agent_uname']); ?></td>
                <td><?php echo htmlspecialchars($row['agent_pass']); ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="agent_id" value="<?php echo $row['id']; ?>" >
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $edit_id = (int)$_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM agents WHERE id=?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $edit_result = $stmt->get_result();
        $edit_row = $edit_result->fetch_assoc();
        $stmt->close();
    }
    ?>
</body>
</html>
