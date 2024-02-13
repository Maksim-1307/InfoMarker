<?php 
session_start();
?>
<header class="header">
    <div class="container">
        <div class="header__left">
            <a class="logo" href="/">[InfoMarker]*</a>
            <div class="header__text">Маркировка упоминания иностранных агентов и нежелательных организаций в Ваших текстах</div>
        </div>
        <?php require 'user-card.php'; ?>
    </div>
    <div class="container">
        <p class="warning">
            Это <b>демонстрационная</b> версия сайта, призванная показать в первую очередь его функционал, работа над интерфейсом будет произведена позже 
        </p>
    </div>
</header>