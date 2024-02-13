<?php
if (session_status() === PHP_SESSION_NONE) die('No active session');
?>
<div class="user-card">
    <?php
    if (!isset($_SESSION['user'])) {
    ?>
        <div class="user-card__content">
            <a href="../pages/register.php">регистрация</a>
            <a href="../pages/login.php">вход</a>
        </div>
        <div class="user-card__image">
            <img class="user-card__avatar" src="../assets/images/avatar.jpg">
        </div>
    <?php
    } else {
    ?>
        <div class="user-card__content">
            <div class="user-card__full-name"><?= $_SESSION['user']['full_name'] ?></div>
            <a href="../pages/user.php">настройки</a>
            <a href="../user/signout.php">выйти</a>
        </div>
        <div class="user-card__image">
            <?php
            if ($_SESSION['user']['avatar']) {
            ?>
                <img class="user-card__avatar" src="<?= "../" . $_SESSION['user']['avatar'] ?>">
            <?php
            } else {
            ?>
                <img class="user-card__avatar" src="../assets/images/avatar.jpg">
            <?php
            }
            ?>

            <?php if ($_SESSION['user']['is_admin']) {
            ?>
                <div class="user-card__status">
                    <a href="/pages/admin.php">админ-панель</a>
                </div>
            <?php
            } ?>
        </div>
    <?php
    } ?>
    <!-- if ($_SESSION['user']['is_admin']) -->
    <!-- <div class="user-card__content">
        <div class="user-card__full-name">Иванов Иван Иванович</div>
        <a href="../pages/register.php">регистрация</a>
        <a href="../pages/login.php">вход</a>
    </div>
    <div class="user-card__image">
        <img src="">
        <div class="user-card__status">админ</div>
    </div> -->
</div>