<?

function _db_rows_select($eid) {
    return _db_rows(_sql_select($eid));
}

function _dbs_list($e) { // todo ???? нужен ли
    return _db_rows(_sql_select_from(_dbs_num2table($e)));
}

function _db_item($e, $id) {
    return _db_row(_sql_select_from_where(_dbs_num2table($e), 'ID = ?'), array($id));
}

function _db_row_key_value($e, $key, $value) {
    return _db_row(_sql_select_from_where(_dbs_num2table($e), $key.' = ?'), array($value));
}
