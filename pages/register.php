<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="../style.css">
    <title>InfoMarker</title>
</head>

<body>
    <div class="wrapper">
        <?php require '../blocks/header.php' ?>
        <div class="container">
            <form action="../user/signup.php" method="post" enctype="multipart/form-data">
                <div class="segment">
                    <label>Login</label>
                    <input class="field-1" name="login" type="text" placeholder="enter your login" required>
                </div>
                <div class="segment">
                    <label>E-Mail</label>
                    <input class="field-1" name="e-mail" type="text" placeholder="enter your e-mail" required>
                </div>
                <div class="segment">
                    <label>ФИО</label>
                    <input class="field-1" name="full_name" type="text" placeholder="Введите Ваше ФИО" required>
                </div>
                <div class="segment">
                    <label>Profile Image</label>
                    <input type="file" name="profile-image" class="field-1" accept="image/png, image/jpeg">
                </div>
                <div class="segment">
                    <label>Password</label>
                    <input class="field-1" name="password" type="password" placeholder="enter your password" required>
                </div>
                <div class="segment">
                    <label>Password again</label>
                    <input class="field-1" name="password-again" type="password" placeholder="confirm your password" required>
                </div>
                <div class="segment">
                    <button class="btn-1" type="submit">Log In</button>
                </div>
                <div class="segment">
                    <div class="msg">Already have an account? <a class="link-1" href="index.php">Register</a></div>
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
                ?>
            </form>
        </div>
    </div>
    <?php require '../blocks/footer.php' ?>
</body>

</html>