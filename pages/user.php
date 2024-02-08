<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="../style.css">
    <title>TextMarker | Личный кабинет</title>
</head>

<body>
    <div class="wrapper">
        <?php require '../blocks/header.php' ?>
        <?php

        require_once '../user/connect.php';
        ?>
        <div class="container">
            <div class="user-form">
                <h3>Ваши данные</h3>
                <form action="../user/update.php" method="post" enctype="multipart/form-data">
                    <div class="segment">
                        <label>Логин</label>
                        <input name="login" value="<?= $_SESSION["user"]["login"] ?>" class="field-1" type="text" placeholder="Логин">
                    </div>
                    <div class="segment">
                        <label>Ваше ФИО</label>
                        <input name="full_name" value="<?= $_SESSION["user"]["full_name"] ?>" class="field-1" type="text" placeholder="Иванов Иван Иванович">
                    </div>
                    <div class="segment">
                        <label>Ваша почта</label>
                        <input name="email" value="<?= $_SESSION["user"]["email"] ?>" class="field-1" type="text" placeholder="mymail@example.com">
                    </div>
                    <div class="segment">
                        <label>Изображение</label>
                        <input type="file" name="profile-image" class="field-1" accept="image/png, image/jpeg">
                    </div>
                    <div class="segment">
                        <button class="btn-1" type="submit">Сохранить</button>
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
    </div>
    <?php require '../blocks/footer.php' ?>
</body>

</html>