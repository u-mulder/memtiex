<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();
if (empty($errors)) {?>
<p><?=GetMessage('MMTX_I_RES_SUCC')?></p>
<?php
    } else {?>
<p><?=GetMessage('MMTX_I_RES_FAIL')?></p>
<ul><li><?=implode('</li><li>', $errors)?></li></ul>
<?php
}?>