<?php

// DB
function _db_link() {
    return new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
//    return new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASSWORD);
//    return new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASSWORD);
}

function _db_exec($q, $v = null) {
    $pdo = _db_link();
    if (empty($v)) {
        $result = $pdo->exec($q);
    } else {
        $stmt = $pdo->prepare($q);
        $stmt->execute($v);
        $result = $pdo->lastInsertId();
        $stmt = null;
    }
    $pdo = null;
    return $result;
}

function _db_row($q, $v = null) {
    $pdo = _db_link();
    $sth = $pdo->prepare($q);
    if (empty($v)) {
        $sth->execute();
    } else {
        $sth->execute($v);
    }
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    $sth = null;
    $pdo = null;
    return $result;
}

function _db_rows($q, $v = null) {
    $pdo = _db_link();
    $sth = $pdo->prepare($q);
    if (empty($v)) {
        $sth->execute();
    } else {
        $sth->execute($v);
    }
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    $sth = null;
    $pdo = null;
    return $result;
}

