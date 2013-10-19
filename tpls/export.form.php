<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();
if (0 < sizeof($ev_types)) {?>
<form action="" method="POST" />
    <h3><?=GetMessage('MMTX_E_CAPTION')?></h3>
    <? /*label>
        Заменять LID сайта в новых шаблонах на
        <input type="text" name="new_lid" value="" maxlength="5" title="Оставьте пустым если не хотите менять LID" />
    </label*/ ?>
    <table class="">
        <tr>
            <th><!-- input id="all" type="checkbox" name="all" value="" title="" --></th>
            <th><?=GetMessage('MMTX_E_TH_EVENT')?></th>
            <th><?=GetMessage('MMTX_E_TH_PARAMS')?></th>
        </tr>
<?php       $i = 1;
            foreach ($ev_types as $et_name => $et_data) {?>
        <tr>
            <td><input id="etfull<?=++$i?>" type="checkbox" name="exp_et[]" value="<?=$et_name?>" title="<?=GetMessage('MMTX_E_TITLE')?>"></td>
            <td><label for="etfull<?=$i?>" title="<?=GetMessage('MMTX_E_TITLE')?>"><b><?=$et_name?></b>, <?=$et_data[LANGUAGE_ID]['NAME']?></label></td>
            <td>---</td>
        </tr>
<?php       }?>
    </table>
    <div>
        <input type="submit" name="export" value="<?=GetMessage('MMTX_E_BTN_CAPTION')?>" title="<?=GetMessage('MMTX_E_BTN_TITLE')?>" />
    </div>
</form>
<?php
} else {?>
<p><?=GetMessage('MMTX_E_ERROR')?></p>
<?php
}?>