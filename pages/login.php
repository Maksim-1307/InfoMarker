<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="../style.css">
    <title>InfoMarker | Вход</title>
</head>

<body>
    <div class="wrapper">
        <?php require '../blocks/header.php' ?>
        <div class="container">
            <form action="../user/signin.php" method="post">
                <div class="segment">
                    <label>Login</label>
                    <input class="field-1" type="text" name="login" placeholder="enter your login" required>
                </div>
                <div class="segment">
                    <label>Password</label>
                    <input class="field-1" type="password" name="password" placeholder="enter your password" required>
                </div>
                <div class="segment">
                    <button class="btn-1" type="submit">Log In</button>
                </div>
                <div class="segment">
                    <div class="msg">Don't have an account yet? <a class="link-1" href="register.php">Registration</a></div>
                </div>
                <?php
                if (isset($_SESSION['error_message'])) {
                ?>
                    <div class="segment">
                        <div class="error">
                            <?= $_SESSION['error_message']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['error_message']);
                }
                if (isset($_SESSION['success_message'])) {
                ?>
                    <div class="segment">
                        <div class="success">
                            <?= $_SESSION['success_message']; ?>
                        </div>
                    </div>
                <?php
                    unset($_SESSION['success_message']);
                }
                ?>
            </form>
        </div>
    </div>
    <?php require '../blocks/footer.php' ?>
</body>

</html>