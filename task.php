<?

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

$a = $_GET['a'];
$n = $_GET['n'];
$v = $_GET['v'];

if (isset($_SESSION['user'])) { //todo install perm

    $hdr = _link_href_rel('cms/css/bootstrap.min.css', 'stylesheet').
//        _link_href_rel('cms/css/bootstrap-theme.min.css', 'stylesheet'). todo toggle settings
//        _link_href_rel('cms/css/site.css', 'stylesheet').
        _script_src('http://code.jquery.com/jquery-latest.js'). //todo inner
        _script_src('cms/js/bootstrap.min.js');

    if ('upload' == $a) {
        $file = $n.'-'.$v.'.tar.gz';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, "http://512.kz/lib/".$file);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result) {
            $full = sys_get_temp_dir().'/'.$file;

            // write to temp dir
            $handle = fopen($full, "w");
            fwrite($handle, $result);
            fclose($handle);

            // extract
            $p0 = new PharData($full);
            $names = array();
            foreach (new RecursiveIteratorIterator($p0) as $f) {
                $name = $f->getPathname();
                $pos = strpos($name, $full);
                $path = substr($name, $pos + strlen($full));
                array_push($names, $path);
            }
            $p0->extractTo(__DIR__, NULL, true);
            unlink($full);

            _db_exec('INSERT INTO '.TLIBRARY.' (NAME, VERSION, FILES) VALUES (?, ?, ?)', array($n, $v, implode(',', $names)));

            print _html(_head($hdr)._body(_div_class('jumbotron',
                    _h3('SlimCMS асинхронный диспетчер').
                    _div_class('alert alert-info', 'Установка библиотеки '._b($file).' успешно завершена').
                    _a_class_href('btn btn-primary btn-lg', '/?m=a&q=m&e=-11', 'Перейти в CMS'))));
        } else {

            print 'Не завершена';
        }
    } else if ('remove' == $a) {
        $lib = _db_row('SELECT * FROM '.TLIBRARY.' WHERE NAME = ? AND VERSION = ?', array($n, $v));
        if ($lib) {
            $files = $lib['FILES'];
            foreach (explode(',', $files) as $file) {
                unlink(__DIR__.$file);
            }
            _db_exec('DELETE FROM '.TLIBRARY.' WHERE NAME = ? AND VERSION = ?', array($n, $v));

            print _html(_head($hdr)._body(_div_class('jumbotron',
                    _h3('SlimCMS асинхронный диспетчер').
                    _div_class('alert alert-info', 'Библиотека '._b($n.'-'.$v.'.tar.gz').' успешно удалена').
                    _a_class_href('btn btn-primary btn-lg', '/?m=a&q=m&e=-11', 'Перейти в CMS'))));
        } else {
            print _html(_head($hdr)._body(_div_class('jumbotron',
                    _h3('SlimCMS асинхронный диспетчер').
                    _div_class('alert alert-info', 'Запрашиваемой библиотеки '._b($n.'-'.$v.'.tar.gz').' не существует').
                    _a_class_href('btn btn-primary btn-lg', '/?m=a&q=m&e=-11', 'Перейти в CMS'))));
        }
    }
}


