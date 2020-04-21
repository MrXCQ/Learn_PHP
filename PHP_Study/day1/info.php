<!DOCTYPE html>
<html>

<head>
    <title>PHP的基本语法</title>
</head>

<body>

    <h1>My first PHP page</h1>

    <?php

echo "Hello World!\n";


// PHP 变量规则
// 变量以 $ 符号开始 ，后面跟着变量的名称
// 变量必须以字母或者下划线字符开始
// 变量名智能包含字符数字字符以及下划线
// 变量名不能包含空格
// 变量名区分大小写 $x 和 $Y 是两个不同的变量


$x = 5 ;
$y = 6 ;
$z = $x + $y;
echo "x+y = " . $z;


// PHP 的变量作用域
// 变量的作用域是脚本中变量可以被引用使用的部分
// PHP 有四种不同的变量作用域：

// local
// global
// static
// parameter

// 局部和全局作用域
// 所有函数外部定义的变量，拥有全局作用域。除了函数外 全局变量可以被脚本中的任何部分访问，要在一个函数中访问一个
// 全局变量需要使用global 关键字

// 在PHP 函数内部申明的变量是局部变量，仅能在函数内部访问

function myTest()
{
    $y = 10 ;
    echo "<p> 测试函数内的变量：<p>";
    echo "变量 x 为: $x";
    echo "<br>";
    echo "变量 y 为: $y";
}

myTest();

echo "<p> 测试函数内的变量：<p>";
echo "变量 x 为: $x";
echo "<br>";
echo "变量 y 为: $y";


$a = 10 ;
$b = 20 ;
// global 关键字
function myTest2(){
  global $a,$b ;
  $b = $a + $b ;
}

myTest2();

echo "<br>";
echo "global关键字 = " . $b ;

// PHP 将所有的全局变量存储在一个名为 $GLOBALS[index] 数组当中.index保存变量的名称。此数组可以在函数内部访问

function myTest3(){

$GLOBALS['y'] =  $GLOBALS['x'] + $GLOBALS['y'] ;

}

myTest3();

echo "<br>";
echo "全局变量数组关键字 = " . $y ;


// Static 作用域 此处和别的语言静态变量有点区别
// 当一个函数完成时，他的所有变量都会被删除，如果你奚望某个局部变量不要被删除。要做到这一点 请使用static 关键字

function myTest4(){
    static $b = 0 ;
    echo "<br>". $b;
    $b++;
    
    echo PHP_EOL;
}

myTest4();
myTest4();
myTest4();
myTest4();
myTest4();

// PHP EOF 的使用

// PHP EOF 是一种在命令行shell 和程序语言里定义一个字符串的方法
// 使用概述：
// 1、必须要后接分号，否则编译不通过
// 2、EOF 可以用任意其他的字符代替，需要保证结束标识与开始标识一致
// 3、结束标识必须要顶格肚子占一行（即行首开始，前后不能有其他的内容）
// 4、开始标识可以不带引号或者带单引号 不带引号与带引号的效果一致，解释内嵌的变量和转义符号，带单引号则不解释内嵌的变量和转义符号
// 5、当内容需要内嵌引号时不需要加转义符，本身对单引号转义，


echo <<<EOF
        <h1>我的第一个标题</h1>
EOF;

$name="runoob";
$a=<<<EOF
"abc"$name
"123"
EOF;

echo "<br> EOF的用法" . $a ; 


// PHP 的数据类型
// String 、Integer、Float、Boolean、Array、Object、NULL
$x = 100 ;
echo "<br>"; 
var_dump($x) ; 

echo "<br>"; 
$x = -345; // 负数 
var_dump($x);
echo "<br>"; 
$x = 0x8C; // 十六进制数
var_dump($x);
echo "<br>";
$x = 047; // 八进制数
var_dump($x);


// PHP 对象
// 对象数据类型也可以用于存储数据
// 在PHP 中毒性必须声明。 必须要使用class 关键字声明类的对象，类是可以包含属性和方法的结构
// 然后在我们类中定义数据类型 然后在实例化的类中使用数据类型

function xcq_echo($content){
    echo "<br>" . $content ; 
}


class User{

    var $name;

    function __construct($name="IMpBear"){
        $this->name = $name;
    }

    function what_color(){
        return $this->name;
    }
}

function prints_vars($obj){
    foreach (get_object_vars($obj) as $prop => $val) {
        echo "\t$prop = $val\n";
    }
}

//实例一个对象
$ming = new User("小明");

// 显示对象属性
echo "<br>\therbie: Properties\n";
prints_vars($ming);


// PHP 的类型比较
// 虽然PHP 是弱类型语言，也要明白变量类型以及他们的意义，因为我们经常需要对PHP 变量进行比较，包括松散和严格的比较

// 松散比较：  使用两个等号 == 比较 ，只比较值，不比较类型。
// 严格比较：  使用三个等号 === 比较，除了比较值，也比较类型。


if (42 == "42") {
    xcq_echo("1 值相等");
}
 
if (42 === "42") {
    xcq_echo("类型相等");
}else{
    xcq_echo("类型不相等");
}


// PHP 常量 
// 常量值被定义之后 在脚本的任何位置都不能被改变
// 设置常量 使用define() 函数 语法如下： 
// bool define ( string $name , mixed $value [, bool $case_insensitive = false ] )
// name 必选参数 常量名称，标识符
// value 必选参数 常量的值
// case_insensitive 可选参数 如果设置为 true 该常量则大小写不敏感，默认是大小写敏感的
// 常量是全局的  不需要添加 $ 符号修饰

define("LOL","欢迎来到德莱联盟！！！");

xcq_echo(LOL);

// echo lol; 会报错

//不用区分大小写
define("NAME","欢迎来到德莱联盟呀！！！",true);

xcq_echo(NAME);

xcq_echo(name);



// PHP 字符串

// 1、获取字符串长度

$text = "这是一个PHP的字符串";

xcq_echo("字符串的长度 = " .strlen($text));

// 2、匹配字符串
// strpos() 函数用于在字符串内查找一个字符或者一段指定的文本

//中文一个占3个字符
xcq_echo(strpos($text,"一"));

$words = "Hello world! whats up!";
xcq_echo(strpos($words,"ll"));

// PHP 数组排序
// 以下是PHP 的数组排序函数：
// sort()  对数组进行升序排列
// rsort() 对数组进行降序排列
// asort() 根据关联数组的值，对数组进行升序排列
// ksort() 根据关联数组的键，对数组进行升序排列
// arsort()根据关联数组的值，对数组进行降序排列
// krsort()根据关联数组的键，对数组进行降序排列



$car = array("bmw","byd","benz","toyota","tesla","aAAAAA");

// 升序排列
sort($car) ;
echo("<br>");
print_r($car);

$numArr = array(23,34,436,324,2,3,5,757);
sort($numArr);
echo("<br>");
print_r($numArr);


// 降序排列
rsort($numArr) ;
echo("<br>");
print_r($numArr);

// 根据数组的键值进行升序排列
$userArr = array("a"=>'30',"b"=>'234',"c"=>"234234","d"=>"283");

asort($userArr);
//ksort($userArr);

echo("<br>");
print_r($userArr);

// 根据数组的键值进行降序排列
arsort($userArr);
// krsort($userArr);

echo("<br>");
print_r($userArr);






















?>




</body>

</html>