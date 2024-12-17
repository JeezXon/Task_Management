<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class HomepageSystem {

    private $contentFile;

    public function __construct() {
        if (!isset($_SESSION['ID']) || !isset($_SESSION['user_name'])) {
            $this->redirectToLogin();
        }
        $this->determineContentFile();
    }

    private function redirectToLogin() {
        header("Location: ../logs/loginSystem.php");
        exit();
    }

    private function determineContentFile() {
        $content = isset($_GET['page']) ? $_GET['page'] : '';

        switch ($content) {
            case 'clients':
                $this->contentFile = 'client.php';
                break;
            case 'agents':
                $this->contentFile = 'agents.php';
                break;
            case 'assign_task':
                $this->contentFile = 'assign_task.php';
                break;
            case 'reports':
                $this->contentFile = 'reports.php';
                break;
            default:
                $this->contentFile = 'create.php';
                break;
        }
    }

    public function renderPage() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Homepage</title>
            <link rel="stylesheet" href='../css/bg.css'>
            <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
            <style>
                body {
                    font-size: larger;
                    display: block;
                    margin: 0px;
                    background-repeat: no-repeat;
                    background-size: cover;
                }
                .container_home {
                    display: flex;
                    font-size: 35px;
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: flex-start;
                    font-family: 'Press Start 2P', cursive;
                }
                .text-itemz {
                    padding: 10px;
                    border: 1px solid blue;
                    flex: 1;
                    text-align: center;
                    text-decoration: none;
                    background-color: white;
                }
                .text-itemz:hover {
                    background-color:rgb(165, 248, 241);
                }
            </style>
        </head>
        <body>
            <div class="container_home">
                <a href="?page=clients" class="text-itemz">Clients</a>
                <a href="?page=agents" class="text-itemz">Agents</a>
                <a href="?page=assign_task" class="text-itemz">Tasks</a>
                <a href="?page=reports" class="text-itemz">Reports</a>
                <a href="../logs/logout.php" class="text-itemz">Logout</a>
            </div>

            <div>
                <?php include($this->contentFile); ?>
            </div>
        </body>
        </html>
        <?php
    }
}

$homepageSystem = new HomepageSystem();
$homepageSystem->renderPage();
?>
