<section class="fileloader-section">
    <div class="container">
        <div class="fileloader">
            <form action="../filehandler/upload.php" method="post" enctype="multipart/form-data">

                <input type="file" name="new_document" accept=".docx" />
                Отправить этот файл:
                <input type="submit" value="Отправить файл" />
                <div>перетаскивания пока нет</div>

            </form>
            <form action="testing/handle.php" method="post" enctype='multipart/form-data'>
                <input type="file" name="file">
                <input type="submit" value="Отправить файл" />
            </form> 
        </div>
        <div class="fileloader-section__info">
            <img class="info-icon" src="">
            <p>Результаты проверки <b>[InfoMarker]*</b> носят строго рекомендательный характер и не могут использоваться в качестче юридического документа</p>
        </div>
    </div>
</section>