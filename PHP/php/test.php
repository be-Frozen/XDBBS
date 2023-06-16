<?php
function replace_unicode_escape_sequence($match) {

    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');

}
$s='%uD83D%uDE00\u6d4b\u8bd5';

$s=str_replace("%u","\\u",$s);
//echo $s;
//echo preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $s);
echo preg_replace_callback('/\\\\b(?:(?!([\\\\ud83c\\\\udf00-\\\\udfff]|\\\\ud83d[\\\\udc00-\\\\ude4f\\\\ude80-\\\\udeff]|[\\\\u2600-\\\\u2B55])).)*?\\\\b(?=\\\\s|$)/','replace_unicode_escape_sequence',$s);
?>

