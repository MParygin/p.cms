<?

/**
 * Вычисление хеша текущим методом хеширования
 *
 * @param $v Хешируемая строка
 * @return string Хеш
 */
function _au_hash($v) {
    return (AU_METHOD == 1) ? md5(AU_SALT.$v.AU_SALT) : sha1(AU_SALT.$v.AU_SALT);
}
