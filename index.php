<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
/*global $USER;
if (!$USER->IsAdmin())
    LocalRedirect('/');*/

define('MAIL_IMPEXP_ON', true);
spl_autoload_register('ie_classes_loader');
function ie_classes_loader($class) {
    $class_prefix = 'CMail';
    $filename =  strtolower(substr($class, strlen($class_prefix)));
    include 'classes/' . $filename . '.php';
}

require_once('tpls/header.php');

if (CModule::IncludeModule('subscribe')) {
    require_once('classes/helper.php');

    if (isset($_POST['export'])) {
        $obj_export = new CMailExport();
        $obj_export->execute($_POST['exp_et']);
        $errors = $obj_export->getErrors();
    } elseif (isset($_POST['import'])) {
        //$obj_import = new CMailImport;

    } else {
        $ev_types = CMailHelper::getEventTypes();
        if (!defined('LANGUAGE_ID'))
            define('LANGUAGE_ID', 'ru');
        //include 'tpls/import.form.php';
        if (0 < sizeof($ev_types)) {?>
<form action="" method="POST" />
    <h3>Экспорт почтовых событий и шаблонов в файл</h3>
    <? /*label>
        Заменять LID сайта в новых шаблонах на
        <input type="text" name="new_lid" value="" maxlength="5" title="Оставьте пустым если не хотите менять LID" />
    </label*/ ?>

    <table class="">
        <tr>
            <th><input id="all" type="checkbox" name="all" value="" title="Будут экспортированы все шаблоны всех событий"></th>
            <th>Почтовое событие</th>
            <th>Параметры</th>
        </tr>
<?php       $i = 1;
            foreach ($ev_types as $et_name => $et_data) {?>
        <tr>
            <td><input id="etfull<?=++$i?>" type="checkbox" name="exp_et[]" value="<?=$et_name?>" title="Будут экспортированы все шаблоны данного события"></td>
            <td><label for="etfull<?=$i?>" title="Будут экспортированы все шаблоны данного события"><b><?=$et_name?></b>, <?=$et_data[LANGUAGE_ID]['NAME']?></label></td>
            <td>---</td>
        </tr>
<?php       }?>
    </table>
    <div>
        <input type="submit" name="export" value="Экспортировать" title="Будут экспортированы отмеченные элементы" />
    </div>
</form>
<?php   } else {
            echo 'Не найдено почтовых событий.';
        }
    }
} else {
    echo 'Модуль "Подписка и рассылки" (subscribe) не найден.';
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
