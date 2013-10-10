<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

//var_dump(SITE_CHARSET); defined!

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
        $filename = $obj_export->getExportFilename();
        require_once('tpls/export_result.php');
    } elseif (isset($_POST['import'])) {
        $obj_import = new CMailImport;
        $obj_import->execute($_FILES['iev']);
        $errors = $obj_import->getErrors();
        require_once('tpls/import_result.php');
    } else {
        $ev_types = CMailHelper::getEventTypes();
        if (!defined('LANGUAGE_ID'))
            define('LANGUAGE_ID', 'ru');
        include 'tpls/import.form.php';
        include 'tpls/export.form.php';
    }
} else {
    echo 'Модуль "Подписка и рассылки" (subscribe) не найден.';
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
