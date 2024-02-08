<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href="../style.css">
    <title>InfoMarker | Админ-панель</title>
</head>

<body>
    <div class="wrapper">
        <?php require '../blocks/header.php' ?>
        <?php

        if (!$_SESSION["user"]["is_admin"]) {
            header('Location: ../index.php');
            exit();
        }

        require_once '../user/connect.php';
        ?>
        <div class="container">
            <div class="message">Добавление запрещенных организаций вручную - это временное решение. В дальнейшем они будут подгружаться автоматически, и отчеты об этом будут отображаться в админ-панели</div>
            <h3>Список иностранных агентов и нежелательных организаций </h3>
            <div class="db-table admin__db-table">
                <?php

                $request = "SELECT * FROM register";
                $result = $connect->query($request);


                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='db-table__row'>";
                        foreach ($row as $el) {
                            echo '<div class="db-table__el">';
                            echo $el;
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "Таблица пуста";
                }
                ?>
            </div>
            <div class="add-name-form">
                <h3>Добавить</h3>
                <form action="../admin/database/add.php" method="post">
                    <label for="name">название</label>
                    <input name="name" type="text"><br><br>
                    <label for="about">описание</label>
                    <textarea name="about" cols="30" rows="5"></textarea><br><br>
                    <button type="submit">Добавить</button>

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