<?php
// lang
if (isset($_GET['l'])) {
    $_SESSION['lang'] = $_GET['l']; //todo check valid
}

function _lang_suffix() {
    return '_'._lang();
}

function _lang() {
    $l = $_SESSION['lang'];
    if (strlen($l) == 0) {
        $l = 'rus';
    }
    return $l;
}

global $_;

$_['action.create'] = 'Создать';
$_['action.delete'] = 'Удалить';
$_['action.edit'] = 'Редактировать';
