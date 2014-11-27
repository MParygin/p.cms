<?php

if (get_magic_quotes_gpc()) {
    function undoMagicQuotes($array, $topLevel=true) {
        $newArray = array();
        foreach($array as $key => $value) {
            if (!$topLevel) {
                $key = stripslashes($key);
            }
            if (is_array($value)) {
                $newArray[$key] = undoMagicQuotes($value, false);
            }
            else {
                $newArray[$key] = stripslashes($value);
            }
        }
        return $newArray;
    }
    $_GET = undoMagicQuotes($_GET);
    $_POST = undoMagicQuotes($_POST);
    $_COOKIE = undoMagicQuotes($_COOKIE);
    $_REQUEST = undoMagicQuotes($_REQUEST);
}

function _get_int($name, $default) { return (isset($_GET[$name])) ? intval($_GET[$name]) : $default; }

function _get_session($name, $domain) {
    $pname = $domain.'-'.$name;
    if (isset($_GET[$name])) {
        $_SESSION[$pname] = $_GET[$name];
        return $_GET[$name];
    } else {
        if (isset($_SESSION[$pname])) {
            return $_SESSION[$pname];
        } else {
            return NULL;
        }
    }
}


/**
 * Вывод чанка
 *
 * @param $cid Идентификатор чанка
 * @return mixed Вычисленный чанк
 */
function _chunk($cid) {
    $id = intval($cid);
    if ($id > 0) {
        $s = _db_item(ECHUNK, $cid);
    } else {
        $s = _db_row_key_value(ECHUNK, NM, $cid);
    }
    $str = $s['CONTENT'];
    return eval('return "'.$str.'";');
}

/**
 * Вывод блока
 *
 * @param $bid Идентификатор блока
 * @return mixed Вычисленный блок
 */
function _block($bid) {
    $id = intval($bid);
    if ($id > 0) {
        $s = _db_item(EBLOCK, $bid);
    } else {
        $s = _db_row_key_value(EBLOCK, NM, $bid);
    }
    return $s['CONTENT'];
}
