<?php

session_start();

require_once('cms/config.php');
require_once('cms/defs.php');

require_once('cms/db.php');
require_once('cms/sql.php');
require_once('cms/dbs.php');
require_once('cms/dbplus.php');

require_once('cms/auth.php');
require_once('cms/html.php');
require_once('cms/uni.php');
require_once('cms/er.php');
require_once('cms/lang.php');
require_once('cms/fns.php');

if (MM_GZIP == 1) { ob_start(); ob_implicit_flush(0); }


// PTCB

function _page_application($app) {
    $pid = $_GET['p'];
    if (!$pid) $pid = $app['PAGE'];
    return _db_row_key_value(EPAGE, NM, $pid);
}

function _page_several($pid) {
    return _db_row(_sql_select_from_where(EPAGE, 'NAME = ?'), array($pid));
}

function _template($tid, $list) {
    $t = _db_item(ETEMPLATE, $tid); // todo
    return _template_list($list, $t[PR], $t[SF], $t['DELIMITER'], $t['ITEM']);
}

function _template2($tid, $list) {
    $t = _db_item(ETEMPLATE, $tid); // todo
    return _template_list2($list, $t[PR], $t[SF], $t['DELIMITER'], $t['ITEM']);
}

function _meta() {
    $a = $_SESSION[AP];
    $s = '';
    $v = _dbs_list(EMETA);
    if ($v) {
        foreach ($v as $i) {
            if ($i['SITE'] == $a[ID]) {
                $s .= _meta_name_content($i[NM], $i[VL])._rn();
            }
        }
    }
    return $s;
}

function _head_jqueryui() {
    return
/*    _link_href_rel('cms/css/smoothness/jquery-ui-1.9.1.custom.css', 'stylesheet').
    _link_href_rel('cms/css/main.css', 'stylesheet').
    _script_src('cms/js/jquery-1.8.2.js').
    _script_src('cms/js/jquery-ui-1.9.1.custom.js').
    _script_src('cms/js/ckeditor.js').
    _script_src('cms/js/config.js').
    _link_href_rel('cms/css/elfinder.min.css', 'stylesheet').
    _link_href_rel('cms/css/theme.css', 'stylesheet').
    _script_src('cms/js/elfinder.min.js').
    _script_src('cms/js/i18n/elfinder.ru.js').
    _link_href_rel('cms/css/jquery.treeview.css', 'stylesheet').
    _script_src('cms/js/jquery.cookie.js').
    _script_src('cms/js/jquery.treeview.js'). */

    _link_href_rel('cms/css/bootstrap.min.css', 'stylesheet').
    _link_href_rel('cms/css/bootstrap-theme.min.css', 'stylesheet').
    _link_href_rel('cms/css/site.css', 'stylesheet').
    _script_src('http://code.jquery.com/jquery-latest.js').
    _script_src('cms/js/bootstrap.min.js').
    _script_src('cms/js/ckeditor.js').
    _script_src('cms/js/config.js')

        ;
}

// Функция вывода метатега для русского языка
function _meta_content_type() {
    return '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
}

// Вывод всего заголовка
function _header() {
    return _head(
        _meta_content_type().
        _title(MM_FULL).
        _head_jqueryui()
    );
}

function _admin2() {
    //return _div(MM_NAME.' :: Управление контентом / Пользователь: '.$_SESSION['user'][TT].', роль: '.$_SESSION['user']['ROLE'].'. '._a_href('?m=z', 'выход'));

    //$s = '<h1>SlimCMS 1.2</h1>'._div_class('row', _div_class('span3', _admin_nav())._div_class('span9', _admin_content()));
    $q = isset($_GET['q']) ? $_GET['q'] : '';



    $r = '<nav class="navbar navbar-default" role="navigation">';
    $r .= _div_class('container-fluid',
        _div_class('navbar-header', _div_class('navbar-brand', 'SlimCMS 1.2')).
        _div_class('collapse navbar-collapse',
            _ul_class('nav navbar-nav navbar-right',
                _li(___ui_a('?m=l', 'glyphicon glyphicon-cog', 'Файлы')).
                _li(___ui_a('?m=z', 'glyphicon glyphicon-off', 'Выход'))
            )
        )
    );
    $r .= '</nav>';


    return
    $r.
        _div_class('row',
            _div_class('col-md-3', _admin_nav()).
            _div_class('col-md-9',

                (($q == 'd') ? _er_delete() : '').
                (($q == 'a') ? _er_edit() : '').
                (($q == 'b') ? _er_apply2() : '').
                (($q == 'm') ? _admin_content() : '').
                (($q == 'l') ? _admin_library() : '').
                (($q == '') ? _div_class('jumbotron', _h1(MM_FULL)._p('Начните работу c...')._a_class_href('btn btn-primary btn-large', '?m=a&q=m&e=-1', 'Сущностей')) : '')

            )
        );
}


function _admin_nav() {
    $entities = _dbs_list(EENTITY);

    // manager ?
    $role = $_SESSION['user']['ROLE'];
    $isa = $role == 'admin';
    $isr = $role == 'user';
    $ism = $role == 'manager';
    $start = 0;
    $stop = count($entities);
    if ($ism || $isr) {
        if ($isr) {
            $start = 2;
            $stop = 3;
        } else {
            $start = 4;
        }
    } else {
        $e = '';
        $f = '';
    }

    // Постройка древовидного меню
    $tr = array();
    $ns = array();
    for ($i = $start; $i < $stop; $i++) {
        $en = $entities[$i];
        $st = $en[TT];
        array_push($ns, $st);
        $tr[$st] = $en['ID'];
    }
    asort($ns);

    // активный
    $entity = intval($_GET['e']);
    $e = '';
    // system
    if ($isa) {
        $e .= _li_class('disabled', 'Система');
        foreach (_dbs_internal() as $key => $value) {
            $en = _dbs_entity($key);
            $a = _a_href('?m=a&q=m&e='.$key, $en[TT]);
            $e .= ($key == $entity) ? _li_class('active', $a) : _li($a);
        }
    }
    $e .= _li_class('disabled', 'Пользователь');
    foreach ($ns as $n) {
        $id = $tr[$n];
        $a = _a_href('?m=a&q=m&e='.$id, $n);
        $e .= ($id == $entity) ? _li_class('active', $a) : _li($a);
    }
    return _ul_class('nav nav-pills nav-stacked', $e);
}

function _admin_content() {
    $e = intval($_GET['e']); //todo
    $en = _dbs_entity($e);
    $at = _dbs_attributes($e);
    // entity has refs
    $fr = '';
    foreach ($at as $key => $a) {
        if ($a[TP] == TREF) {
            $format = $a[FR];
            $params = ___params($format);
            $p_entity = ___get($params, 'entity');
            $p_attr = ___get($params, 'attribute');
            $n = $p_attr['ARGS'][0];
            $list = _dbs_list($p_entity['ARGS'][0]);
            // аргументы
            $f = '';
            if (isset($_GET['f'])) $f = $_GET['f'];
            $fv = NULL;
            if (strlen($f) > 0) {
                // SQL filter
                $_f = explode(':', $f);
                if ($_f[1] != '0') {
                    $fv = $_f[1];
                }
            }
            // создание опций
            $s = _option_value('0', 'Не применять');
            $s .= _options_ref($list, 'ID', $n, $fv);
            $fr .= $a[TT].':'._select_id_name_class('ef', 'ef', 'flr', $s).' <input style="width: 90px" type=button value=Применить onclick="window.location.href = \'?m=a&q=m&f='.$a[NM].':\'+$(\'#ef\').val()+\'&e='.$e.'\';"/>';
            // todo
        }
    }

    return

        _template_ilist2($e);
}

function _admin_library() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, "http://512.kz/repository.php");
    $result = curl_exec($ch);
    curl_close($ch);

    $s = _tr(_th('Наименование')._th('Версия'));
    $rep = json_decode($result, true);
    for ($i = 0; $i < count($rep); $i++) {
        $r = $rep[$i];
        $s .= _tr(_td($r['name'])._td($r['version'])._td($r['title'])._td('<a href="task.php?a=upload&n='.$r['name'].'&v='.$r['version'].'" class="btn btn-danger">Установить</a>'));
    }
    $s = _table_class('table table-hovered', $s);

    return _div_class('panel panel-primary', _div_class('panel-heading', _div_class('panel-title', 'Репозиторий')).$s._div_class('panel-footer', 'Всего '.count($rep).' библиотек'));
}


function _administrative() {
    $s = _admin2();
    return _html(_header()._body(_div_class('container-fluid', $s)));
}

// Template функции =========================================================

function _template_list($list, $lprefix, $lsuffix, $ldelimiter, $litem) {
//var_dump($lprefix);
    $str = '';
    eval ("\$str = \"$lprefix\";");
    $res = $str;
//var_dump($str);

    if ($list)
        for ($i = 0; $i < count($list); $i++) {
            if ($i != 0) $res = $res.$ldelimiter;
            $j = $i + 1;
            $item = $list[$i];
            eval ("\$str = \"$litem\";");
            $res .= $str;
        }
    eval ("\$str = \"$lsuffix\";");
    $res .= $str;
    return $res;
}

function _template_list2($list, $lprefix, $lsuffix, $ldelimiter, $litem) {
    $res = '';
    $str = '';
    eval ("\$str = \"$lprefix\";");
    if ($list)
        $res = $str;

    if ($list)
        for ($i = 0; $i < count($list); $i++) {
            if ($i != 0) $res = $res.$ldelimiter;
            $j = $i + 1;
            $item = $list[$i];
            eval ("\$str = \"$litem\";");
            $res .= $str;
        }
    eval ("\$str = \"$lsuffix\";");
    if ($list)
        $res .= $str;
    return $res;
}


function _template_ilist2($eid) {
    // константы
    $empty_string = _span_class('label label-warning', 'пустая строка');

    // переменные
    $e = _dbs_entity($eid);
    $t = _dbs_num2table($eid);
    $sql = _sql_select_from($t);
    $attributes = _dbs_attributes($eid);

    // заголовок
    $panel_heading = _div_class('panel-title', $e[TT]);

    // создание
    if ($eid != ELIBRARY) {
        $btn = ___ui_btn('?m=a&q=a&r=m&e='.$eid, 'glyphicon glyphicon-edit', 'Создать');
    } else {
        $btn = ___ui_btn('?m=a&q=l&e=-11', 'glyphicon glyphicon-cloud-download', 'Залить');
    }
    $panel_body = _div_class('pbc', $btn);

    // сортировка
    $order = _get_session('o', $eid); //todo security
    $ot = _get_session('ot', $eid);
    $base = '?m=a&q=m&e='.$eid;
    $_s = _li_class(($order == 'ID' && $ot == 'a') ? 'active' : NULL, ___ui_a($base.'&o=ID&ot=a', 'glyphicon glyphicon-sort-by-attributes', 'Номер'));
    $_s .= _li_class(($order == 'ID' && $ot == 'd') ? 'active' : NULL, ___ui_a($base.'&o=ID&ot=d', 'glyphicon glyphicon-sort-by-attributes-alt', 'Номер'));
    $_s .= _li_class('divider', '');
    foreach ($attributes as $attribute) {
        $_s .= _li_class(($order == $attribute[NM] && $ot == 'a') ? 'active' : NULL, ___ui_a($base.'&o='.$attribute[NM].'&ot=a', 'glyphicon glyphicon-sort-by-attributes', $attribute[TT]));
        $_s .= _li_class(($order == $attribute[NM] && $ot == 'd') ? 'active' : NULL, ___ui_a($base.'&o='.$attribute[NM].'&ot=d', 'glyphicon glyphicon-sort-by-attributes-alt', $attribute[TT]));
    }
    $_s .= _li_class('divider', '');
    $_s .= _li(___ui_a($base, 'glyphicon glyphicon-ban-circle', 'Базовая'));


    $sort = _div_class('btn-group', '<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-sort"></i> Сортировка <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.$_s.'</ul>');
    $panel_body .= _div_class('pbc', $sort);

    $f = '';
    if (isset($_GET['f'])) $f = $_GET['f'];

    if (strlen($f) > 0) {
        // SQL filter
        $_f = explode(':', $f);
        if ($_f[1] != '0') {
            $sql .= " WHERE ".$_f[0].'='.$_f[1];
        }
    }

    //$ord = $e['ORD'];
    if (isset($order) AND strlen($order) > 0) {
        $sql .= ' ORDER BY '.$order;
        if ($_GET['ot'] == 'd') $sql .= ' DESC';
    }
    // limits?


    $r = ''; // todo
    // сортировка
    if (isset($e)) {
        $ord = $e['ORD'];
        if (isset($ord) AND strlen($ord) > 0) {
            $r = _h4('Сортировка SQL: '.$ord);
        }
    }

    $tc = '';

    // заголовок & ссылки
    $c =  _th_style('width: 40px', '#'); // _th('#');//
    for ($i = 0; $i < count($attributes); $i++) {
        $c .= _th($attributes[$i][TT]);
        if ($attributes[$i][TP] == TREF) {
            $params = ___params($attributes[$i][FR]);
            $p_entity = ___get($params, 'entity');
            $p_attr = ___get($params, 'attribute');
//            $a = explode(',', $attributes[$i][FR]);
            $h[$i] = __list2hash2($p_entity['ARGS'][0], $p_attr['ARGS'][0]);
        }
    }
    $c .= _th('');//_th_style('width: 20px', '');
    $tc .= '<thead><tr>'.$c.'</tr></thead>';

    $r .= $c;

    $list = _db_rows($sql);


    // pages
    $pageSize = 50;
    $pageNumber = 0;
    if (isset($_GET['pn'])) {
        $pageNumber = intval($_GET['pn']);
    }
    $allSize = count($list);
    $pages = (($allSize - 1) / $pageSize) + 1;
    $posFrom = $pageNumber * $pageSize;
    $posTo = $posFrom + $pageSize;
    if ($posTo >= $allSize) {
        $posTo = $allSize;
    }

    // контент
    $c = '';
    if ($pages > 2) {
        $paginator = '';
        for ($p = 1; $p < $pages; $p++) {
            $paginator .= _li(_a_href('?m=a&q=m&e='.$eid.'&l='.$_GET['l'].'&pn='.($p - 1), $p));
        }
        $panel_body .= _div_class('pbc', _ul_class('pagination', $paginator));
    }
    $panel_body .= _div_class('pbc', _span_class('label label-success', 'Всего записей '.$allSize));

    if ($list)
//    for ($i = 0; $i < count($list); $i++) {
        for ($i = $posFrom; $i < $posTo; $i++) {
            $t = $list[$i];
            $id = $t['ID'];
            $hr = '?m=a&r='.$_GET['q'].'&q=a&i='.$id.'&e='.$eid;
            if (isset($_GET['f'])) {
                $hr .= '&f='.$_GET['f'];
            }
            $d = _td(_a_href($hr, $id));
            for ($j = 0; $j < count($attributes); $j++) {
                $n = $attributes[$j][NM];
                $p = $attributes[$j][TP];
                $f = $attributes[$j][FR];
                $params = ___params($f);
                $p_color = ___get($params, 'color');
                $p_file = ___get($params, 'file');

                $v = $t[$n];
                if ($n == NM) {
                    $v = $v;
                } else if ($p == TTEXT) {
                    $v = (strlen($v) == 0) ? $empty_string : '<a href="?m=v&e='.$eid.'&i='.$id.'&n='.$n.'" target="vt">'.___ui_label_info('текст').'</a> <span class="badge">'.strlen($v).' байт</span>';
                } else if ($p == THTML) {
                    $v = (strlen($v) == 0) ? $empty_string : '<a href="?m=v&e='.$eid.'&i='.$id.'&n='.$n.'" target="vt">'.___ui_label_info('HTML').'</a> <span class="badge">'.strlen($v).' байт</span>';
                } else if ($p == TPASSWORD) {
                    $v = _code($v);
                } else if ($p == TBOOLEAN) {
                    $v = ($v == '1') ? 'да' : 'нет';
                } else if ($p == TSTRING) {
                    if ($p_file != NULL) {
                        $v = (strlen($v) > 0) ? ___ui_label_info('ссылка').' <a href="../'.$v.'" target="blank"> '.$v.'</a>' : '--';
                    } else if ($p_color != NULL) {
                        $v = (strlen($v) > 0) ? ___ui_label_info('цвет').' '._code('#'.$v).' <span style="background-color: #'.$v.'"> ... </span>' : '--';
                    } else if (strlen($v) == 0) {
                        $v = $empty_string;
                    }
                } else if ($p == TREF) {
                    $v = (isset($h[$j][$v])) ? $h[$j][$v] : '---';
                }
                $d .= _td($v);
            }

            $href = ($eid != ELIBRARY) ? '?m=a&r='.$_GET['q'].'&q=d&i='.$id.'&e='.$eid : 'task.php?a=remove&n='.$t['NAME'].'&v='.$t['VERSION'];

            $d .= _td(___ui_a($href, 'glyphicon glyphicon-remove', ''));
            $tc .= "<tr ondblclick='location.href=\"".$hr."\";'>".$d.'</tr>';
        }
    $r .= $c;

    // Итог
    $panel_footer = 'Всего '.count($list).' записей. Показаны '.($posFrom+1).' - '.($posTo);

    // поисковые поля
//    if ($eid == 13 || ($eid >= 25 && $eid <= 28)) {
//      $ph = '';
//      if (isset($_GET['c'])) $ph = $_GET['c'];
//      $tt = "<form method=get action=index.php><input type=hidden name=q value='{$_GET['q']}'/><input type=hidden name=e value='{$_GET['e']}'/><input type=hidden name=l value='{$_GET['l']}'/> Телефон для поиска: "._input_name_value('c', $ph).'<input type=submit value="Применить"/></form>';
//      $r = _div_class('eua', $tt).$r;
//    }

    $r = _table_class('table table-hover table-bordered table-condensed table-custom', $tc);



    return _div_class('panel panel-primary', _div_class('panel-heading', $panel_heading)._div_class('panel-body', _div_class('pbt', $panel_body)).$r._div_class('panel-footer', $panel_footer));
}

// Сопровождающие функции ===================================================




// Db функции ===============================================================

function __list2hash($eid) {
    $list = _dbs_list($eid);
    for ($i = 0; $i < count($list); $i++) $res[$list[$i]['ID']] = $list[$i][NM];
    return $res;
}

function __list2hash2($eid, $n) {
    $list = _dbs_list($eid);
    for ($i = 0; $i < count($list); $i++) $res[$list[$i]['ID']] = $list[$i][$n];
    return $res;
}


function _db_user($login, $pass) {
    $login = preg_replace('%[^A-Za-zА-Яа-я0-9]%', '', $login);
    $pass = _au_hash($pass);
    return _db_row('SELECT * FROM `user` WHERE `login` = ? AND `password` = ? LIMIT 1', array($login, $pass));
}

// Work функции ==============================================================


function list_ref($e, $n) {
    return _select_name($n, _options_ref(_dbs_list($e), ID, NM));
}

function list_ref_nv($eid, $name, $pn, $pv) {
    $s = _options_ref(_dbs_list($eid), ID, NM);
    return _select_name_pv($name, $pn, $pv, $s);
}

function list_ref_prompt($eid, $name) {
    $curr = $_GET[$name];
    $list = _dbs_list($eid);
    $s = _option_value('0', '--');
    $s .= _options_ref($list, ID, NM, $curr);
    return _select_id_name($name, $name, $s);
}

function list_ref_prompt_class($eid, $name, $c) {
    $curr = $_GET[$name];
    $list = _dbs_list($eid);
    $s = _option_value('0', '--');
    $s .= _options_ref($list, ID, NM, $curr);
    return _select_id_name_class($name, $name, $c, $s);
}

function input_prompt($n, $d) {
    $v = $_GET[$n];
    if (!$v) $v = $d;
    return _input_name_value($n, $v);
}

function input_prompt_class($n, $c, $d) {
    $v = $_GET[$n];
    if (!$v) $v = $d;
    return _input_name_class_value($n, $c, $v);
}


function _notify_mail($to, $subj, $body) {
    mail($to, $subj, $body);
    return TRUE;
}

require('cms/private.php');

// mode
if ($_GET['m'] == 'a') {
    // admin


    if (isset($_POST['log']) && isset($_POST['pwd'])) {
        $user = _db_user($_POST['log'], $_POST['pwd']);
        if ($user) {
            $_SESSION['user'] = $user;
        }
    }
    if (isset($_SESSION['user'])) {
        print _administrative();
    } else {


        print '<html>';
        print _header();
        ?>
        <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Slim[CMS] ver 1.2 :: Авторизация
                        </div>
                        <div class="panel-body">
                            <form name="loginform" id="loginform" action="?m=a" method="POST" role="form">
                                <div class="form-group">
                                    <label for="user_login">Имя пользователя</label>
                                    <input type="text" class="form-control" id="user_login" name="log" placeholder="Имя пользователя">
                                </div>
                                <div class="form-group">
                                    <label for="user_pass">Пароль</label>
                                    <input type="password" class="form-control" id="user_pass" name="pwd" placeholder="Пароль">
                                </div>
                                <button type="submit" class="btn btn-primary">Войти</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </body>
        </html>
    <?
    }


} else if ($_GET['m'] == 'z') {
    // logout
    unset($_SESSION['user']);
    header('Location: http://'.MM_HOST.'/');
} else if ($_GET['m'] == 'i') {
    // install
    _dbs_install();
    print "Install complete";
} else if ($_GET['m'] == 'v') {
    // view text
    $e = intval($_GET['e']);
    $i = intval($_GET['i']);
    $o = _db_item($e, $i);
    print _html(_header()._body($o[$_GET['n']])); // todo security
} else if ($_GET['m'] == 'f') {
    // file manager
    if (isset($_SESSION['user'])) {
        print _html(_head(
                _link_href_rel_type('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css', 'stylesheet', 'text/css').
                _script_src_type('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'text/javascript').
                _script_src_type('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', 'text/javascript').
                _link_href_rel_type('cms/css/elfinder.min.css', 'stylesheet', 'text/css').
                _link_href_rel_type('cms/css/theme.css', 'stylesheet', 'text/css').
                _script_src_type('cms/js/elfinder.min.js', 'text/javascript').
                _script_src_type('cms/js/i18n/elfinder.ru.js', 'text/javascript').
                _script_src_type('cms/js/fm.js', 'text/javascript')
            )._body(_div_id('elfinder', '')));
    }
} else if ($_GET['m'] == 's') {
    // file manager
    if (isset($_SESSION['user'])) {
        print _html(_head(
                _link_href_rel_type('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css', 'stylesheet', 'text/css').
                _script_src_type('http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', 'text/javascript').
                _script_src_type('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js', 'text/javascript').
                _link_href_rel_type('cms/css/elfinder.min.css', 'stylesheet', 'text/css').
                _link_href_rel_type('cms/css/theme.css', 'stylesheet', 'text/css').
                _script_src_type('cms/js/elfinder.min.js', 'text/javascript').
                _script_src_type('cms/js/i18n/elfinder.ru.js', 'text/javascript').
                _script_src_type('cms/js/fm2.js', 'text/javascript')
            )._body(_div_id('elfinder', '')));
    }
} else {

    // host from request
    $__host = $_SERVER['HTTP_HOST'];

    // application
    $application = _db_row_key_value(ESITE, 'DOMAIN', $__host);
    if (!$application) {
        header("HTTP/1.0 404 Not Found");
        print 'Application not found';
        exit;
    }
    $_SESSION[AP] = $application;

    // main page
    $page = _page_application($application);
    if (!$page) {
        header("HTTP/1.0 404 Not Found");
        print "Page not found";
        exit;
    }
    $_SESSION[PG] = $page;

// parse
    $str = $page['CONTENT'];
    header('Content-Type: text/html; charset=utf-8');


// pass?
    print eval('return "'.$str.'";');

//    var_dump($__host);

    function CheckCanGzip(){
        $HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
        if (headers_sent() || connection_aborted()){
            return 0;
        }
        if (strpos($HTTP_ACCEPT_ENCODING,  'x-gzip') !== false) return "x-gzip";
        if (strpos($HTTP_ACCEPT_ENCODING, 'gzip') !== false) return "gzip";
        return 0;
    }

// gzip
    if (MM_GZIP == 1) {
        $ENCODING = CheckCanGzip();
        if ($ENCODING){
            $Contents = ob_get_contents();
            ob_end_clean();
            header("Content-Encoding: $ENCODING");
            print "\x1f\x8b\x08\x00\x00\x00\x00\x00";
            $Size = strlen($Contents);
            $Crc = crc32($Contents);
            $Contents = gzcompress($Contents, MM_GZIP_LEVEL);
            $Contents = substr($Contents,  0,  strlen($Contents) - 4);
            print $Contents;
            print pack('V', $Crc);
            print pack('V', $Size);
        }else{
            ob_end_flush();
        }
    }

}
