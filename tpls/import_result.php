<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();
if (empty($errors)) {?>
<p>Все события и шаблоны успешно импортированы.</p>
<?php
    } else {?>
<p>При импорте событий и шаблонов произошли следующие ошибки:</p>
<ul><li><?=implode('</li><li>', $errors)?></li></ul>
<?php
}?>
