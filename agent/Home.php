<?php

class Task {
    public $ticketNum;
    public $clientName;
    public $agentName;
    public $concern;
    public $severity;
    public $dateStart;
    public $dateFinish;
    public $status;

    public function __construct($ticketNum, $clientName, $agentName, $concern, $severity, $dateStart, $dateFinish, $status = null) {
        $this->ticketNum = $ticketNum;
        $this->clientName = $clientName;
        $this->agentName = $agentName;
        $this->concern = $concern;
        $this->severity = $severity;
        $this->dateStart = $dateStart;
        $this->dateFinish = $dateFinish;
        $this->status = $status;
    }
}

// AgentUser Class
class AgentUser {
    public $ticketNum;
    public $clientName;
    public $agentName;
    public $concern;
    public $severity;
    public $dateStart;
    public $dateFinish;
    public $status;

    public function __construct($ticketNum, $clientName, $agentName, $concern, $severity, $dateStart, $dateFinish, $status) {
        $this->ticketNum = $ticketNum;
        $this->clientName = $clientName;
        $this->agentName = $agentName;
        $this->concern = $concern;
        $this->severity = $severity;
        $this->dateStart = $dateStart;
        $this->dateFinish = $dateFinish;
        $this->status = $status;
    }

    // Save or Update the AgentUser record
    public function save($conn) {
        $checkSql = "SELECT * FROM agent_user WHERE ATicket_num = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $this->ticketNum);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $sql = "UPDATE agent_user SET 
                    Clients_Aname = ?, 
                    Agent_Aname = ?, 
                    Aconcern = ?, 
                    Aseverity = ?, 
                    Adate_start = ?, 
                    Adate_F = ?, 
                    Astatus = ? 
                    WHERE ATicket_num = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $this->clientName, $this->agentName, $this->concern, $this->severity, $this->dateStart, $this->dateFinish, $this->status, $this->ticketNum);
        } else {
            $sql = "INSERT INTO agent_user (ATicket_num, Clients_Aname, Agent_Aname, Aconcern, Aseverity, Adate_start, Adate_F, Astatus) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $this->ticketNum, $this->clientName, $this->agentName, $this->concern, $this->severity, $this->dateStart, $this->dateFinish, $this->status);
        }

        if (!$stmt->execute()) {
            echo "<p>Error processing record for Ticket $this->ticketNum: " . $stmt->error . "</p>";
        }
        $stmt->close();
        $checkStmt->close();
    }
}

require_once __DIR__ . '/../db/db_connect.php'; // Using your existing connection

$tasks = [];

$result = $conn->query("SELECT 
                        t.Ticket_tnum, 
                        c.Client_name, 
                        a.agent_name, 
                        t.tconcern, 
                        t.severity, 
                        t.date_start, 
                        t.date_end, 
                        au.Astatus 
                    FROM tasks t 
                    JOIN clients c ON t.client_tname = c.Client_name 
                    JOIN agents a ON t.agent_tname = a.agent_name 
                    LEFT JOIN agent_user au ON t.Ticket_tnum = au.ATicket_num");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = new Task($row['Ticket_tnum'], $row['Client_name'], $row['agent_name'], $row['tconcern'], $row['severity'], $row['date_start'], $row['date_end'], $row['Astatus']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    foreach ($_POST['Ticket_tnum'] as $index => $ticketNum) {
        $task = new AgentUser(
            $ticketNum,
            $_POST['Clients_Aname'][$index],
            $_POST['Agent_Aname'][$index],
            $_POST['Aconcern'][$index],
            $_POST['Aseverity'][$index],
            $_POST['Adate_start'][$index],
            $_POST['Adate_F'][$index],
            $_POST['status'][$index]
        );
        $task->save($conn);
    }
    header("refresh: 0.5;");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/bg.css">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-repeat: no-repeat;
            background-size: cover;
        }
        h1 {
            margin-top: 50px;
            font-family: 'Press Start 2P', cursive;
            animation: float 3s ease-in-out infinite;
            color:  #A87C41;
            text-shadow: 1px 1px 0 #000, -1px 1px 0 #000, 1px -1px 0 #000, -1px -1px 0 #000;
        }
        
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: bisque;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            font-size: 18px;
            padding: 10px 20px;
            color: white;
            background-color: #A87C41;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background-color: #8a6532;
            transform: scale(1.05);
        }

        button:active {
                background-color: #6e4f26;
                transform: scale(1);
        }
        nav a {
                font-size: 18px;
                padding: 10px 20px;
                color: white;
                background-color: #A87C41;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s ease, transform 0.2s ease;
                display: inline-block;
                margin-top: 20px;
        }

        nav a:hover {
                background-color: #8a6532;
                transform: scale(1.05);
        }

        nav a:active {
                background-color: #6e4f26;
                transform: scale(1);
        }
        select {
                font-size: 16px;
                padding: 8px 12px;
                border: 2px solid #A87C41;
                border-radius: 5px;
                background-color: white;
                color: #333;
                transition: border-color 0.3s ease, background-color 0.3s ease;
                cursor: pointer;
        }

        select:hover {
                border-color: #8a6532;
                background-color: #f2f2f2;
        }

        select:focus {
                outline: none;
                border-color: #6e4f26;
                background-color: #fff;
        }

        option {
                padding: 10px;
                background-color: #fff;
                color: #333;
        }

        option:hover {
                background-color: #f0e6d6;
        }
        
    </style>
</head>
<body>
    <h1>Welcome to the Task Management System</h1>
    <nav>
        <a href="../logs/logout.php">Logout</a>
    </nav>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>Ticket#</th>
                    <th>Client Name</th>
                    <th>Agent Name</th>
                    <th>Concern</th>
                    <th>Severity</th>
                    <th>Date Start</th>
                    <th>Date to Finish</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo $task->ticketNum; ?></td>
                        <td>
                            <input type="hidden" name="Clients_Aname[]" value="<?php echo $task->clientName; ?>">
                            <?php echo $task->clientName; ?>
                        </td>
                        <td>
                            <input type="hidden" name="Agent_Aname[]" value="<?php echo $task->agentName; ?>">
                            <?php echo $task->agentName; ?>
                        </td>
                        <td>
                            <input type="hidden" name="Aconcern[]" value="<?php echo $task->concern; ?>">
                            <?php echo $task->concern; ?>
                        </td>
                        <td>
                            <input type="hidden" name="Aseverity[]" value="<?php echo $task->severity; ?>">
                            <?php echo $task->severity; ?>
                        </td>
                        <td>
                            <input type="hidden" name="Adate_start[]" value="<?php echo $task->dateStart; ?>">
                            <?php echo $task->dateStart; ?>
                        </td>
                        <td>
                            <input type="hidden" name="Adate_F[]" value="<?php echo $task->dateFinish; ?>">
                            <?php echo $task->dateFinish; ?>
                        </td>
                        <td>
                            <select name="status[]">
                                <option value="Pending" <?php echo ($task->status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Ongoing" <?php echo ($task->status == 'Ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                                <option value="Done" <?php echo ($task->status == 'Done') ? 'selected' : ''; ?>>Done</option>
                            </select>
                            <input type="hidden" name="Ticket_tnum[]" value="<?php echo $task->ticketNum; ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" name="update">Update Status</button>
    </form>
</body>
</html>
