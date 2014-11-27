<?

  /**
   * Функция поиска
   * @param $name Наименование поля строки поиска в форме
   * @param $ids Настройки поиска
   * @return string Результат поиска
   */
  function _search($name, $ids) {
    $s = '';
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $t = preg_replace ("[^0-9.a-zA-Zа-яА-Я ]", "", $_GET[$name]);
        foreach ($ids as $id) {
            $eid = $id['eid'];
            $tid = $id['tid'];
            $condition = $id['condition'];
            $field = $id['field'];
            $limit = $id['limit'];
            // поиск
            $where = '';
            if (strlen($condition) > 0) $where .= $condition.' AND ';
            $where .= $field." like '%".$t."%'";
            if (strlen($limit) > 0) $where .= ' LIMIT '.$limit;
            $sql = _sql_select_from_where('message' . $eid, $where);
            $s .= _template($tid, _db_rows($sql)); // todo
        }
    }
    return $s;
  }

  /**
   * @param $poll Объект для полинга
   * @param $uid Идентификатор пользователя
   * @param $cnt Количество полей
   * @param $question Наименование поля вопроса
   * @param $prefix_answer Префикс наименования поля ответа
   * @param $prefix_value Префикс наименования поля значения
   * @param $css Прекс CSS
   * @param $epoll Таблица полинга
   * @param $eres Таблица результатов полинга
   * @param $fpoll Поле номера полинга
   * @param $fuser Поле идентификатора пользователя
   * @return string Код
   */
  function _poll($poll, $uid, $cnt, $question, $prefix_answer, $prefix_value, $css, $epoll, $eres, $fpoll, $fuser) {
      $ex = _db_row('SELECT count(*) as CNT FROM '._dbs_num2table($eres).' WHERE '.$fpoll.' = ? AND '.$fuser.' = ?', array($poll[ID], $uid));
      $a = $ex['CNT'];

      if ($_SERVER['REQUEST_METHOD'] == 'POST' && $a == 0) {
          if (isset($_POST['value'])) {
              $v = intval($_POST['value']);
              _db_exec('INSERT INTO '._dbs_num2table($eres).' ('.$fpoll.', '.$fuser.') VALUES (?, ?)', array($poll[ID], $uid));
              _db_exec('UPDATE '._dbs_num2table($epoll).' SET '.$prefix_value.$v.' = '.$prefix_value.$v.' + 1 WHERE ID = ?', array($poll[ID]));
              $poll[$prefix_value.$v] = $poll[$prefix_value.$v] + 1;
              $a = 1;
          }
      }

      $s = _div_class($css.'_head', $poll[$question]);
      if ($a == 0) {
          // выводить вопросы
          $s .= '<table>';
          for ($i = 0; $i < $cnt; $i++) {
              $a = $poll[$prefix_answer.$i];
              if (strlen($a) > 0) {
                  $id = 'a_'.$i;
                  $s .= '<tr><td><input type=radio name=value value='.$i.' id='.$id.' /></td>';
                  $s .= '<td><label for='.$id.' class=pool-label >'.$a.'</label></td></tr>';
              }
          }
          $s .= '</table>';
          $s .= '<input type=submit value=Ответить />';
          $s = '<form method=post>'.$s.'</form>';
      } else {
          // выводить результаты
          $total = 0;
          for ($i = 0; $i < $cnt; $i++) {
              $total = $total + intval($poll[$prefix_value.$i]);
          }
          $max = 0;
          for ($i = 0; $i < $cnt; $i++) {
              $a = $poll[$prefix_answer.$i];
              if (strlen($a) > 0) {
                  $v = intval($poll[$prefix_value.$i]);
                  if ($v > $max) $max = $v;
              }
          }
          for ($i = 0; $i < $cnt; $i++) {
              $a = $poll[$prefix_answer.$i];
              if (strlen($a) > 0) {
                  $v = intval($poll[$prefix_value.$i]);
                  $p = intval($v * 100 / $total);
                  $s .= _div_class($css.'_result', _div_class($css.'_text', $a).
                      _div_class($css.'_bar', '<span class='.(($v == $max) ? $css.'_win' : $css.'_scale').' style="width: '.$p.'%"></span>'._span_class($css.'_percent', $v.' ('.$p.'%)')));
              }
          }
      }
      return _div_class($css, $s);
  }