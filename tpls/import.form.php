<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();?>
<form action="" method="POST" enctype="multipart/form-data" />
    <h3><?=GetMessage('MMTX_I_CAPTION')?></h3>
    <div>
        <?=GetMessage('MMTX_I_FILE_CAPTION')?>
        <input type="file" name="iev" />
        <input type="submit" name="import" value="<?=GetMessage('MMTX_I_BTN_CAPTION')?>" />
    </div>
</form>