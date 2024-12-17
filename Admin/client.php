<?php
require_once __DIR__ . '/../db/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $client_name = $_POST['client_name'] ?? null;
        $contact_num = $_POST['contact'] ?? null;
        $email = $_POST['email'] ?? null;

        $stmt = $conn->prepare("INSERT INTO clients (Client_name, contact_num, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $client_name, $contact_num, $email);
        $stmt->execute();
        $stmt->close();
        header("refresh: 0.5;");
        exit();
    } elseif ($_POST['action'] == 'edit') {
        $client_id = $_POST['client_id'];
        $client_name = $_POST['client_name'] ?? null;
        $contact_num = $_POST['contact'] ?? null;
        $email = $_POST['email'] ?? null;

        $stmt = $conn->prepare("UPDATE clients SET Client_name=?, contact_num=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $client_name, $contact_num, $email, $client_id);
        $stmt->execute();
        $stmt->close();
        header("refresh: 0.5;");
        exit();
    }
}

if (isset($_POST['delete'])) {
    $client_id = $_POST['client_id'];
    $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
    $stmt->bind_param("i", $client_id);
    if ($stmt->execute()) {
        header("refresh: 0.5;");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

$result = $conn->query("SELECT * FROM clients"); // Ensure this query executes properly
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client</title>
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
            background-color:rgb(242, 242, 242);
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
        .sub{
            background-color:rgb(190, 27, 27);
        }
        sub:hover {
            background-color:rgb(23, 240, 255);
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="" class="f">
            <h1>CLIENTS</h1>
            <label>Client Name:</label>
            <input name="client_name" type="text" placeholder="Client Name" required><br>
            <label>Contact Number:</label>
            <input name="contact" type="text" placeholder="Contact Number" required><br>
            <label>Email:</label>
            <input name="email" type="email" placeholder="Email" required><br>
            <input type="hidden" name="action" value="add">
            <button name="sub" type="submit">Submit</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Client_name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_num']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="client_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="client_name" value="<?php echo htmlspecialchars($row['Client_name']); ?>">
                        <input type="hidden" name="contact" value="<?php echo htmlspecialchars($row['contact_num']); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($row['email']); ?>">
                        <button type="submit" name="delete">Delete</button>                      
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
