<?

function _sql_select_from($e) { return 'SELECT * FROM '.DB_PREFIX.$e; }

function _sql_select_from_where($e, $w) { return 'SELECT * FROM '.DB_PREFIX.$e.' WHERE '.$w; } //todo replace short version with args


function _sql_select($eid) {
    return _sql_select_from(_dbs_num2table($eid));
}

function _sql_delete_id($eid, $id) {
    return 'DELETE FROM `'.DB_PREFIX._dbs_num2table($eid).'` WHERE id = '.intval($id);
}

/**
 * Создать таблицу по идентификатору
 *
 * @param $eid Идентификатор таблицы
 * @return string SQL выражение DDL
 */
function _sql_ddl_create_table($eid) {
    $a = _dbs_attributes($eid);
    $s = 'CREATE TABLE `'.DB_PREFIX._dbs_num2table($eid).'` (ID bigint(20) not null auto_increment, ';
    if ($a) foreach ($a as $i) $s .= '`'.$i[NM].'` '._dbs_type2mysql($i[TP], $i[FR]).' not null, ';
    $s .= 'primary key(ID)) DEFAULT CHARSET=utf8';
    return $s;
}

/**
 * Удалить таблицу по идентификатору
 *
 * @param $eid Идентификатор таблицы
 * @return string SQL выражение DDL
 */
function _sql_ddl_drop_table($eid) {
    return 'DROP TABLE `'.DB_PREFIX._dbs_num2table($eid).'`';
}

/**
 * Добавить в таблицу столбец
 *
 * @param $eid Идентификатор таблицы
 * @param $name Имя столбца
 * @param $type Тип столбца
 * @param $format Формат столбца
 * @return string SQL выражение DDL
 */
function _sql_ddl_add_column($eid, $name, $type, $format) {
    return 'ALTER TABLE `'.DB_PREFIX._dbs_num2table($eid).'` ADD COLUMN '.$name.' '. _dbs_type2mysql($type, $format) .' not null';
}

/**
 * Удалить из таблицы столбец
 *
 * @param $eid Идентификатор таблицы
 * @param $name Имя столбца
 * @return string SQL выражение DDL
 */
function _sql_ddl_drop_column($eid, $name) {
    return 'ALTER TABLE `'.DB_PREFIX._dbs_num2table($eid).'` DROP COLUMN '.$name;
}
