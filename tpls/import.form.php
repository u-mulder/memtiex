<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();?>
<form action="" method="POST" enctype="multipart/form-data" />
    <h3>Импорт почтовых событий и шаблонов из файлов</h3>
    <div>
        Файл с данными
        <input type="file" name="iev" />
        <!-- a href="#" id="">Добавить файл</a -->
        <input type="submit" name="import" value="Импортировать" />
    </div>
</form>
