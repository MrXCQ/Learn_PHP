<?php

// 单、双引号定义字符串
// 双引号定义的字符串会对 PHP 变量及特殊的字符进行解析，单引号定义的字符串则不会解析PHP 变量和特殊字符

// heredoc
// Heredoc 结构就像是没有使用双引号的 双引号字符串
// 定义语法 
// <<<标识符名称 // 此处必须单独成行 不能缩进，结束有且仅可以一个分号

// $name = 'string';

// echo <<< 'EOT' 
// 我在学习PHP
// EOT ;

function xcq_echo($content){
    echo "<br>" . $content;
}

$string1 = "这是一个字符串一个啊";
$string2 = "a1234H56";


// 字符串常用操作函数
// 获取字符串长度函数
$length = strlen($string1) ;

$length2 = strlen($string2) ;

$length3 = mb_strlen($string1,"UTF8");

// 中文 UTF-8 长度是3 GBK 是2个字节，默认是UTF-8
xcq_echo($length);
// 英文数字为1 
xcq_echo($length2);

xcq_echo($length3);


// 查找字符位置函数
// 查找字符串首次出现的位置

$position = strpos($string1,"一个"); //从0 开始查找

xcq_echo("一个字符串所在的位置" . $position);

$position2 = strpos($string1,"一个",7); // 从下标 7 开始查找

xcq_echo("一个字符串所在的位置" . $position2);

$p1 = strpos($string2,'h') ; //区分大小写
$p2 = stripos($string2,'h');// 不区分大小写

xcq_echo($p1);
xcq_echo($p2);



//字符串替换函数
// str_replace 区分大小写
// str_ireplace 不区分大小写
$newStr = str_replace("一个","德",$string1,$count);

xcq_echo($newStr);

xcq_echo("替换次数".$count);

$newStr1 = str_ireplace("A","替换后的字符串",$string2,$count);

xcq_echo($newStr1);

xcq_echo("不区分大小写的字符串次数".$count);



// 字符串截取操作





?>