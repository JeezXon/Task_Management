<?php
require_once __DIR__ . '/../db/db_connect.php';
require __DIR__ . '/../print/pdf.php';

// Initialize filter variable
$status_filter = isset($_POST['status']) ? $_POST['status'] : '';

// Prepare the SQL query with the selected filter
$query = "SELECT ATicket_num AS Ticket_num, Clients_Aname AS Client_Name, Agent_Aname, Aconcern AS Concern, Aseverity AS Severity, Adate_start AS Date_start, Adate_F AS Date_end, Astatus AS Status FROM agent_user";
if ($status_filter) {
    $query .= " WHERE Astatus = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}

$result = mysqli_query($conn, $query);
$reports = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row; 
    }
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}


mysqli_close($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORTS</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        /* Base styles for the h1 */
        h1 {
            display: flex;
            justify-content: center;
            gap: 5px; /* Adds spacing between letters */
            font-family: 'Press Start 2P', cursive;
            font-size: 85px;
            margin-top: 50px;
        }

        /* Style for each letter */
        h1 span {
            display: inline-block;
            animation: wave 1.5s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.1s);
        }

        /* Keyframes for the wave animation */
        @keyframes wave {
            0%, 100% {
                transform: translateY(0); /* Initial position */
            }
            50% {
                transform: translateY(-20px); /* Move up */
            }
        }

        /* Apply staggered delay using nth-child */
        h1 span:nth-child(1) { --i: 0; }
        h1 span:nth-child(2) { --i: 1; }
        h1 span:nth-child(3) { --i: 2; }
        h1 span:nth-child(4) { --i: 3; }
        h1 span:nth-child(5) { --i: 4; }
        h1 span:nth-child(6) { --i: 5; }
        h1 span:nth-child(7) { --i: 6; }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: blanchedalmond;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        
    </style>
</head>
<body>
<h1>
        <span>R</span>
        <span>E</span>
        <span>P</span>
        <span>O</span>
        <span>R</span>
        <span>T</span>
        <span>S</span>
    </h1>

    <form method="post" style="margin-bottom: 20px;">
        <label>Filters: </label>
        <select name="status">
            <option value="">All</option>
            <option value="Pending" <?php if ($status_filter === 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Ongoing" <?php if ($status_filter === 'Ongoing') echo 'selected'; ?>>Ongoing</option>
            <option value="Done" <?php if ($status_filter === 'Done') echo 'selected'; ?>>Done</option>
        </select>
        <button type="submit">Filter</button>
    </form>
    <a href="../print/printing.php" target="_blank"><button>Print PDF</button></a>

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
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['Ticket_num']); ?></td>
                        <td><?php echo htmlspecialchars($report['Client_Name']); ?></td>
                        <td><?php echo htmlspecialchars($report['Agent_Aname']); ?></td>
                        <td><?php echo htmlspecialchars($report['Concern']); ?></td>
                        <td><?php echo htmlspecialchars($report['Severity']); ?></td>
                        <td><?php echo htmlspecialchars($report['Date_start']); ?></td>
                        <td><?php echo htmlspecialchars($report['Date_end']); ?></td>
                        <td><?php echo htmlspecialchars($report['Status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
