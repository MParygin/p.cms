<?php

function _a_href($href, $str) { return '<a href="'.$href.'">'.$str.'</a>'; }
function _a_class_href($class, $href, $str) { return '<a class="'.$class.'" href="'.$href.'">'.$str.'</a>'; }
function _a_href_id($href, $id, $str) { return '<a href="'.$href.'" id="'.$id.'">'.$str.'</a>'; }
function _body($str) { return '<body>'.$str.'</body>'; }
function _b($str) { return '<strong>'.$str.'</strong>'; }
function _br() { return '<br/>'; }
function _code($str) { return '<code>'.$str.'</code>'; }
function _datalist_id($id, $o) { $r = ''; for ($i = 0; $i < count($o); $i++) $r .= '<option value="'.$o[$i].'"/>'; return '<datalist id="'.$id.'">'.$r.'</datalist>'; }
function _div($str) { return '<div>'.$str.'</div>'; }
function _div_class($class, $str) { return '<div class="'.$class.'">'.$str.'</div>'; }
function _div_class_id($class, $id, $str) { return '<div class="'.$class.'" id="'.$id.'">'.$str.'</div>'; }
function _div_id($id, $str) { return '<div id="'.$id.'">'.$str.'</div>'; }
function _div_style($style, $str) { return '<div style="'.$style.'">'.$str.'</div>'; }
function _form_action($action, $str) { return '<form action="'.$action.'">'.$str.'</form>'; }
function _form_post_action($action, $str) { return '<form action="'.$action.'" method="post">'.$str.'</form>'; }
function _form_post_action_file($action, $str) { return '<form action="'.$action.'" method="post" enctype="multipart/form-data">'.$str.'</form>'; }
function _h1($str) { return '<h1>'.$str.'</h1>'; }
function _h2($str) { return '<h2>'.$str.'</h2>'; }
function _h3($str) { return '<h3>'.$str.'</h3>'; }
function _h4($str) { return '<h4>'.$str.'</h4>'; }
function _head($str) { return '<head>'.$str.'</head>'; }
function _html($str) { return '<!DOCTYPE html><html lang="ru">'.$str.'</html>'; }
function _hr() { return '<hr/>'; }
function _i_class($class, $str) { return '<i class="'.$class.'">'.$str.'</i>'; }
function _img_src($src) { return '<img src="'.$src.'"/>'; }
function _input_name_value($name, $value) { return '<input name="'.$name.'" value="'.$value.'"/>'; }
function _input_name_class_value($name, $class, $value) { return '<input name="'.$name.'" class="'.$class.'" value="'.$value.'"/>'; }
function _input_id_name_value($id, $name, $value) { return '<input id="'.$id.'" name="'.$name.'" value="'.$value.'"/>'; }
function _input_id_name_value_maxlength($id, $name, $value, $max) { return '<input id="'.$id.'" name="'.$name.'" value="'.$value.'" maxlength="'.$max.'"/>'; }
function _input_id_name_value_maxlength_list($id, $name, $value, $max, $list) { return '<input id="'.$id.'" name="'.$name.'" value="'.$value.'" maxlength="'.$max.'" list="'.$list.'"/>'; }
function _input_hidden_name_value($name, $value) { return '<input type="hidden" name="'.$name.'" value="'.$value.'"/>'; }
function _input_file_name($name) { return '<input type="file" name="'.$name.'"/>'; }
function _input_submit_id_value($id, $value) { return '<input type="submit" id="'.$id.'" value="'.$value.'"/>'; }
function _input_hidden_id_name_value($id, $name, $value) { return '<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'"/>'; }
function _label_class($class, $str) { return '<label class="'.$class.'">'.$str.'</label>'; }
function _li($str) { return '<li>'.$str.'</li>'; }
function _li_class($class, $str) { return '<li'.(($class != NULL) ? ' class="'.$class.'"' : '').'>'.$str.'</li>'; }
function _link_href_rel($href, $rel) { return '<link href="'.$href.'" rel="'.$rel.'"/>'; }
function _link_href_rel_type($href, $rel, $type) { return '<link href="'.$href.'" rel="'.$rel.'" type="'.$type.'"/>'; }
function _meta_name_content($name, $content) { return '<meta name="'.$name.'" content="'.$content.'"/>'; }
function _ol_class($class, $str) { return '<ol class="'.$class.'">'.$str.'</ol>'; }
function _option_value($value, $str) { return '<option value="'.$value.'">'.$str.'</option>'; }
function _option_value_selected($value, $str) { return '<option value="'.$value.'" selected="selected">'.$str.'</option>'; }
function _p($str) { return '<p>'.$str.'</p>'; }
function _p_class($class, $str) { return '<p class="'.$class.'">'.$str.'</p>'; }
function _select_id_name($id, $name, $str) { return '<select id="'.$id.'" name="'.$name.'">'.$str.'</select>'; }
function _select_id_name_class($id, $name, $class, $str) { return '<select id="'.$id.'" name="'.$name.'" class="'.$class.'">'.$str.'</select>'; }
function _select_name($name, $str) { return '<select name="'.$name.'">'.$str.'</select>'; }
function _select_name_pv($name, $pn, $pv, $str) { return '<select name="'.$name.'" '.$pn.'="'.$pv.'">'.$str.'</select>'; }
function _select_name_class($name, $class, $str) { return '<select name="'.$name.'" class="'.$class.'">'.$str.'</select>'; }
function _script($str) { return '<script type="text/javascript" charset="utf-8">'.$str.'</script>'; }
function _script_src($str) { return '<script src="'.$str.'"></script>'; }
function _script_src_type($str, $type) { return '<script src="'.$str.'" type="'.$type.'"></script>'; }
function _span($str) { return '<span>'.$str.'</span>'; }
function _span_class($class, $str) { return '<span class="'.$class.'">'.$str.'</span>'; }
function _span_class_id($class, $id, $str) { return '<span class="'.$class.'" id="'.$id.'">'.$str.'</span>'; }
function _td($str) { return '<td>'.$str.'</td>'; }
function _textarea_name($name, $str) { return '<textarea name="'.$name.'">'.$str.'</textarea>'; }
function _textarea_name_class($name, $class, $str) { return '<textarea name="'.$name.'" class="'.$class.'">'.$str.'</textarea>'; }
function _table($str) { return '<table>'.$str.'</table>'; }
function _table_class($class, $str) { return '<table class="'.$class.'">'.$str.'</table>'; }
function _td_class($str, $class) { return '<td class="'.$class.'">'.$str.'</td>'; }
function _th($str) { return '<th>'.$str.'</th>'; }
function _th_class($class, $str) { return '<th class="'.$class.'">'.$str.'</th>'; }
function _th_style($style, $str) { return '<th style="'.$style.'">'.$str.'</th>'; }
function _title($str) { return '<title>'.$str.'</title>'; }
function _tr($str) { return '<tr>'.$str.'</tr>'; }
function _ul_class($class, $str) { return '<ul class="'.$class.'">'.$str.'</ul>'; }

function _tag_open($n) { return '<'.$n; }
function _tag_close() { return '/>'; }
function _tag_stop() { return '>'; }
function _tag_second($n) { return '</'.$n.'>'; }
function _attr($n, $v) { return ' '.$n.'="'.$v.'"'; }
function _attr_class($v) { return _attr('class', $v); }
function _attr_maxlength($v) { return _attr('maxlength', $v); }
function _attr_name($v) { return _attr('name', $v); }
function _attr_placeholder($v) { return _attr('placeholder', $v); }
function _attr_required($a) { if ($a != NULL) { return _attr('required', ''); } else { return ''; }; }
function _attr_type($v) { return _attr('type', $v); }
function _attr_value($v) { return _attr('value', $v); }

function _rn() { return "\r\n"; }

function ___ui_btn($href, $icon, $label) { return _a_class_href('btn btn-primary', $href, _i_class($icon, '').' '.$label); }
function ___ui_a($href, $icon, $label) { return _a_href($href, _i_class($icon, '').' '.$label); }
function ___ui_alert_danger($str) { return _div_class('alert alert-danger', $str); }
function ___ui_alert_info($str) { return _div_class('alert alert-info', $str); }
function ___ui_label_info($str) { return _span_class('label label-info', $str); }

function ___ui_btn_table($href) { return ___ui_btn($href, 'glyphicon glyphicon-list', 'Таблица'); }


function _options($arr, $values, $curr) {
    $s = '';
    for ($i = 0; $i < count($arr); $i++) {
        if ($curr == $arr[$i]) {
            $s .= _option_value_selected($values[$i], $arr[$i]);
        } else {
            $s .= _option_value($values[$i], $arr[$i]);
        }
    }
    return $s;
}

function _options_ref($arr, $value_idx, $label_idx, $curr = NULL) {
    $s = '';
    foreach ($arr as $i) {
        if ($i[$value_idx] == $curr) {
            $s .= _option_value_selected($i[$value_idx], $i[$label_idx]);
        } else {
            $s .= _option_value($i[$value_idx], $i[$label_idx]);
        }
    }
    return $s;
}
