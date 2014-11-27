<?php
// Идентификаторы таблиц
$__E2T = array(EENTITY => TENTITY, EATTRIBUTE => TATTRIBUTE, EUSER => TUSER,
    ESITE => TSITE, EPAGE => TPAGE, ETEMPLATE => TTEMPLATE, ECHUNK => TCHUNK,
    EBLOCK => TBLOCK, ELANG => TLANG, EMETA => TMETA, ELIBRARY => TLIBRARY, ETEMPLATE2 => TTEMPLATE2);

function _dbs_internal() {
    global $__E2T;
    return $__E2T;
}

function _dbs_attributes($eid) {
    if ($eid == EENTITY) return _dbs_attributes_entity();
    if ($eid == EATTRIBUTE) return _dbs_attributes_attribute();
    if ($eid == EUSER) return _dbs_attributes_user();
    if ($eid == ESITE) return _dbs_attributes_site();
    if ($eid == EPAGE) return _dbs_attributes_page();
    if ($eid == ETEMPLATE) return _dbs_attributes_template();
    if ($eid == ECHUNK) return _dbs_attributes_chunk();
    if ($eid == EBLOCK) return _dbs_attributes_block();
    if ($eid == ELANG) return _dbs_attributes_lang();
    if ($eid == EMETA) return _dbs_attributes_meta();
    if ($eid == ELIBRARY) return _dbs_attributes_library();
    if ($eid == ETEMPLATE2) return _dbs_attributes_template2();
    return _db_rows(_sql_select_from_where(TATTRIBUTE, 'REF = ? ORDER BY ORD'), array($eid)); // todo list_ordered
}

function _dbs_entity($eid) {
    if ($eid == EENTITY) return array(TT => 'Сущность', 'ORD' => NM);
    if ($eid == EATTRIBUTE) return array(TT => 'Атрибуты', 'ORD' => 'REF, NAME');
    if ($eid == EUSER) return array(TT => 'Пользователи');
    if ($eid == ESITE) return array(TT => 'Сайты', 'ORD' => 'DOMAIN');
    if ($eid == EPAGE) return array(TT => 'Страницы', 'ORD' => NM);
    if ($eid == ETEMPLATE) return array(TT => 'Шаблоны', 'ORD' => 'ID');
    if ($eid == ECHUNK) return array(TT => 'Чанки', 'ORD' => NM);
    if ($eid == EBLOCK) return array(TT => 'Блоки');
    if ($eid == ELANG) return array(TT => 'Языки', 'ORD' => NM);
    if ($eid == EMETA) return array(TT => 'Мета теги', 'ORD' => 'SITE, NAME');
    if ($eid == ELIBRARY) return array(TT => 'Библиотеки', 'ORD' => NM);
    if ($eid == ETEMPLATE2) return array(TT => 'Шаблоны PHP', 'ORD' => NM);
    return _db_row(_sql_select_from_where(TENTITY, 'ID = '.$eid));
}

function _dbs_attribute($aid) {
    return _db_row(_sql_select_from_where(TATTRIBUTE, 'ID = ?'), array($aid));
}

function _dbs_entities() {
    return _db_rows(_sql_select_from(TENTITY));
}



function _dbs_attributes_entity() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Наименование сущности', FR => 'length(128),required()');
    $arr[1] = array(NM => TT, TP => TSTRING, TT => 'Описание сущности', FR => 'length(128),required()');
    $arr[2] = array(NM => 'ORD', TP => TSTRING, TT => 'Порядок отображения', FR => 'length(128)');
    return $arr;
}

function _dbs_attributes_attribute() {
    $arr[0] = array(NM => RF, TP => TREF, TT => 'ID Сущности', FR => 'entity(-1),attribute(TITLE)');
    $arr[1] = array(NM => NM, TP => TSTRING, TT => 'Наименование атрибута', FR => 'length(128),required()');
    $arr[2] = array(NM => TT, TP => TSTRING, TT => 'Описание атрибута', FR => 'length(128),required()');
    $arr[3] = array(NM => TP, TP => TSTRING, TT => 'Тип атрибута', FR => 'length(128),hint(string,text,html,int,ref,boolean,password,date,datetime),required()');
    $arr[4] = array(NM => FR, TP => TSTRING, TT => 'Формат атрибута', FR => 'length(128)');
    $arr[5] = array(NM => 'ORD', TP => TINT, TT => 'Порядок атрибута', FR => '');
    return $arr;
}

function _dbs_attributes_user() {
    $arr[0] = array(NM => 'LOGIN', TP => TSTRING, TT => 'Идентификатор пользователя', FR => 'length(128),required()');
    $arr[1] = array(NM => 'PASSWORD', TP => TPASSWORD, TT => 'Пароль пользователя', FR => (AU_METHOD == 1) ? 'length(32)' : 'length(40)');
    $arr[2] = array(NM => TT, TP => TSTRING, TT => 'Описание пользователя', FR => 'length(128)');
    $arr[3] = array(NM => 'ROLE', TP => TSTRING, TT => 'Роль пользователя', FR => 'length(128)');
    return $arr;
}

function _dbs_attributes_site() {
    $arr[0] = array(NM => 'DOMAIN', TP => TSTRING, TT => 'Домен сайта', FR => 'length(128),required()');
    $arr[1] = array(NM => 'PAGE', TP => TSTRING, TT => 'Страница по умолчанию', FR => 'length(128),required()');
    $arr[2] = array(NM => TT, TP => TSTRING, TT => 'Заголовок сайта', FR => 'length(128)');
    return $arr;
}

function _dbs_attributes_page() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Наименование страницы', FR => 'length(128),required()');
    $arr[1] = array(NM => TT, TP => TSTRING, TT => 'Заголовок страницы', FR => 'length(128)');
    $arr[2] = array(NM => 'CONTENT', TP => TTEXT, TT => 'Содержимое  страницы', FR => 'length(32768)');
    return $arr;
}

function _dbs_attributes_template() {
    $arr[0] = array(NM => TT, TP => TSTRING, TT => 'Заголовок шаблона', FR => 'length(128)');
    $arr[1] = array(NM => 'ITEM', TP => TTEXT, TT => 'Значение шаблона', FR => 'length(32768)');
    $arr[2] = array(NM => PR, TP => TTEXT, TT => 'Префикс шаблона', FR => 'length(32768)');
    $arr[3] = array(NM => SF, TP => TTEXT, TT => 'Суффикс шаблона', FR => 'length(32768)');
    $arr[4] = array(NM => 'DELIMITER', TP => TTEXT, TT => 'Разделитель шаблона', FR => 'length(32768)');
    return $arr;
}

function _dbs_attributes_chunk() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Наименование чанка', FR => 'length(128),required()');
    $arr[1] = array(NM => TT, TP => TSTRING, TT => 'Заголовок чанка', FR => 'length(128)');
    $arr[2] = array(NM => 'CONTENT', TP => TTEXT, TT => 'Содержимое чанка', FR => 'length(32768)');
    return $arr;
}

function _dbs_attributes_block() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Наименование блока', FR => 'length(128),required()');
    $arr[1] = array(NM => TT, TP => TSTRING, TT => 'Заголовок блока', FR => 'length(128)');
    $arr[2] = array(NM => 'CONTENT', TP => THTML, TT => 'Содержимое блока', FR => 'length(32768)');
    return $arr;
}

function _dbs_attributes_lang() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Суффикс языка', FR => 'length(3),hint(en,ru),required()');
    $arr[1] = array(NM => TT, TP => TSTRING, TT => 'Название языка', FR => 'length(128),hint(Английский,Русский),required()');
    return $arr;
}

function _dbs_attributes_meta() {
    $arr[0] = array(NM => 'SITE', TP => TREF, TT => 'Сайт', FR => 'entity(-4),attribute(DOMAIN),required()');
    $arr[1] = array(NM => NM, TP => TSTRING, TT => 'Название тега', FR => 'length(128),hint(author,copyright,description,keywords,google-site-verification),required()');
    $arr[2] = array(NM => VL, TP => TSTRING, TT => 'Значение тега', FR => 'length(4096)');
    return $arr;
}

function _dbs_attributes_library() {
    $arr[0] = array(NM => NM, TP => TSTRING, TT => 'Наименование', FR => 'length(128),required()');
    $arr[1] = array(NM => 'VERSION', TP => TSTRING, TT => 'Версия', FR => 'length(128),required()');
    $arr[2] = array(NM => 'FILES', TP => TTEXT, TT => 'Список файлов', FR => 'length(65535),required()');
    return $arr;
}

function _dbs_attributes_template2() {
    $arr[0] = array(NM => TT, TP => TSTRING, TT => 'Заголовок шаблона', FR => 'length(128)');
    $arr[1] = array(NM => 'ITEM', TP => TTEXT, TT => 'Значение шаблона', FR => 'length(32768)');
    return $arr;
}

function _dbs_num2table($i) {
    global $__E2T;
    if (isset($__E2T[$i])) {
        return $__E2T[$i];
    }
    return 'message'.$i;
}

function _dbs_type2mysql($type, $format) {
    $params = ___params($format);
    $p_length = ___get($params, 'length');
    $v_length = ($p_length == NULL) ? 16 : $p_length['ARGS'][0];
    if ($type == TSTRING) {
        return 'varchar('.$v_length.')';
    } else if ($type == TTEXT || $type == THTML) {
        return 'text('.$v_length.')';
    } else if ($type == TREF) {
        return 'bigint(20)';
    } else if ($type == TINT) {
        return 'int(11)';
    } else if ($type == TPASSWORD) {
        return (AU_METHOD == 1) ? 'varchar(32)' : 'varchar(40)';
    } else if ($type == TBOOLEAN) {
        return 'boolean';
    } else if ($type == TDATE) {
        return 'date';
    } else if ($type == TDATETIME) {
        return 'datetime';
    }
    return '';
}



function _dbs_install() {
    foreach (_dbs_internal() as $key => $value) {
        _db_exec(_sql_ddl_create_table($key));
    }

    // Вставить первую учетку, если ее не было
    $c = _db_row("SELECT count(*) as cnt FROM `user`"); // todo count
    if ($c['cnt'] == 0) {
        $p = 'admin';
        $v = _au_hash($p);
        _db_exec("INSERT INTO `user` (`login`, `password`, `title`, `role`) VALUES ('${p}', '${v}', '${p}', '${p}')"); //todo insert func
    }
}

