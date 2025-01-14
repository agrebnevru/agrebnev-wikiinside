<?php

if (!check_bitrix_sessid()) {
    return;
}

/**
 * @global CMain $APPLICATION
 * @global string $errors
 */

\CAdminMessage::ShowMessage([
    'TYPE' => 'ERROR',
    'MESSAGE' => GetMessage("MOD_INST_ERR"),
    'DETAILS' => $errors,
    'HTML' => true,
]);
?>
<form action="<?= $APPLICATION->GetCurPage() ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
    <input type="submit" name="" value="<?= GetMessage("MOD_BACK") ?>">
</form>
