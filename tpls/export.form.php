<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();
if (0 < sizeof($ev_types)) {?>
<form action="" method="POST" />
    <h3>Экспорт почтовых событий и шаблонов в файл</h3>
    <? /*label>
        Заменять LID сайта в новых шаблонах на
        <input type="text" name="new_lid" value="" maxlength="5" title="Оставьте пустым если не хотите менять LID" />
    </label*/ ?>
    <table class="">
        <tr>
            <th><!-- input id="all" type="checkbox" name="all" value="" title="Будут экспортированы все шаблоны всех событий" --></th>
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
<?php
} else {?>
<p>Не найдено почтовых событий</p>
<?php
}?>
