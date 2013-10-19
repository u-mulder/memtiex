<?php
if (!defined('MAIL_IMPEXP_ON') || MAIL_IMPEXP_ON !== true)
    die();
if (empty($errors)) {?>
<p><?=GetMessage('MMTX_E_RES_SUCC')?><b><?=$filename?></b></p>
<?php
} else {?>
<p><?=GetMessage('MMTX_E_RES_FAIL')?></p>
<ul><li><?=implode('</li><li>', $errors)?></li></ul>
<?php
}?>
