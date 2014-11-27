<?

/*
 * Удаление
 */
function _er_delete() {
    $eid = _get_int('e', 0);
    $id = _get_int('i', 0);
    if ($eid == EATTRIBUTE) {
        $old = _db_item(EATTRIBUTE, $id);
        $res = _db_exec(_sql_ddl_drop_column($old[RF], $old[NM]));
    } else if ($eid == EENTITY) {
        $res = _db_exec(_sql_ddl_drop_table($id));
    } else {
        $res = _db_exec(_sql_delete_id($eid, $id));
    }
    return ___ui_alert_danger('Сущность '.(($res) ? '' : 'не ').'удалена').___ui_btn_table('?m=a&q='. $_GET['r'] .'&e='.$eid);
}

/*
 * Редактирование
 */
function _er_edit() {
    $eid = _get_int('e', 0);
    $id = _get_int('i', 0);
    $attributes = _dbs_attributes($eid);
    $item = _db_item($eid, $id);
    $str = _er_form2($attributes, $item, $eid, $_GET['r'], $id);
    return $str;
}

function ___split($f) {
    $cn = strlen($f);
    $d = 0;
    $r = '';
    $t = array();
    for ($i = 0; $i < $cn; $i++) {
        $c = $f[$i];
        if (strcmp($c, '(') == 0) {
            $d++; $r .= $c;
        } else if (strcmp($c, ')') == 0) {
            $d--; $r .= $c;
        } else if (strcmp($c, ',') == 0) {
            if ($d == 0) {
                array_push($t, $r); $r = '';
            } else {
                $r .= $c;
            }
        } else {
            $r .= $c;
        }
    }
    array_push($t, $r);
    return $t;
}


function ___params($f) {
    $a = ___split($f);
    $t = array();
    for ($i = 0; $i < count($a); $i++) {
        $j = $a[$i];
        $cn = strlen($f);
        $d = 0;
        $n = '';
        $g = '';
        for ($k = 0; $k < $cn; $k++) {
            $c = $j[$k];
            if (strcmp($c, '(') == 0) {
                $d++;
            } else if (strcmp($c, ')') == 0) {
                $d--;
            } else {
                if ($d == 0) {
                    $n .= $c;
                } else {
                    $g .= $c;
                }
            }
        }
        $m['NAME'] = $n;
        $m['ARGS'] = explode(',', $g);
        array_push($t, $m);
    }
    return $t;
}

function ___get($p, $n) {
    for ($i = 0; $i < count($p); $i++) {
        $j = $p[$i];
        if (strcmp($n, $j['NAME']) == 0) {
            return $j;
        }
    }
    return NULL;
}

function _er_form2($st, $data, $eid, $ret, $id) {
    $res = '';
    for ($i = 0; $i < count($st); $i++) {
        $name = $st[$i][NM];
        $type = $st[$i][TP];
        $title = $st[$i][TT];
        $format = $st[$i][FR];
        $params = ___params($format);
        $p_length = ___get($params, 'length');
        $p_hint = ___get($params, 'hint');
        $p_file = ___get($params, 'file');
        $p_required = ___get($params, 'required');


        if ($type == TSTRING || $type == TINT) {
            // строка короткая
            $v_length = ($p_length == NULL) ? 16 : $p_length['ARGS'][0];

            $in = _tag_open('input')._attr_type(($type == TINT) ? 'number' : 'text')._attr('id', $name)._attr_name($name)._attr('class', 'form-control');
            $in .= _attr_maxlength($v_length)._attr_value(htmlspecialchars($data[$name], ENT_QUOTES))._attr_placeholder($title)._attr_required($p_required);
            if ($p_hint != NULL) {
                $in .= _attr('list', '_l'.$name);
            }
            $in .= _tag_close();

            if ($p_file != NULL) {
                $in = _div_class('input-group', $in.'<div class="input-group-addon" style="cursor: pointer" onclick="window.open(\'?m=f&id='.$name.'\',\'blank\',\'resizable=yes,scrollbars=yes,status=yes\')" >...</div>');
            }

            if ($p_hint != NULL) {
                $in .= _datalist_id('_l'.$name, $p_hint['ARGS']);
            }
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $in));
        } else if ($type == TPASSWORD) {
            // пароль
            $_res = _tag_open('input')._attr_name($name)._attr('class', 'form-control')._attr_value($data[$name])._attr_maxlength(128)._tag_close();
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $_res));
        } else if ($type == TREF) {
            // ссылка по идентификатору
            $p_entity = ___get($params, 'entity');
            $p_attr = ___get($params, 'attribute');
            $n = $p_attr['ARGS'][0];
            $list = _dbs_list($p_entity['ARGS'][0]);
            // создание опций
            $s = '';
            if ($p_required == NULL) {
                $s .= _option_value('0', '--');
            }
            $s .= _options_ref($list, 'ID', $n, $data[$name]);
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', _select_name_class($name, 'form-control', $s)));
        } else if ($type == TTEXT) {
            // текст
            $v_length = ($p_length == NULL) ? 16 : $p_length['ARGS'][0];
            $s = str_replace('textarea', 'txtarea', $data[$name]);
            $_res = _tag_open('textarea')._attr_name($name)._attr('class', 'form-control')._attr('rows', 7)._attr_maxlength($v_length)._attr_placeholder($title)._attr_required($p_required)._tag_stop().$s._tag_second('textarea');
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $_res));
        } else if ($type == THTML) {
            // текст
            $v_length = ($p_length == NULL) ? 16 : $p_length['ARGS'][0];
            $_res = _tag_open('textarea')._attr_name($name)._attr_class('form-control')._attr('rows', 7)._attr_maxlength($v_length)._attr_placeholder($title)._attr_required($p_required)._tag_stop().$data[$name]._tag_second('textarea')."<script>CKEDITOR.replace( '".$name."', { filebrowserBrowseUrl: '?m=s', filebrowserUploadUrl: '?m=s', lang: 'ru' });</script>";
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $_res));
        } else if ($type == TBOOLEAN) {
            // двоичное поле
            $_res = '<input type=checkbox name="'.$name.'" value="1" '.(($data[$name] == '1') ? 'checked=true' : '').' />';
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $_res));
        } else if ($type == TDATE) {
            // дата
            $_res = _tag_open('input')._attr_name($name)._attr_type('date')._attr_class('form-control')._attr_value($data[$name])._attr_maxlength(10)._tag_close();
            $res .= _div_class('form-group', _label_class('col-sm-3 control-label', $title)._div_class('col-sm-9', $_res));
        } else {
            // неизвестный формат
            $res .= $res.$name.' '.$type.' '.$title.' '.$format.'<br/>';
        }
    }
    $res .= '<button type=submit id=sb class="btn btn-primary">'._i_class('glyphicon glyphicon-ok', '').' Сохранить</input>';
    $res = _div_class('panel-body', $res);
    $res = _div_class('panel-heading', _div_class('panel-title', 'Редактировать')).$res;
    $res = _div_class('panel panel-primary', $res);


    $res .= _input_hidden_name_value('__e', $eid);
    $res .= _input_hidden_name_value('__r', $ret);
    if ($id) $res .= _input_hidden_name_value('__i', $id);
    if (isset($_GET['f'])) {
        $res .= _input_hidden_name_value('__f', $_GET['f']);
    }

    return '<form class="form-horizontal" role="form" method="post" action="?m=a&q=b">'.$res.'</form>';
}



function __process_http_value($type, $name) {
    if ($type == TPASSWORD) {
        $v = _au_hash($_POST[$name]);
    } else if ($type == TBOOLEAN) {
        $v = '0';
        if ($_POST[$name]) $v = $_POST[$name];
    } else {
        $v = isset($_POST[$name]) ? $_POST[$name] : '';
    }
    return $v;
}

function _er_apply2() {
    $eid = $_POST['__e'];
    $ret = $_POST['__r'];
    $id = $_POST['__i'];
    $fl = $_POST['__f'];

    $table = _dbs_num2table($eid);
    $attributes = _dbs_attributes($eid);
//    $old = _db_item($eid, $id);
    $lid = '';

    if ($id) {
        // обновление
        $p = count($attributes);
        $q = 'UPDATE '.$table.' SET ';
        for ($i = 0; $i < $p; $i++) {
            $attribute = $attributes[$i];
            $name = $attribute[NM];
            $type = $attribute[TP];
            if ($i != 0) $q .= ', ';
            $q .= $name.' = ?';
            if ($type == TTEXT) {
                $v[$i] = str_replace('txtarea', 'textarea', $_POST[$name]);
            } else if ($type == TPASSWORD) {
                $v[$i] = _au_hash($_POST[$name]);
            } else {
                $v[$i] = $_POST[$name];
            }
        }
        $q .= ' WHERE ID = '.$id;

        // exec
        _db_exec($q, $v);
        $str = ___ui_alert_info('Сущность обновлена').
            ___ui_btn_table('?m=a&q='.$ret.'&e='.$eid.((isset($fl)) ? '&f='.$fl : '')).
            ___ui_btn('?m=a&r=m&q=a&e='.$eid.'&i='.$id.((isset($fl)) ? '&f='.$fl : ''), 'glyphicon glyphicon-edit', 'Продолжить редактирование');
    } else {
        // создание
        $p = count($attributes);
        $q = 'INSERT INTO '.$table.' (';
        for ($i = 0; $i < $p; $i++) {
            $attribute = $attributes[$i];
            $name = $attribute[NM];
            $type = $attribute[TP];
            if ($i != 0) $q .= ', ';
            $q .= $name;
            $v[$i] = __process_http_value($type, $name);
        }
        $q .= ') VALUES (';
        for ($i = 0; $i < $p; $i++) $q .= (($i == 0) ? '?' : ', ?');
        $q .= ')';

        // exec
        $lid = _db_exec($q, $v);
        $str = ___ui_alert_info('Сущность создана').___ui_btn_table('?m=a&q='.$ret.'&e='.$eid.((isset($fl)) ? '&f='.$fl : ''));
    }

    // проверка на создание сущности
    if (!$id && $eid == EENTITY) {
        _db_exec(_sql_ddl_create_table(intval($lid)));
    }

    // проверка на создание атрибута
    if (!$id && $eid == EATTRIBUTE) {
        _db_exec(_sql_ddl_add_column($_POST[RF], $_POST[NM], $_POST[TP], $_POST[FR]));
    }

    return $str;
}
