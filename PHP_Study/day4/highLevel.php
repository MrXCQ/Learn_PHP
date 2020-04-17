<?php 
include 'welcome.php';

// PHP 多维数据
// function xcq_echo($content){
//     echo "<br>" . $content ; 
// }

$cars = array(
    array('a1',100,10),
    array('a2',200,20),
    array('a3',300,30),
    array('a4',400,50)
);


print_r($cars);
foreach ($cars as $a => $value) {
    xcq_echo($value[1]);
}

// PHP Date函数
// PHP date（） 函数可以把时间戳格式化为可读性更好的日期和时间

xcq_echo( date("Y/m/d"));

xcq_echo( date("Y.m.d"));

xcq_echo( date("Y-m-d"));

// 详见date() 完整用法 ：https://www.runoob.com/php/php-ref-date.html



// PHP include 和 require 语句
// 在PHP中您可以在服务器执行 PHP 文件之前在改文件插入一个文件内容
// include 和 require 语句用于在执行流中插入卸载其他文件中游泳的代码

// include 和 require 除了处理错误的方式不同之外，其他方面都是相同的。

// require 生成一个致命错误 E_COMPILE_ERROR
// include 生成一个警告 E_WARNing 在错误发生后脚本会继续执行

// 因此,如果您希望继续执行，并向用户输出结果，即时包含文件已丢失，那么请使用 include。否则，在框架、cms 或者复杂的PHP
// 应用程序编程中 请始终使用require 向执行流引用关键文件，这有助于提供应用程序的安全性和完整性。在某个关键文件以外丢失的情况下、

// 包含文件省去了大量的工作，意味着您可以为所用网页创建标准页头、页脚、或者菜单文件。然后 在页头需要更新的时候，您只需要更新这个页头包含文件即可


// PHP 文件处理
// fopen() 函数用于在PHP 中打开文件
// 此函数的第一个参数含有打开文件的名称，第二个参数规定了使用暗中模式来打开文件,如果无法打开文件返回失败 false 

$file = fopen('PrivacyText.plist','r') or exit('无法正确打开文件');

xcq_echo($file) ;

// 模式	描述
// r	只读。在文件的开头开始。
// r+	读/写。在文件的开头开始。
// w	只写。打开并清空文件的内容；如果文件不存在，则创建新文件。
// w+	读/写。打开并清空文件的内容；如果文件不存在，则创建新文件。
// a	追加。打开并向文件末尾进行写操作，如果文件不存在，则创建新文件。
// a+	读/追加。通过向文件末尾写内容，来保持文件内容。
// x	只写。创建新文件。如果文件已存在，则返回 FALSE 和一个错误。
// x+	读/写。创建新文件。如果文件已存在，则返回 FALSE 和一个错误。


// 关闭文件
// fclose() 函数用于关闭打开的文件
// fclose($file);


// 检测文件末尾 EOF 
// feof() 函数检测是否已达到文件末尾 EOF
// 在循坏遍历未知长度的数据时，feof() 函数很有作用 在 w、a和x 模式下 您无法读取打开的文件

if (feof($file)) {
     xcq_echo('已到文件末尾');
}

// 逐行读取文件 
// fgets() 函数用于从文件中逐行读取文件
// 注释： 在调用该函数之后，文件指针会移动到下一行

// while (!feof($file)) {   
//     xcq_echo(fgets($file));
// }

// 逐字符读取文件

// fgetc() 函数用于从文件中逐字符地读取文件
// 注释：在调用该函数之后，文件指针会移动到下一个字符

while (!feof($file)) {   
    xcq_echo(fgetc($file));
}

// 更多功能请查看： https://www.runoob.com/php/php-ref-filesystem.html



?>