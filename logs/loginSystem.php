<?php

class LoginSystem {

    private $action;
    private $error;

    public function __construct($action = "login.php", $error = null) {
        $this->action = $action;
        $this->error = $error;
    }

    public function renderForm() {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
            <link rel="stylesheet" type="text/css" href="../css/login.css">
        </head>
        <body>
            <form action="<?php echo $this->action; ?>" method="post">
                <h2>LOGIN</h2>
                <?php if ($this->error) { ?>
                    <p class="error"><?php echo htmlspecialchars($this->error); ?></p>
                <?php } ?>
                <label>Username</label>
                <input type="text" id="username" name="uname" placeholder="Username" required><br>

                <label>Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <button type="submit">Login</button>
            </form>
        </body>
        </html>
        <?php
    }
}

// Instantiate and render the login form
$error = isset($_GET['error']) ? $_GET['error'] : null;
$loginSystem = new LoginSystem("login.php", $error);
$loginSystem->renderForm();

?>
