<?php
session_start();
require_once __DIR__ . '/../db/db_connect.php';


class LoginHandler {

    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    private function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function login($username, $password) {
        $username = $this->validate($username);
        $password = $this->validate($password);

        // Check if fields are empty
        if (empty($username)) {
            $this->redirectWithError("Username is required");
        } else if (empty($password)) {
            $this->redirectWithError("Password is required");
        }

        // Authenticate user for different roles
        if ($this->authenticateUser($username, $password, "usern", "user_name", "password")) {
            // Admin login
            header("Location: ../Admin/Homepage.php");
            exit();
        } elseif ($this->authenticateUser($username, $password, "agents", "agent_uname", "agent_pass")) {
            // Agent login
            header("Location: ../agent/Home.php");
            exit();
        } else {
            $this->redirectWithError("Incorrect Username or Password");
        }
    }

    // Authenticate the user and verify password hash
    private function authenticateUser($username, $password, $table, $usernameField, $passwordField) {
        $sql = "SELECT * FROM $table WHERE $usernameField = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            echo "Error preparing statement: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            
            // Direct password comparison (without hashing)
            if ($password === $row[$passwordField]) {
                // Set session variables
                $_SESSION['user_name'] = $row[$usernameField];
                $_SESSION['name'] = $row['name'];  // Assuming 'name' field exists
                $_SESSION['ID'] = $row['ID'];      // Assuming 'ID' field exists
                
                echo "Welcome, " . $_SESSION['user_name'];
                return true;
            } else {
                // Password doesn't match
                echo "Incorrect password";
                return false;
            }
        } else {
            // Username not found
            echo "No user found with that username.";
            return false;
        }
    }
    
    

    // Redirect with an error message
    private function redirectWithError($error) {
        header("Location: loginSystem.php?error=" . urlencode($error));
        exit();
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uname']) && isset($_POST['password'])) {
    $loginHandler = new LoginHandler($conn);
    $loginHandler->login($_POST['uname'], $_POST['password']);
} else {
    // If accessed directly, redirect to login page
    header("Location: loginSystem.php");
    exit();
}
?>
