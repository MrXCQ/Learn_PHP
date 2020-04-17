<?php

// 自定义函数

function t(){
    return 1;
}

$res = t();

// 返回值是PHP 的任何数据类型，可以用一个变量来接收
var_dump($res);

// 动态调用
$fun = 't';
var_dump($fun()); // 相当于t()， $fun = 't'

// 变量中的可变变量
$h = "hello" ;
$hello = "hello world";

echo $h.'<br>' ; 
echo $$h . '<br>'; // $($h) 等价于 $(hello) = $hello  = hello world ;

// 函数内的局部变量
// 函数内的变量有两种，一下两种变量仅在函数内有效

// 1、局部变量（local） 函数内部声明的变量
// 2、局部静态变量 static 函数内部使用关键字 static 声明的变量。静态变量的值不能是表达式的值，只能是标量值。

// 变量知识补充：
// 全局变量 global 在全局声明的变量 全局有效
// 变量的销毁和检测


function test() {
    $a = 100 ;
    static $num = 100;

    $a++ ;
    $num++;

    echo '<br>$a=',$a,'  $num=',$num,'<br>';

}

test();
test();
test();

// 普通局部变量，每次函数调用时都会进行初始化
// static 静态局部变量，在函数首次调用时，会进行初始化，他可以用做静态变量


$a1 = 100 ;
$b1; //只声明不赋值 默认是NULL

var_dump(isset($a1));
echo "<br>";
var_dump(isset($b1));
echo "<br>";
unset($a1); // 销毁$a1 变量
var_dump(isset($a1));
echo "<br>";



?>