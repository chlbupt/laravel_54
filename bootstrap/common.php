<?php
// request related functions
function get_query_string()
{
    global $_SERVER;

    if (isset($_SERVER["QUERY_STRING"]))
    {
        return $_SERVER["QUERY_STRING"];
    }
    return "";
}
function _TMP_L($txt)
{
    return $txt;
}
function validate_form(&$f, $rules)
{
    $fn_lang = function_exists('L') ? 'L' : '_TMP_L';
    foreach($rules as $k => $v)
    {
        if ($v["required"])
        {
            if (!isset($f[$k]) || ($f[$k] !== '0' && empty($f[$k])))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("All required fields must not be empty.");
            }
        }
        if (isset($v["maxlength"]))
        {
            if (strlen($f[$k]) > $v["maxlength"])
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Characters too long.");
            }
        }
        if (isset($v["minlength"]))
        {
            if (strlen($f[$k]) < $v["minlength"])
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Characters is not enough.");
            }
        }
        if (isset($v["email"]))
        {
            if (!preg_match("/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Email address is not valid.");
            }
        }
        else if (isset($v["url"]))
        {
            if (!preg_match("/^(https?:\/\/)?(((www\.)?[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)?\.([a-zA-Z]+))|(([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5])\.([0-1]?[0-9]?[0-9]|2[0-5][0-5]))(\:\d{0,4})?)(\/[\w- .\/?%&=]*)?$/i", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("URL is not valid.");
            }
        }
        else if (isset($v["dataISO"]))
        {
            if (!preg_match("/^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Date is not valid.");
            }
        }
        else if (isset($v["number"]))
        {
            if (!preg_match("/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Number is not valid.");
            }
            if (isset($v["range"]))
            {
                $num = floatval($f[$k]);
                if ($num < $v["range"][0] || $num > $v["range"][1])
                {
                    return isset($v["msg"]) ? $v["msg"] : $fn_lang("Number is not in range.");
                }
            }
        }
        else if (isset($v["digits"]))
        {
            if (!preg_match("/^\d+$/", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Digit is not valid.");
            }
        }
        else if (isset($v["phone"]))
        {
            if (!preg_match("/^1[3|4|5|8|7|9]\d{9}$/", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Phone is not valid.");
            }
        }
        else if (isset($v["creditcard"]))
        {
            if (!preg_match("/[^0-9 \-]+/", $f[$k]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("Credit card is not valid.");
            }
        }
        else if (isset($v["in_array"]))
        {
            if (!isset($f[$k]) || !in_array($f[$k], $v["in_array"]))
            {
                return isset($v["msg"]) ? $v["msg"] : $fn_lang("All required fields must not be empty.");
            }
        }
    }
    return true;
}
function create_form($rules)
{
    $f = array();

    foreach ($rules as $k => $v)
    {
        $f[$k] = P($k);
    }
    if (!validate_form($f, $rules))
    {
        return false;
    }
    return $f;
}
function goto_url($url)
{
    header("Location: $url");
    exit();
}
function P($name, $default = NULL)
{
    global $_GET, $_POST;

    if (isset($_GET[$name]))
    {
        return $_GET[$name];
    }
    if (isset($_POST[$name]))
    {
        return $_POST[$name];
    }
    return $default;
}
function P_from_array($name, &$arr, $default = NULL)
{
    if ($arr && isset($arr[$name]))
    {
        return $arr[$name];
    }
    return $default;
}
function R_from_array($name, &$ref, &$arr, $default = NULL)
{
    if ($arr && isset($arr[$name]))
    {
        $ref = &$arr[$name];
    }
    else
    {
        $ref = $default;
    }
}
function is_field_not_equal(&$arr, $key, $v)
{
    return !isset($arr[$key]) || $arr[$key] != $v;
}
function is_one_key_empty(&$arr, $keys)
{
    if (is_string($keys))
    {
        $keys = array($keys);
    }
    foreach ($keys as $key)
    {
        if (!isset($arr[$key]) || !$arr[$key])
        {
            return true;
        }
    }
    return false;
}
function array_to_p(&$arr)
{
    $sep = '';
    $p = '';
    foreach($arr as $k => &$v)
    {
        $p .= $sep.$k.'='.urlencode($v);
        $sep = '&';
    }
    return $p;
}
function array_from_key(&$arr, $key, &$dst)
{
    $dst = array();
    foreach ($arr as &$item)
    {
        $dst[] = $item[$key];
    }
}
function set_val_when_empty(&$arr, $keys, $val)
{
    if (is_string($keys)) $keys = array($keys);
    foreach ($keys as &$key) {
        if (!isset($arr[$key]) || !$arr[$key]) $arr[$key] = $val;
    }
}
function file_extension_to_type( $ext )
{
    $ext = strtolower( $ext );
    $ext2type = array(
        'image'       => array( 'jpg', 'jpeg', 'jpe',  'gif',  'png',  'bmp',   'tif',  'tiff', 'ico' ),
        'audio'       => array( 'aac', 'ac3',  'aif',  'aiff', 'm3a',  'm4a',   'm4b',  'mka',  'mp1',  'mp2',  'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma' ),
        'video'       => array( '3g2',  '3gp', '3gpp', 'asf', 'avi',  'divx', 'dv',   'flv',  'm4v',   'mkv',  'mov',  'mp4',  'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt',  'rm', 'vob', 'wmv' ),
        'document'    => array( 'doc', 'docx', 'docm', 'dotm', 'odt',  'pages', 'pdf',  'xps',  'oxps', 'rtf',  'wp',   'wpd' ),
        'spreadsheet' => array( 'numbers',     'ods',  'xls',  'xlsx', 'xlsm',  'xlsb' ),
        'interactive' => array( 'swf', 'key',  'ppt',  'pptx', 'pptm', 'pps',   'ppsx', 'ppsm', 'sldx', 'sldm', 'odp' ),
        'text'        => array( 'asc', 'csv',  'tsv',  'txt' ),
        'archive'     => array( 'bz2', 'cab',  'dmg',  'gz',   'rar',  'sea',   'sit',  'sqx',  'tar',  'tgz',  'zip', '7z' ),
        'code'        => array( 'css', 'htm',  'html', 'php',  'js' ),
    );
    foreach ( $ext2type as $type => &$exts )
        if ( in_array( $ext, $exts ) )
            return $type;
    return null;
}
function to_json_response($result, $info, $info_type = 'error')
{
    $json = array('success' => $result, 'info' => $info);
    if ($info_type)
    {
        $json['info_type'] = $info_type;
    }
    return $json;
}
function get_mime_types()
{
    return array(
        // Image formats.
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'tif' => 'image/tiff',
        'tiff' => 'image/tiff',
        'ico' => 'image/x-icon',
        // Video formats.
        'asf' => 'video/x-ms-asf',
        'asx' => 'video/x-ms-asf',
        'wmv' => 'video/x-ms-wmv',
        'wmx' => 'video/x-ms-wmx',
        'wm' => 'video/x-ms-wm',
        'avi' => 'video/avi',
        'divx' => 'video/divx',
        'flv' => 'video/x-flv',
        'mov' => 'video/quicktime',
        'qt' => 'video/quicktime',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'mp4' => 'video/mp4',
        'm4v' => 'video/mp4',
        'ogv' => 'video/ogg',
        'webm' => 'video/webm',
        'mkv' => 'video/x-matroska',
        '3gp' => 'video/3gpp', // Can also be audio
        '3gpp' => 'video/3gpp', // Can also be audio
        '3g2' => 'video/3gpp2', // Can also be audio
        '3gp2' => 'video/3gpp2', // Can also be audio
        // Text formats.
        'txt' => 'text/plain',
        'asc' => 'text/plain',
        'c' => 'text/plain',
        'cc' => 'text/plain',
        'h' => 'text/plain',
        'srt' => 'text/plain',
        'csv' => 'text/csv',
        'tsv' => 'text/tab-separated-values',
        'ics' => 'text/calendar',
        'rtx' => 'text/richtext',
        'css' => 'text/css',
        'htm' => 'text/html',
        'html' => 'text/html',
        'vtt' => 'text/vtt',
        'dfxp' => 'application/ttaf+xml',
        // Audio formats.
        'mp3' => 'audio/mpeg',
        'm4a' => 'audio/mpeg',
        'm4b' => 'audio/mpeg',
        'ra' => 'audio/x-realaudio',
        'ram' => 'audio/x-realaudio',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'oga' => 'audio/ogg',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'wma' => 'audio/x-ms-wma',
        'wax' => 'audio/x-ms-wax',
        'mka' => 'audio/x-matroska',
        // Misc application formats.
        'rtf' => 'application/rtf',
        'js' => 'application/javascript',
        'pdf' => 'application/pdf',
        'swf' => 'application/x-shockwave-flash',
        'class' => 'application/java',
        'tar' => 'application/x-tar',
        'zip' => 'application/zip',
        'gz' => 'application/x-gzip',
        'gzip' => 'application/x-gzip',
        'rar' => 'application/rar',
        '7z' => 'application/x-7z-compressed',
        'exe' => 'application/x-msdownload',
        // MS Office formats.
        'doc' => 'application/msword',
        'pot' => 'application/vnd.ms-powerpoint',
        'pps' => 'application/vnd.ms-powerpoint',
        'ppt' => 'application/vnd.ms-powerpoint',
        'wri' => 'application/vnd.ms-write',
        'xla' => 'application/vnd.ms-excel',
        'xls' => 'application/vnd.ms-excel',
        'xlt' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel',
        'mdb' => 'application/vnd.ms-access',
        'mpp' => 'application/vnd.ms-project',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
        'onetoc' => 'application/onenote',
        'onetoc2' => 'application/onenote',
        'onetmp' => 'application/onenote',
        'onepkg' => 'application/onenote',
        'oxps' => 'application/oxps',
        'xps' => 'application/vnd.ms-xpsdocument',
        // OpenOffice formats.
        'odt' => 'application/vnd.oasis.opendocument.text',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'odg' => 'application/vnd.oasis.opendocument.graphics',
        'odc' => 'application/vnd.oasis.opendocument.chart',
        'odb' => 'application/vnd.oasis.opendocument.database',
        'odf' => 'application/vnd.oasis.opendocument.formula',
        // WordPerfect formats.
        'wp' => 'application/wordperfect',
        'wpd' => 'application/wordperfect',
        // iWork formats.
        'key' => 'application/vnd.apple.keynote',
        'numbers' => 'application/vnd.apple.numbers',
        'pages' => 'application/vnd.apple.pages',
    );
}
function no_cache()
{
    // no cache
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
}
function req_not_found()
{
    header("HTTP/1.1 404 Not Found");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
}
function set_content_type($content_type = 'text/plain', $is_image = false)
{
    // Date in the past
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    // always modified
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    if ($is_image)
    {
        header("Content-type: $content_type");
    }
    else
    {
        header("Content-type: $content_type;charset=".WEB_CHARSET);
    }
}
function download_file($file_path, $file_name, $content_type = "application/force-download",$attachment = true)
{
    if (file_exists($file_path))
    {
        $file_size = filesize($file_path);
        header('Pragma: public');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Transfer-Encoding: binary');
        header('Content-Encoding: none');
        header('Content-type: '.$content_type);
        if ($attachment)
        {
            header('Content-Disposition: attachment; filename='.$file_name);
        }
        header('Content-length: '.$file_size);
        readfile($file_path);
    }
    else
    {
        req_not_found();
    }
}
function set_output_file($file_name, $content_type = "application/force-download",$attachment = true)
{
    header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
    header('Content-Transfer-Encoding: binary');
    header('Content-Encoding: none');
    header('Content-type: '.$content_type);
    if ($attachment)
    {
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
    }
}
// db related functions
function encode_slash($data)
{
    if (!isset($data))
    {
        return "";
    }
    if(get_magic_quotes_gpc())
    {
        $data = stripslashes($data);
    }
    return $data;
}
function encode_array_slash(&$arr)
{
    if (!is_array($arr))
    {
        $arr = encode_slash($arr);
        return;
    }
    foreach($arr as $key => &$item)
    {
        $item = encode_slash($item);
    }
}
function unset_data(&$data, $key)
{
    if (!is_array($key))
    {
        $key = array($key);
    }
    foreach ($key as $item)
    {
        if (isset($data[$item]))
        {
            unset($data[$item]);
        }
    }
}
// charset related functions
function change_charset($data, $from, $to)
{
    if($data)
    {
        return iconv($from, $to, $data);
    }
    return $data;
}
function change_array_charset(&$data, $from, $to)
{
    foreach($data as $key => &$item)
    {
        if(!is_array($item))
        {
            $data[$key] = change_charset($from, $to, $data);
        }
    }
}
// file related functions
function txt_file_to_array($file_path, $sep = "\n")
{
    if (!file_exists($file_path))
    {
        return null;
    }
    $content = @file_get_contents($file_path);
    $items = explode($sep, $content);
    $ret = array();
    foreach ($items as $item)
    {
        $item = trim($item);
        if (!empty($item))
        {
            $ret[] = $item;
        }
    }
    return count($ret) > 0 ? $ret : null;
}
function txt_file_to_map($file_path, $sep = "\n", $key_sep = '=')
{
    if (!file_exists($file_path))
    {
        return null;
    }
    $content = @file_get_contents($file_path);
    $items = explode($sep, $content);
    $ret = array();
    foreach ($items as $item)
    {
        $item = trim($item);
        if (empty($item))
        {
            continue;
        }
        $pos = strpos($item, $key_sep);
        if ($pos !== false) {
            $key = substr($item, 0, $pos);
            $key = trim($key);
            $ret[$key] = trim(substr($item, $pos + 1));
        }
    }
    return count($ret) > 0 ? $ret : null;
}
function is_dot_dir(&$dir)
{
    return $dir == '.' || $dir == '..';
}
function xcopy($src, $dst)
{
    if (is_dir($src))
    {
        if (!file_exists($dst))
        {
            @mkdir($dst);
        }
    }
    $src = realpath($src);
    $dst = realpath($dst);
    if (is_windows())
    {
        system("xcopy /q /e /y $src\\* $dst");
    }
    else
    {
        system("scp -r $src/* $dst");
    }
}
function copy_files($src, $dst, $files)
{
    foreach ($files as $file)
    {
        $src_file = join_paths($src, $file);
        if (file_exists($src_file))
        {
            @copy($src_file, join_paths($dst, $file));
        }
    }
}
function copy_files_with_prefix($src, $dst, $prefix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !starts_with($file, $prefix))
        {
            continue;
        }
        $src_file = join_paths($src, $file);
        if (file_exists($src_file))
        {
            @copy($src_file, join_paths($dst, $file));
        }
    }
}
function unlink_files_with_prefix($src, $prefix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !starts_with($file, $prefix))
        {
            continue;
        }
        @unlink(join_paths($src, $file));
    }
}
function copy_files_with_suffix($src, $dst, $suffix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !ends_with($file, $suffix))
        {
            continue;
        }
        $src_file = join_paths($src, $file);
        if (file_exists($src_file))
        {
            @copy($src_file, join_paths($dst, $file));
        }
    }
}
function rename_files_with_prefix($src, $dst, $prefix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !starts_with($file, $prefix))
        {
            continue;
        }
        $src_file = join_paths($src, $file);
        if (file_exists($src_file))
        {
            @rename($src_file, join_paths($dst, $file));
        }
    }
}
function rename_files_with_suffix($src, $dst, $suffix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !ends_with($file, $suffix))
        {
            continue;
        }
        $src_file = join_paths($src, $file);
        if (file_exists($src_file))
        {
            @rename($src_file, join_paths($dst, $file));
        }
    }
}
function unlink_files_with_suffix($src, $suffix)
{
    $files = scandir($src);
    foreach ($files as $file)
    {
        if (is_dot_dir($file) || !ends_with($file, $suffix))
        {
            continue;
        }
        @unlink(join_paths($src, $file));
    }
}
function all_file_exists($files)
{
    foreach ($files as &$item)
    {
        if (!file_exists($item))
        {
            return false;
        }
    }
    return true;
}
function create_dirs($dirs)
{
    if (!$dirs)
    {
        return;
    }
    foreach ($dirs as $dir)
    {
        @mkdir($dir);
    }
}
function dir_size($dir) {
    $handle = opendir($dir);
    $size = 0;
    while (false !== ($file = readdir($handle))) {
        if (is_dot_dir($file)) continue;
        $file_path = "$dir/$file";
        if (is_dir($file_path)) {
            $size += dir_size($file_path);
        } else {
            $tmp = filesize($file_path);
            if ($tmp >= 0) $size += $tmp;
            else $size += intval(sprintf('%u', $tmp));
        }
    }
    closedir($handle);
    return $size;
}
function file_ext($name)
{
    $pos = strrpos($name, '.');
    if ($pos === false)
    {
        return "";
    }
    return substr($name, $pos + 1);
}
function get_file_name($name)
{
    $pos = strrpos($name, '.');
    return $pos === false ? $name : substr($name, 0, $pos);
}
function echo_file($file)
{
    if (file_exists($file))
    {
        echo(file_get_contents($file));
    }
}
function echo_js_file($file)
{
    if (file_exists($file))
    {
        echo('<script>');
        echo(file_get_contents($file));
        echo('</script>');
    }
}
function join_paths()
{
    $args = func_get_args();
    $paths = array();
    foreach ($args as $arg) {
        $paths = array_merge($paths, (array)$arg);
    }
    return is_windows() ? join("\\", $paths) : join('/', $paths);
}
function data_from_excel_file(&$data, $file_path, $fields, $additional_data = NULL, $unique_indexes = NULL, &$unique = NULL)
{
    require_once(PP_INC_ROOT . '/PHPExcel.php');
    require_once(PP_INC_ROOT . '/PHPExcel/IOFactory.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel5.php');
    $data = array();
    if (!file_exists($file_path))
    {
        return;
    }
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($file_path);
    $objWorksheet = $objPHPExcel->getSheet(0);
    $rows = $objWorksheet->getHighestRow();
    $cols = $objWorksheet->getHighestColumn();
    $cols = PHPExcel_Cell::columnIndexFromString($cols);
    $max_cols = count($fields);
    for ($i = 2; $i <= $rows; ++$i)
    {
        $r = array();
        for ($j = 0; $j < $cols; ++$j)
        {
            if ($j >= $max_cols)
            {
                break;
            }
            $r[$fields[$j]] = $objWorksheet->getCellByColumnAndRow($j, $i)->getValue();
        }
        if ($additional_data)
        {
            copy_from($r, $additional_data);
        }
        $data[] = $r;
    }
}
function data_from_excel_file_ex(&$data, $file_path, $fields, $unique_indexes, &$unique, $additional_data = NULL)
{
    require_once(PP_INC_ROOT . '/PHPExcel.php');
    require_once(PP_INC_ROOT . '/PHPExcel/IOFactory.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel5.php');
    $data = array();
    if (!file_exists($file_path))
    {
        return;
    }
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($file_path);
    $objWorksheet = $objPHPExcel->getSheet(0);
    $rows = $objWorksheet->getHighestRow();
    $cols = $objWorksheet->getHighestColumn();
    $cols = PHPExcel_Cell::columnIndexFromString($cols);
    $max_cols = count($fields);
    for ($j = 0; $j < $cols; ++$j)
    {
        if ($j >= $max_cols)
        {
            break;
        }
        $data['header'][] = $objWorksheet->getCellByColumnAndRow($j, 1)->getValue();
    }
    for ($i = 2; $i <= $rows; ++$i)
    {
        $r = array();
        for ($j = 0; $j < $cols; ++$j)
        {
            if ($j >= $max_cols)
            {
                break;
            }
            $k = &$fields[$j];
            $r[$k] = $objWorksheet->getCellByColumnAndRow($j, $i)->getValue();
            $v = &$r[$k];
            if (isset($unique_indexes[$j]) && $unique_indexes[$j])
            {
                if (!is_array($unique_indexes[$j]))
                {
                    $unique_indexes[$j] = array();
                }
                if (!isset($unique_indexes[$j][$v]))
                {
                    $unique_indexes[$j][$v] = true;
                    $unique[$j][] = $v;
                }
            }
        }
        if ($additional_data)
        {
            copy_from($r, $additional_data);
        }
        $data[] = $r;
    }
}
function data_to_excel($fileName, $data, $type='', $color='', $version='2007')
{
    require_once(PP_INC_ROOT . '/PHPExcel.php');
    require_once(PP_INC_ROOT . '/PHPExcel/IOFactory.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel5.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel2007.php');
    if(empty($data) || !is_array($data)){
        die("data must be a array");
    }
    if(empty($fileName)){
        exit;
    }
    $date = date("Y-m-d H-i-s");
    if($version == '2007') {
        $fileName .= "_{$date}.xlsx";
    } else {
        $fileName .= "_{$date}.xls";
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->removeSheetByIndex();
    $j = 0;
    foreach($data as $t => $d){
        $objPHPExcel->createSheet();
        $i = 1;
        foreach($d['data'] as $n => $r){
            $key = ord("A");
            foreach($r as $k => $v){
                $colum = chr($key);
                if($type == 'string') {
                    $objPHPExcel->setActiveSheetIndex($j)->setCellValueExplicit($colum.$i, $v, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet($j)->getStyle($colum.$i)->getFont()->setName('宋体');
                    $objPHPExcel->getActiveSheet($j)->getStyle($colum.$i)->getFont()->setSize(12);
                    $objStyle = $objPHPExcel->getActiveSheet($j)->getStyle($colum."1");
                } else {
                    $objPHPExcel->setActiveSheetIndex($j)->setCellValue($colum.$i, $v);
                }
                if($colum.$i == $colum.'1') {
                    $objFill = $objStyle->getFill();
                    $objAlign = $objStyle->getAlignment();
                    $objAlign->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objAlign->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    $objFill->getStartColor()->setARGB("00".$color);
                    if($d['width']) {
                        $objPHPExcel->setActiveSheetIndex($j)->getColumnDimension($colum)->setWidth($d['width']["$colum"]);
                    }
                } else {
                    if($colum == 'A') {
                        $objPHPExcel->getActiveSheet()->getStyle($colum.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    //水平方向上对齐
                    }
                }
                $key += 1;
            }
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle($d['title']);
        $objPHPExcel->setActiveSheetIndex($j);
        $j++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName);
    if($version == '2007') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    } else {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
function data_to_excel_cmd($fileName, $data, $type='', $color='', $version='2007')
{
    require_once(PP_INC_ROOT . '/PHPExcel.php');
    require_once(PP_INC_ROOT . '/PHPExcel/IOFactory.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel5.php');
    require_once(PP_INC_ROOT . '/PHPExcel/Reader/Excel2007.php');
    if(empty($data) || !is_array($data)){
        die("data must be a array");
    }
    if(empty($fileName)){
        exit;
    }
    $date = date("Y-m-d-H-i-s");
    if($version == '2007') {
        $fileName .= "_{$date}.xlsx";
    } else {
        $fileName .= "_{$date}.xls";
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->removeSheetByIndex();
    $j = 0;
    foreach($data as $t => $d){
        $objPHPExcel->createSheet();
        $i = 1;
        foreach($d['data'] as $n => $r){
            $key = ord("A");
            foreach($r as $k => $v){
                $colum = chr($key);
                if($type == 'string') {
                    $objPHPExcel->setActiveSheetIndex($j)->setCellValueExplicit($colum.$i, $v, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet($j)->getStyle($colum.$i)->getFont()->setName('宋体');
                    $objPHPExcel->getActiveSheet($j)->getStyle($colum.$i)->getFont()->setSize(12);
                    $objStyle = $objPHPExcel->getActiveSheet($j)->getStyle($colum."1");
                } else {
                    $objPHPExcel->setActiveSheetIndex($j)->setCellValue($colum.$i, $v);
                }
                if($colum.$i == $colum.'1') {
                    $objFill = $objStyle->getFill();
                    $objAlign = $objStyle->getAlignment();
                    $objAlign->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objAlign->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objFill->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
                    $objFill->getStartColor()->setARGB("00".$color);
                    if($d['width']) {
                        $objPHPExcel->setActiveSheetIndex($j)->getColumnDimension($colum)->setWidth($d['width']["$colum"]);
                    }
                } else {
                    if($colum == 'A') {
                        $objPHPExcel->getActiveSheet()->getStyle($colum.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    //水平方向上对齐
                    }
                }
                $key += 1;
            }
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle($d['title']);
        $objPHPExcel->setActiveSheetIndex($j);
        $j++;
    }
    $fileName = iconv("utf-8", "gb2312", $fileName);
    if($version == '2007') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($fileName);
        //return true;
        echo $fileName."\n";
    } else {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileName);
        echo $fileName."\n";
    }
}
// date & time related functions
function get_micro_time() {
    return microtime(true) * 1000;
}
function get_next_day_time($t = 0, $offset = 0)
{
    if ($t === 0)
    {
        $t = time();
    }
    $h = intval(@date("H", $t));
    $minute = intval(@date("i", $t));
    $s = intval(@date("s", $t));
    $y = intval(@date("Y", $t));
    $m = intval(@date("m", $t));
    $d = intval(@date("d", $t)) + $offset;
    return @mktime($h, $minute, $s, $m, $d, $y);
}
function get_datetime($t = 0)
{
    return @date("Y-m-d H:i:s", $t == 0 ? time() : $t);
}
function get_datetime_string($t)
{
    if (empty($t))
    {
        return '--';
    }
    return @date("Y/m/d H:i:s", $t);
}
function datetime_to_int($timestamp, $format = 'Y-m-d H:i:s')
{
    $ret = @date_parse_from_format($format, $timestamp);
    if ($ret['year'] === false || $ret['month'] === false || $ret['day'] === false ||
        $ret['hour'] === false || $ret['minute'] === false || $ret['second'] === false) {
        return 0;
    }
    return @mktime($ret['hour'], $ret['minute'], $ret['second'], $ret['month'], $ret['day'], $ret['year']);
}
function get_date($t = 0, $offset = 0, $sep = '/')
{
    $format = 'Y'.$sep.'m'.$sep.'d';
    if ($offset === 0)
    {
        return @date($format, $t == 0 ? time() : $t);
    }
    get_sep_date($year, $month, $day);
    $aDay = $day + $offset;
    return @date($format, mktime(0, 0, 0, $month, $aDay, $year));
}
function format_date_range(&$set, $field)
{
    if (!isset($set[$field]))
    {
        return;
    }
    if (empty($set[$field]))
    {
        unset($set[$field]);
    }
    else
    {
        $date_range = explode('-', $set[$field]);
        unset($set[$field]);
        if (count($date_range) == 2)
        {
            $date_range[0] = trim($date_range[0]) . ' 00:00:00';
            $date_range[1] = trim($date_range[1]) . ' 23:59:59';
            $set[$field] = $date_range;
        }
    }
}
function format_date_range_int(&$set, $field)
{
    if (!isset($set[$field]))
    {
        return;
    }
    if (empty($set[$field]))
    {
        unset($set[$field]);
    }
    else
    {
        $date_range = explode('-', $set[$field]);
        unset($set[$field]);
        if (count($date_range) == 2)
        {
            $start = explode('/', $date_range[0]);
            $end = explode('/', $date_range[0]);
            $date_range[0] = mktime(0, 0, 0, $start[1], $start[2], $start[0]);
            $date_range[1] = mktime(23, 59, 59, $end[1], $end[2], $end[0]);
            $set[$field] = $date_range;
        }
    }
}
function get_year()
{
    return @date("Y");
}
function get_month()
{
    return @date("m");
}
function get_day()
{
    return @date("d");
}
function get_hour()
{
    return @date("H");
}
function get_minute()
{
    return @date("i");
}
function get_second()
{
    return @date("s");
}
function get_week_day()
{
    $d = @getdate();
    return $d["wday"];
}
function is_weekend()
{
    $wday = get_week_day();
    return $wday == 0 || $wday == 6;
}
function get_sep_date(&$y, &$m, &$d, $t = 0)
{
    if ($t === 0)
    {
        $t = time();
    }
    $y = intval(@date("Y", $t));
    $m = intval(@date("m", $t));
    $d = intval(@date("d", $t));
}
function get_last_days(&$date, $nums)
{
    $year = 0;
    $month = 0;
    $day = 0;
    get_sep_date($year, $month, $day);
    for ($i = $nums - 1; $i >= 0; --$i)
    {
        $aDay = $day - $i;
        $t = mktime(0, 0, 0, $month, $aDay, $year);
        $d = array();
        $d['day'] = get_date($t);
        $d['range'] = array(get_datetime($t), get_datetime(mktime(23, 59, 59, $month, $aDay, $year)));
        $date[] = $d;
    }
}
// data related functions
function remove_empty_in_map(&$arr)
{
    $empties = array();
    foreach ($arr as $k => $v)
    {
        if (empty($v) && $v !== '0')
        {
            $empties[] = $k;
        }
    }
    foreach ($empties as $k)
    {
        unset($arr[$k]);
    }
}
function to_json($arr)
{
    $tmp = json_encode($arr);
    return str_replace("\\/", "/", $tmp);
}
function echo_json($arr)
{
    echo(to_json($arr));
}
function copy_from(&$target, $from)
{
    foreach($from as $k => $v)
    {
        $target[$k] = $v;
    }
}
function copy_from_by_ref(&$target, &$from)
{
    foreach($from as $k => &$v)
    {
        $target[$k] = $v;
    }
}
// string related functions
function merge_spaces($str)
{
    while (strpos($str, '  ') !== false)
    {
        $str = str_replace("  ", " ", $str);
    }
    while (strpos($str, "\xc2\xa0") !== false)
    {
        $str = str_replace("\xc2\xa0", "", $str);
    }
    while (strpos($str, "\x5c\x0a") !== false)
    {
        $str = str_replace("\x5c\x0a", "\x0a", $str);
    }
    $str = str_replace("\xe2\x80\x99", "'", $str);
    $str = str_replace("\xe2\x80\x98", "'", $str);
    return $str;
}
function remove_4bytes_utf8($str)
{
    return preg_replace('/[\xF0-\xF7].../', '', $str);
}
function trim_all_spaces($str)
{
    $str = remove_4bytes_utf8($str);
    return preg_replace(array('/\s/', '/\xC2\xA0/'), '', $str);
}
function trim_chn_mark($str)
{
    return trim($str, '"。，？（）；：‘“……"');
}
function trim_array(&$arr, $trims = null)
{
    if ($trims)
    {
        foreach ($arr as &$item)
        {
            $item = trim($item, $trims);
        }
    }
    else
    {
        foreach ($arr as &$item)
        {
            $item = trim($item);
        }
    }
}
function random_string()
{
    return md5(uniqid('', true) . time());
}
function str_to_int_array($str, $delimiter)
{
    if (empty($str))
    {
        return array();
    }
    $arr = explode($delimiter, $str);
    $ret = array();
    foreach($arr as $item)
    {
        if (preg_match("/^\d+$/", $item))
        {
            $ret[] = intval($item);
        }
    }
    return $ret;
}
function rc4($pwd, $data)
{
    $key[] ="";
    $box[] ="";
    $cipher = "";
    $pwd_length = strlen($pwd);
    $data_length = strlen($data);

    for ($i = 0; $i < 256; $i++)
    {
        $key[$i] = ord($pwd[$i % $pwd_length]);
        $box[$i] = $i;
    }

    for ($j = $i = 0; $i < 256; $i++)
    {
        $j = ($j + $box[$i] + $key[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $data_length; $i++)
    {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;

        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;

        $k = $box[(($box[$a] + $box[$j]) % 256)];
        $cipher .= chr(ord($data[$i]) ^ $k);
    }
    return $cipher;
}
function encrypt_data($data, $key)
{
    $req = array(
        'data' => $data,
        't' => time(),
    );
    return array('data' => bin2hex(rc4($key, to_json($req))), 'enc' => true);
}
function decrypt_data($data, $key)
{
    $data = rc4($key, hex2bin($data));
    $data = json_from_string($data);
    if (!$data)
    {
        return false;
    }
    if (!isset($data['data']) || empty($data['data']))
    {
        return false;
    }
    return $data['data'];
}
function remove_unwanted_data(&$data, $wanted, $unwanted = null, $convert = false)
{
    if ($convert)
    {
        $f1 = array();
        if ($wanted)
        {
            foreach ($wanted as $i)
            {
                $f1[$i] = 1;
            }
            $wanted = $f1;
        }
        $f1 = array();
        if ($unwanted)
        {
            foreach ($unwanted as $i)
            {
                $f1[$i] = 1;
            }
            $unwanted = $f1;
        }
    }
    foreach($data as $k => &$v)
    {
        if (($wanted && !isset($wanted[$k])) || ($unwanted && isset($unwanted[$k])))
        {
            unset($data[$k]);
        }
    }
}
function copy_data_from_array(&$dst, &$src, $wanted, $check_empty = false) {
    foreach ($src as $k => &$v) {
        if (isset($wanted[$k]) && (!$check_empty || !empty($v))) {
            $dst[$k] = $v;
        }
    }
}
// os related functions
function is_windows()
{
    global $is_windows;

    if (!isset($is_windows))
    {
        $is_windows = 'WINNT' == PHP_OS || 'WIN32' == PHP_OS;
    }
    return $is_windows;
}
// http related functions
function http_request($url, &$my_data = null, $timeout = 60)
{
    $up_data = '';
    if ($my_data)
    {
        $data = &$my_data;
    }
    else
    {
        $data = array();
    }
    if (isset($data['data']))
    {
        $up_data = http_build_query($data['data']);
    }
    $headers = array();
    if ($up_data)
    {
        $headers[] = "Content-Length: " . strlen($up_data);
    }
    if (!isset($data['language']))
    {
        $language = 'en-US;q=0.6,en;q=0.4';
    }
    else
    {
        $language = $data['agent'];
    }
    $headers[] = 'Accept-Language: ' . $language;
    if (isset($data['headers']))
    {
        $headers = array_merge($headers, $data['headers']);
    }
    if (isset($data['referer']) && $data['referer'])
    {
        $headers[] = 'Referer: ' . $data['referer'];
    }
    if (!isset($data['agent']))
    {
        $agent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.106 Safari/537.36';
    }
    else
    {
        $agent = $data['agent'];
    }
    $headers[] = 'User-Agent: ' . $agent;
    $opts = array (
        'http' => array (
            'method' => isset($data['method']) ? $data['method'] : 'get',
            'timeout' => $timeout,
            'header'=> implode("\r\n", $headers),
        )
    );
    if ($up_data)
    {
        $opts['http']['content'] = $up_data;
    }
    $context = stream_context_create($opts);
    return @file_get_contents($url, false, $context);
}
function http_request_post($url, &$data, $timeout = 60)
{
    if (!isset($data['headers']))
    {
        $data['headers'] = array();
    }
    $data['method'] = 'post';
    $data['headers'][] = 'Content-type: application/x-www-form-urlencoded';
    return http_request($url, $data, $timeout);
}
function http_post($url, $data, $timeout = 180)
{
    if (is_array($data))
    {
        $data = http_build_query($data);
    }
    $opts = array (
        'http' => array (
            'method' => 'POST',
            'timeout' => $timeout,
            'header'=> "Content-type: application/x-www-form-urlencoded\r\n" .
                "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
        ),
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false
        )

    );
    $context = stream_context_create($opts);
    return @file_get_contents($url, false, $context);
}
function http_post_file($url, $file_path, $name, $file_name, $content_type, $headers, $boundary)
{
    copy_from($headers, array(
        "Expect" => "100-continue",
        'Accept-Language' => 'zh-CN,zh;q=0.8,en-US;q=0.6,en;q=0.4',
        //'Content-Type' => 'multipart/form-data; boundary='.$boundary,
    ));
    $body = implode("\r\n", array(
        "",
        $boundary,
        "Content-Disposition: form-data; name=\"{$name}\"; filename=\"{$file_name}\"",
        "Content-Type: $content_type",
        "",
        file_get_contents($file_path),
        $boundary,
    ));
    //$file_size = filesize($file_path);
    //$data_end = "\r\n" . $boundary;
    //$headers['Content-Length'] = $file_size + strlen($data_start) + strlen($data_end);
    $header_string = array();
    $header_string[] = 'Content-Length: '.strlen($body);
    $user_agent = '';
    $cookies = '';
    foreach ($headers as $k => $v)
    {
        if ($k == 'User-Agent')
        {
            $user_agent = $v;
        }
        else if ($k == 'Cookie')
        {
            $cookies = $v;
        }
        else
        {
            $header_string[] = $k . ': ' . $v;
        }
    }
    $process = curl_init();
    curl_setopt($process, CURLOPT_URL, $url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $header_string);
    curl_setopt($process, CURLOPT_HEADER, 0);
    if ($user_agent)
    {
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
    }
    if ($cookies)
    {
        curl_setopt($process, CURLOPT_COOKIE, $cookies);
    }
    curl_setopt($process, CURLOPT_TIMEOUT, 50);
    curl_setopt($process, CURLOPT_POSTFIELDS, array(
        "tmp" => "@$file_path"
    ));
    //curl_setopt($process, CURLOPT_POST, 1);
    $return = curl_exec($process);
    curl_close($process);
    return $return;

    /*
        $header_string = implode("\r\n", $header_string);
        $opts = array (
            'http' => array (
                'method' => 'POST',
                'header'=> $header_string,
                'content' => $body,
            )
        );
        $context = stream_context_create($opts);
        return @file_get_contents($url, false, $context);*/
}
function http_get($url, $data, $option = array(), &$http_response_header = false)
{
    if (is_array($data))
    {
        $data = http_build_query($data);
    }
    if($data){
        if (strpos($url, '?'))
        {
            $url .= '&' . $data;
        }
        else
        {
            $url .= '?' . $data;
        }
    }
    $opts = array (
        'http' => array (
            'method' => 'GET',
            'timeout' => 30
        )
    );
    copy_from($opts['http'], $option);
    $context = stream_context_create($opts);
    return @file_get_contents($url, false, $context);
}
function get_remote_client_address()
{
    if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    else if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    else
    {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    if ($ip == '::1')
    {
        $ip = '127.0.0.1';
    }
    return $ip;
}
function write_key_to_csv_file(&$data, $filePath, $sep = ',')
{
    $ids = "";
    foreach($data as $k => $v)
    {
        $ids .= $k.$sep;
    }
    file_put_contents($filePath, $ids);
}
function write_string_array_to_csv_file(&$data, $filePath, $sep = ',')
{
    file_put_contents(implode($sep, $data), $filePath);
}
function load_csv_to_key_map($filePath, $sep = ',')
{
    if (!file_exists($filePath))
    {
        return array();
    }
    $ids = array();
    $arr = explode($sep, @file_get_contents($filePath));
    for ($i = 0; $i < count($arr); ++$i)
    {
        $arr[$i] = trim($arr[$i]);
        if (empty($arr[$i]))
        {
            continue;
        }
        $ids[$arr[$i]] = true;
    }
    return $ids;
}

function str_add_quot($v)
{
    return "'".$v."'";
}

function array_to_string(&$arr)
{
    return "('" . implode("','", $arr) . "')";
}

function join_array_key(&$arr, $sep = ';')
{
    if (!is_array($arr))
    {
        return '';
    }
    $ret = '';
    $is_sep = '';
    foreach ($arr as $k => $v)
    {
        $ret .= $is_sep . $k;
        if (!$is_sep)
        {
            $is_sep = $sep;
        }
    }
    return $ret;
}
function format_file_size($s) {
    if ($s < 1024) {
        return $s . 'B';
    }
    if ($s < 1048576) {
        return round($s / 1024) . 'KB';
    }
    if ($s < 1073741824) {
        return round($s / 1048576) . 'MB';
    }
    return round($s / 1073741824) . 'GB';
}

function convert_to_chinese_simple($str){
    if(!$str){
        return '';
    }
    global $zh2Hans, $zh2Hant, $zh2TW, $zh2CN, $zh2SG, $zh2HK;
    require_once(PP_INC_ROOT . '/ZhConversion.php');
    $simple_str = strtr($str, $zh2Hans);
    return $simple_str;
}

function convert_to_chinese_traditions($str){
    if(!$str){
        return '';
    }
    global $zh2Hans, $zh2Hant, $zh2TW, $zh2CN, $zh2SG, $zh2HK;
    require_once(PP_INC_ROOT . '/ZhConversion.php');
    $tradition_str = strtr($str, $zh2Hant);
    return $tradition_str;
}
function get_all_other_zh($str){
    if(!$str){
        return '';
    }
    global $zh2Hans, $zh2Hant, $zh2TW, $zh2CN, $zh2SG, $zh2HK;
    require_once(PP_INC_ROOT . '/ZhConversion.php');
    $simple_str = strtr($str, $zh2Hans);
    $tradition_str = strtr($str, $zh2Hant);
    $zh2TW_str = strtr($str, $zh2TW);
    $zh2CN_str = strtr($str, $zh2CN);
    $zh2SG_str = strtr($str, $zh2SG);
    $zh2HK_str = strtr($str, $zh2HK);
    if($str != $simple_str){
        return $simple_str;
    }
    if($str != $tradition_str){
        return $tradition_str;
    }
    if($str != $zh2TW_str){
        return $zh2TW_str;
    }
    if($str != $zh2CN_str){
        return $zh2CN_str;
    }
    if($str != $zh2SG_str){
        return $zh2SG_str;
    }
    if($str != $zh2HK_str){
        return $zh2HK_str;
    }
}

function get_image_content_by_url($src){
    if(!$src){
        return '';
    }
    $user_agent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36";
    $option = array(
        'header'=>"Accept-language: en\r\n" .
            "User-Agent: {$user_agent}\r\n"
    );
    $image_content = http_get($src, '', $option);
    return $image_content;
}

function write_post_log($str, $base_path){
    $log = join_paths($base_path, 'log');
    $str = date('Y-m-d H:i:s') . "  " . $str;
    file_put_contents($log, $str."\n", FILE_APPEND);
    echo $str."\n";
}

function curl_request_with_cookie($url, &$cookie, $cookie_file, $base_path, $external_ip=null, $tor_browser_switch=false, $post=false, $data=array(), $retry_times=3, $tor_port="9050", $headers=array()){
    $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36";
    if($tor_browser_switch){
        $url_search_arr = array('facebook.com');
        $url_replace_arr = array('facebookcorewwwi.onion');
        $url = str_replace($url_search_arr, $url_replace_arr, $url);
        $user_agent = "Mozilla/5.0 (Windows NT 6.1; rv:52.0) Gecko/20100101 Firefox/52.0";
    }else{
        $url = str_replace('facebookcorewwwi.onion', 'facebook.com', $url);
    }
    write_post_log('open url: ' . $url, $base_path);
    if($data){
        write_post_log(to_json($data), $base_path);
    }
    write_post_log('url md5:' . md5($url), $base_path);
    $s_time = time();
    $set_cookie_file = join_paths($base_path, 'set_cookie_file_'.time());
    for($i=0; $i<$retry_times; $i++){
        $ch = curl_init();
        if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        if($external_ip && !$tor_browser_switch){
            curl_setopt($ch, CURLOPT_INTERFACE, $external_ip);
        }
        if($tor_browser_switch){
            curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
            curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
            curl_setopt($ch, CURLOPT_PROXYPORT, $tor_port);
        }
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if($cookie){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch,  CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($data){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_COOKIEJAR,  $set_cookie_file);
        $content = curl_exec($ch);
        if(curl_errno($ch)){
            write_post_log('curl error:' . curl_errno($ch)."  ".curl_error($ch), $base_path);
        }
        //write_post_log('curl_info:' . to_json(curl_getinfo($ch)), $base_path);
        curl_close($ch);
        if($content){
            break;
        }
    }
    write_post_log('crawl the page time:' . (time() - $s_time) . " s", $base_path);

    if(file_exists($set_cookie_file)){
        $new_cookie_items = array();
        $lines = file($set_cookie_file, FILE_IGNORE_NEW_LINES);
        if($lines){
            foreach($lines as $line){
                $tokens = explode("\t", $line);
                if(count($tokens) == 7) {
                    $new_cookie_items[] = array(
                        'domain'=> str_replace('#HttpOnly_', '',$tokens[0]),
                        'name'=> $tokens[5],
                        'value'=> $tokens[6],
                        'path'=> $tokens[2],
                        'expires'=> $tokens[4]
                    );
                }
            }
        }
        if($cookie_file && $new_cookie_items){
            $cookie_all = json_from_file($cookie_file);
            $cookie_arr = array();
            $cookie = "";
            foreach($cookie_all as $item){
                $cookie_arr[$item['name']] = $item;
            }
            foreach($new_cookie_items as $item){
                write_post_log("cookie:".to_json($item), $base_path);
                $cookie_arr[$item['name']] = $item;
            }
            foreach($cookie_arr as $item){
                if(isset($item['name']) && isset($item['value'])){
                    $cookie .= $item['name'].'='.$item['value'].'; ';
                }
            }
            $cookie_str = to_json(array_values($cookie_arr));
            file_put_contents($cookie_file, $cookie_str);
        }
    }
    @file_put_contents(join_paths($base_path, md5($url).'_raw.html'), $content);
    return $content;
}

function curl_request_by_proxy($url, &$cookie, $cookie_file, $base_path, $proxy, $post=false, $data=array(), $retry_times=2){
    $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36";
    $url = str_replace('facebookcorewwwi.onion', 'facebook.com', $url);
    write_post_log('open url: ' . $url . '   proxy:'.$proxy, $base_path);
    if($data){
        write_post_log(to_json($data), $base_path);
    }
    write_post_log('url md5:' . md5($url), $base_path);
    $s_time = time();
    $set_cookie_file = join_paths($base_path, 'set_cookie_file');
    for($i=0; $i<$retry_times; $i++){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($proxy){
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if($cookie){
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch,  CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 900);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if($data){
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($ch, CURLOPT_COOKIEJAR,  $set_cookie_file);
        $content = curl_exec($ch);
        if(curl_errno($ch)){
            write_post_log('curl error:' . curl_errno($ch)."  ".curl_error($ch), $base_path);
        }
        //write_post_log('curl_info:' . to_json(curl_getinfo($ch)), $base_path);
        curl_close($ch);
        if($content){
            break;
        }
    }
    write_post_log('crawl the page time:' . (time() - $s_time) . " s", $base_path);

    if(file_exists($set_cookie_file)){
        $new_cookie_items = array();
        $lines = file($set_cookie_file, FILE_IGNORE_NEW_LINES);
        if($lines){
            foreach($lines as $line){
                $tokens = explode("\t", $line);
                if(count($tokens) == 7) {
                    $new_cookie_items[] = array(
                        'domain'=> str_replace('#HttpOnly_', '',$tokens[0]),
                        'name'=> $tokens[5],
                        'value'=> $tokens[6],
                        'path'=> $tokens[2],
                        'expires'=> $tokens[4]
                    );
                }
            }
        }
        if($cookie_file && $new_cookie_items){
            $cookie_all = json_from_file($cookie_file);
            $cookie_arr = array();
            $cookie = "";
            foreach($cookie_all as $item){
                $cookie_arr[$item['name']] = $item;
            }
            foreach($new_cookie_items as $item){
                write_post_log("cookie:".to_json($item), $base_path);
                $cookie_arr[$item['name']] = $item;
            }
            foreach($cookie_arr as $item){
                if(isset($item['name']) && isset($item['value'])){
                    $cookie .= $item['name'].'='.$item['value'].'; ';
                }
            }
            $cookie_str = to_json(array_values($cookie_arr));
            file_put_contents($cookie_file, $cookie_str);
        }
    }
    @file_put_contents(join_paths($base_path, md5($url).'_raw.html'), $content);
    return $content;
}

function curl_get($url, $external_ip, $base_path, $retry_times=1, $tor_browser_switch=false){
    if($tor_browser_switch){
        $url_search_arr = array('facebook.com');
        $url_replace_arr = array('facebookcorewwwi.onion');
        $url = str_replace($url_search_arr, $url_replace_arr, $url);
    }
    if($base_path){
        write_post_log('open url: ' . urldecode($url), $base_path);
    }
    $retry_times = intval($retry_times);
    if($retry_times < 1){
        $retry_times = 1;
    }
    $s_time = time();
    for($i=0; $i<$retry_times; $i++){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($external_ip){
            //curl_setopt($ch, CURLOPT_INTERFACE, $external_ip);
        }
        if($tor_browser_switch){
            curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
            curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
            curl_setopt($ch, CURLOPT_PROXYPORT, "9050");
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $content = curl_exec($ch);
        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            echo 'Took ', $info['total_time'], ' seconds to send a request to ', $info['url'], "\n";
        }
        curl_close($ch);
        if($content){
            break;
        }
    }
    if($base_path){
        write_post_log('crawl the page time:' . (time() - $s_time) . " s", $base_path);
        @file_put_contents(join_paths($base_path, md5($url).'_raw.html'), $content);
    }
    return $content;
}

function get_multi_image_by_urls($url_arr, $base_dir, $external_ip){
    if(!is_array($url_arr)){
        return false;
    }
    $data = array();
    $handle = array();
    $mh = curl_multi_init(); // multi curl handler
    $i = 0;
    echo $external_ip." ------\n";
    foreach($url_arr as $name=>$url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($external_ip){
            //curl_setopt($ch, CURLOPT_INTERFACE, $external_ip);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return don't print
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_multi_add_handle($mh, $ch); // 把 curl resource
        $handle[$name] = $ch;
    }
    do{
        $mrc = curl_multi_exec($mh, $active);
    }while($mrc == CURLM_CALL_MULTI_PERFORM);
    echo "multi 001\n";
    while($active && $mrc == CURLM_OK){
        while(curl_multi_exec($mh, $active) === CURLM_CALL_MULTI_PERFORM);
        if(curl_multi_select($mh) != -1){
            do{
                $mrc = curl_multi_exec($mh, $active);
            }while($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    echo "multi 002\n";
    foreach($handle as $name => $ch){
        $image_content  = curl_multi_getcontent($ch);
        if($image_content){
            $image_path = join_paths($base_dir, $name);
            echo $image_path."\n";
            file_put_contents($image_path, $image_content);
            $mimetype = get_file_type($image_path);
            if($mimetype == 'unknown'){
                @unlink($image_path);
            }
        }
    }
    echo "multi 003\n";
    /* 移除 handle*/
    foreach($handle as $ch){
        curl_multi_remove_handle($mh, $ch);
    }
    curl_multi_close($mh);
}

function get_file_type($file){
    if(!empty($file)){
        $filehead = fopen($file,'r');
        $bin = fread($filehead, 2);
        fclose($filehead);
        $data = unpack('C2chars', $bin);
        $type_code = intval($data['chars1'].$data['chars2']);
        switch ($type_code) {
            case 7790:
                $fileType = 'exe';break;
            case 7784:
                $fileType = 'midi';break;
            case 8075:
                $fileType = 'zip';break;
            case 8297:
                $fileType = 'rar';break;
            case 255216:
                $fileType = 'jpg';break;
            case 7173:
                $fileType = 'gif';break;
            case 6677:
                $fileType = 'bmp';break;
            case 13780:
                $fileType = 'png';break;
            default:
                $fileType = 'unknown';
        }
        return $fileType;
    }else{
        return false;
    }
}

