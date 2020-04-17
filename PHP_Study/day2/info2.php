<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP基本语法2</title>
</head>
<body>

<?php

// PHP的超级变量
// PHP 中预定义了几个超级全局变量 superglobals，意味着在一个脚本的全部作用域中都可用，不需要特别说明在函数和类中使用
// PHP 超级变量列表
// $GLOBALS 
// $_SERVER
// $_REQUEST
// $_POST
// $_GET
// $_FILES
// $_ENV
// $_COOKIE
// $_SESSION

phpinfo();

function xcq_echo($content){
    echo "<br>" . $content ; 
}

// $GLOBALS 是一个超级全局变量组，在一全部的作用域中都可以访问
// $GLOBALS 是一个包含了全部变量的全局组合数组，变量的名字就是数组的键

$x = 100 ;
$y = 200 ;

function globals(){

    $GLOBALS['z'] = $GLOBALS['x'] + $GLOBALS['y'];
}

globals();

xcq_echo($z);

// PHP $_SERVER
// $_SERVER 是一个包含了 头信息、路径以及脚本位置 等等的数组，这个数组中的项目由web服务器创建，不能保证每个服务器都提供全部的项目

// 输出当前的路径
xcq_echo($_SERVER['PHP_SELF']);
// 服务器地址
xcq_echo($_SERVER['SERVER_NAME']);

xcq_echo($_SERVER['HTTP_HOST']);

xcq_echo($_SERVER["HTTP_REFERER"]);

xcq_echo($_SERVER["HTTP_USER_AGENT"]);

xcq_echo($_SERVER['SCRIPT_NAME']);


// $_SERVER['PHP_SELF']	当前执行脚本的文件名，与 document root 有关。例如，在地址为 http://example.com/test.php/foo.bar 的脚本中使用 $_SERVER['PHP_SELF'] 将得到 /test.php/foo.bar。__FILE__ 常量包含当前(例如包含)文件的完整路径和文件名。 从 PHP 4.3.0 版本开始，如果 PHP 以命令行模式运行，这个变量将包含脚本名。之前的版本该变量不可用。
// $_SERVER['GATEWAY_INTERFACE']	服务器使用的 CGI 规范的版本；例如，"CGI/1.1"。
// $_SERVER['SERVER_ADDR']	当前运行脚本所在的服务器的 IP 地址。
// $_SERVER['SERVER_NAME']	当前运行脚本所在的服务器的主机名。如果脚本运行于虚拟主机中，该名称是由那个虚拟主机所设置的值决定。(如: www.runoob.com)
// $_SERVER['SERVER_SOFTWARE']	服务器标识字符串，在响应请求时的头信息中给出。 (如：Apache/2.2.24)
// $_SERVER['SERVER_PROTOCOL']	请求页面时通信协议的名称和版本。例如，"HTTP/1.0"。
// $_SERVER['REQUEST_METHOD']	访问页面使用的请求方法；例如，"GET", "HEAD"，"POST"，"PUT"。
// $_SERVER['REQUEST_TIME']	请求开始时的时间戳。从 PHP 5.1.0 起可用。 (如：1377687496)
// $_SERVER['QUERY_STRING']	query string（查询字符串），如果有的话，通过它进行页面访问。
// $_SERVER['HTTP_ACCEPT']	当前请求头中 Accept: 项的内容，如果存在的话。
// $_SERVER['HTTP_ACCEPT_CHARSET']	当前请求头中 Accept-Charset: 项的内容，如果存在的话。例如："iso-8859-1,*,utf-8"。
// $_SERVER['HTTP_HOST']	当前请求头中 Host: 项的内容，如果存在的话。
// $_SERVER['HTTP_REFERER']	引导用户代理到当前页的前一页的地址（如果存在）。由 user agent 设置决定。并不是所有的用户代理都会设置该项，有的还提供了修改 HTTP_REFERER 的功能。简言之，该值并不可信。)
// $_SERVER['HTTPS']	如果脚本是通过 HTTPS 协议被访问，则被设为一个非空的值。
// $_SERVER['REMOTE_ADDR']	浏览当前页面的用户的 IP 地址。
// $_SERVER['REMOTE_HOST']	浏览当前页面的用户的主机名。DNS 反向解析不依赖于用户的 REMOTE_ADDR。
// $_SERVER['REMOTE_PORT']	用户机器上连接到 Web 服务器所使用的端口号。
// $_SERVER['SCRIPT_FILENAME']	当前执行脚本的绝对路径。
// $_SERVER['SERVER_ADMIN']	该值指明了 Apache 服务器配置文件中的 SERVER_ADMIN 参数。如果脚本运行在一个虚拟主机上，则该值是那个虚拟主机的值。(如：someone@runoob.com)
// $_SERVER['SERVER_PORT']	Web 服务器使用的端口。默认值为 "80"。如果使用 SSL 安全连接，则这个值为用户设置的 HTTP 端口。
// $_SERVER['SERVER_SIGNATURE']	包含了服务器版本和虚拟主机名的字符串。
// $_SERVER['PATH_TRANSLATED']	当前脚本所在文件系统（非文档根目录）的基本路径。这是在服务器进行虚拟到真实路径的映像后的结果。
// $_SERVER['SCRIPT_NAME']	包含当前脚本的路径。这在页面需要指向自己时非常有用。__FILE__ 常量包含当前脚本(例如包含文件)的完整路径和文件名。
// $_SERVER['SCRIPT_URI']	URI 用来指定要访问的页面。例如 "/index.html"。



// PHP $_REQUEST

// 用于收集HTML表单提交的数据
$name = $_REQUEST['fname'];

xcq_echo($name);



// PHP $_POST
// 被广泛应用于收集表单数据。在 HTML form 标签指定属性： method=post

$name2 = $_POST['fname'];
xcq_echo($name2);


// PHP $_GET
// 同样被用于收集表单数据，在form 表单中指定 method = "get"
// 也可以收集URL中发送的数据

xcq_echo("学习".$_GET['subject']." # ". $_GET['web']);

// while 循环
// do while 循环
// for 循环
// 与其他语言类似 大同小异
// for each 循环

$arrEach = array("one","two","three");

foreach ($arrEach as $value){
    xcq_echo($value);
}

// PHP 函数
// 函数的准则 
// 函数的名称应该提示出他的功能
// 函数的名称以字母或者下划线开头 不能够以数字开头

function test(){
    xcq_echo("函数体");
}

// 带有参数的函数
function writeName($fname){
    xcq_echo($fname . "sssss");
}

writeName("XCQ");

// 带有参数 和 返回值 的函数
function add($x,$y){
    return $x + $y ; 
}

xcq_echo("100 + 200 = " . add(100,200));


// PHP 的魔术常量
// PHP 向他运行的任何脚本提供了大量的预定义常量
// 不过很多的常量都是有不同的扩展库定义的，只有加载了这些扩展库时才会出现或者动态加载之后或者在编译时已经包括进去

// __LINE__ 文件中的当前行号
echo "这是第  " . __LINE__ ." 行";

// __FILE__
// 文件的完整路径和文件名 如果被包含文件中 则返回被包含的文件名
// 自 PHP 4.0.2起，包含一个绝对路径，如果是符号链接则是解析后的绝对路径。而在此之前的版本有时会包含一个相对路径。


xcq_echo("当前文件位于 ===" . __FILE__);


// __DIR__ 
// 文件的完整路径和文件名。如果用在被包含文件中，则返回文件所在的目录

xcq_echo("该文件位于 ====" . __DIR__);

// __FUNCTION__
xcq_echo("当前所处位置的函数名称 ===> ". __FUNCTION__);

// __CLASS__ 类的名称

// __TRAIT__
// PHP 自5.4.0后实现的一个代码复用的一个方法，成为traits
// Trait 包括七被声明的作用区域 
class Base{
    public function sayHello()
    {
         xcq_echo("你好，精神小伙！");
    }
}


trait sayMM
{
    public function sayHello()
    {
        parent::sayHello();
        xcq_echo("您好，精神小伙二号");
    }
}

class MySayHello extends Base{
    use sayMM;
}


$test = new MySayHello();

$test->sayHello();


// __METHOD__ 
// 类的方法名 返回该方法被定义是的名字 区分大小写

function test1(){
    xcq_echo("函数的名称为:  " . __METHOD__);
}

test1();


// PHP 的命名空间 namespace

// PHP 命名空间 可以解决以下两类问题

// 1. 用户编写的代码与PHP 内部的类、函数、常量、或者第三方、函数、常量、之间的名字冲突
// 2. 为很长的标识符名称，通常是为了缓解第一类问题而定义创建的一个别名的名称，提高代码的可读性

// 定义命名空间
// 默认的情况下 所有的常量 类 函数名都放在全局空间下，和PHP支持的命名空间一样
// 命名空间通过关键字 namespace 来进行声明 如果一个文件中包含命名空间，他必须在其他所有代码之前声明命名空间

// 必须要要在第一行
// namespace MyProject1 {
//     const connect_ok = 1;
//     function connect(){}
// };

// namespace anotherProject {
//     const connect_ok = 1;
//     function connect(){}
    
// };

// PHP 面向对象

// 在面向对象的程序设计 OOP
// 对象的三个特性
// 对象的行为、形态、表示（相同的行为状态下有什么不同 比如跑的更快，长的更高）

// 面向对象的内容
// 类： 定义了一件事物抽象的特点，包含了数据的形式以及对数据的操作
// 对象： 是类的实例。
// 成员变量： 定义在类内部的变量。变量的值对外是不可见的但是可以通过成员函数范文，在类被实例化之后变量即为对象的属性
// 成员函数 ： 定义在类的内部，可以访问对象的数据
// 继承 ： 继承是子类自动共享父类数据结构和方法的机制。是类之间的一种关系。在定义一个类的时候，可以在已经存在的类的基础上进行
// 父类： 一个类被其他类继承，将该类成为父类、基类或者超类
// 子类： 一个类继承其他类为子类也可成为派生类
// 多态： 多态性是指相同的函数或方法可作用于多种类型的对象上并获得不同的结果。不同的对象，收到同一类消息产生不同的结果，这种现象成为多态性
// 重载： 简单说就是函数或者方法有同样的名称，但是参数列表不相同的情形，这样同名不同参数的或者方法之间 互相称之为重载函数或者方法
// 抽象性: 抽象性指将具有一致的数据结构（属性）和行为（操作）的对象抽象成类，一个类就是这样的一种抽象，他反映了与应用有关的重要性质，而忽略其他一些无关内容。任何类的划分都是主观的，单必须与具体的应用有关
// 封装： 封装是指将现实世界中存在的某个客观的属性与行为绑定在一起，并放置在一个逻辑单元内。
// 构造函数 ： 主要用来在创建对象时初始化对象，即为对象成员变量赋初始值，与new 运算符一起使用
// 析构函数： 析构函数与构造函数相反，当对象结束其生命周期时（如对象所在的函数已调用完毕），系统自动执行析构函数。析构函数往往用来做”清理善后的工作“

class PHP_Animal{

    //成员变量
    var $var;
    var $var2 = "Funny";
    

    // 成员函数
    function myFunc($a,$b){
        return $a+$b;
    }
    
    function setVar($a){
        $this->var = $a;
    }

    function getVar(){

        echo $this->var . PHP_EOL;
    }
}

$dog = new php_Animal();

xcq_echo($dog->var2);
xcq_echo($dog->myFunc(2,3));
$dog->setVar("CNM");

xcq_echo($dog->getVar());

//PHP 构造函数
// 构造函数是一种特殊的方法。主要用来创建对象时初始化对象，即为对象成员变量赋初始值，在创建对象的语句中与New
// 运算符一起使用
// void __construct([mixed $args [,$....]])


class Site{
    var $title,$url;

    function __construct($a,$b){
        $this->url   = $a;
        $this->title = $b;
    }

    function setUrl($a){
        $this->url = $a;
    }

    function getUrl(){
        xcq_echo($this->url);
    }

    function setTitle($a){
        $this->title = $a;
    }

    function getTitle(){
        xcq_echo($this->title);
    }

}


// 上面的例子当中我们就可以通过构造方法来初始化 $url和$title 变量

$baidu = new Site("www.baidu.com","百度");
$google = new Site("www.google.com","google");

//调用成员函数 获取标题和url

xcq_echo($baidu->getTitle());

xcq_echo($google->getUrl());


// 析构函数
// 与构造函数想法，当对象结束其生命周期（函数调用完毕）系统自动执行析构函数。
// PHP5 引入析构函数的概念， 这类似于其他面西乡对象的语言，语法格式如下：
// void __destruct (void)

class demoClass{
    function __construct(){
        xcq_echo("构造函数");
    }

    function __destruct(){
        xcq_echo("析构函数销毁对象");
    }
    
 }

$demoClass = new demoClass();

// 继承
// PHP 使用关键字 extends 来继承一个类，PHP 不支持多继承 格式如下：

// class Child extends Parent{
//     // 代码部分
// }

class sub_Site extends Site{

    var $country;

    function setCountry($a){
        $this->country = $a;
    }

    function getCountry(){
        xcq_echo($this->country);
    }

    function __construct($a,$b,$c){
        $this->url   = $a;
        $this->title = $b;
        $this->country = $c;
    }   
}


$subSite = new sub_Site("www.baidu.com","百度","CN");

xcq_echo($subSite->getCountry());
xcq_echo($subSite->getUrl());


// 访问控制
// PHP 对属性或方法的访问控制，是可以通过在前面添加关键字 public（公有）、protected（受保护）或private（私有）来实现的
// public：公有的类成员可以在任何地方被访问
// protected（受保护） 受保护 的类成员则可以被其自身以及其子类和父类访问
// private（私有）私有的类成员则只能被其定义所在的类访问

class myClass{
    
    public $public = "public";
    protected $protected = "protected";
    private $private = "private";

    function printHello(){
        xcq_echo($this->public);
        xcq_echo($this->protected);
        xcq_echo($this->private);
    }
}


$obj = new myClass();

xcq_echo($obj->public);
// xcq_echo($obj->protected); 这一行会报错
// xcq_echo($obj->private); 这一行会报错
$obj->printHello();

// 接口
// 使用接口 interface 可以指定某个类必须实现哪些方法。单不需要定义这些方法的具体内容
// 接口是通过interface 关键字来定义的，就像定义一个标准的类一样，但其中的定义所有的方法都是空的
// 接口中定义的所有的方法都是公有 这是接口的特性
// 要实现一个接口，使用implements 操作符，类中必须实现接口中定义的所有方法，否则会报一个致命错误。
// 类可以实现多个接口，用逗号来分隔多个接口名称

interface iTemplate
{
    public function setVariable($name,$var);
    public function getHtml($template);
}

// 实现接口
class Template implements ITemplate
{
    private $vars = array();
    public function setVariable($name,$var){
        $this->vars[$name]  = $var;
    }

    public function getHtml($template){
        foreach($this->vars as $name =>$value){
            $template = str_replace("{" . $name . "}",$value,$template);
        }

        return $template ;
    }
}


// 常量
// 可以把在类中时钟保持不变的值定义为常量。在定义和使用常量的时候不需要使用$符号
// 常量的值必须是一个定值，不能是变量，类属性,，数学运算的结果或函数调用。
// PHP 5.3 之后可以用一个变量来动态调用类。但改变量的值不能为关键字 如 self、parent、static
class MyClass2{
    const constant = "常量值";
    function showConstant(){
        xcq_echo(self::constant.PHP_EOL);
    }
}

xcq_echo(MyClass2::constant.PHP_EOL);

// 抽象类
// 任何一个类 如果他里面至少有个方法是被声明为抽象的，那么这个类就必须声明为抽象类
// 定义为抽象的类不能被实例化
// 被定义为抽象的方法只是声明了其调用方式，不能定义其具体的功能实现
// 继承一个抽象类的时候，子类必须定义父类中所有抽象的方法，另外这些方法的访问控制必须和父类中一样。
// 例如某个抽象方法被声明为受保护的，那么子类中实现的方法就应该声明为受保护的或者公有的，而不能定义为私有的


// Static 关键字
// 声明类属性或方法为 static 静态，就可以不实例化类而直接访问
// 静态属性不能通过一个类已实例化的对象来访问 但静态方法可以
// 由于静态方法不需要通过对象即可调用，所以伪变量 $this 在静态方法中不可用
// 静态属性不可以由对象通过 -> 操作符来访问


class Foo {
    public static $my_static = "foo1111";

    public function staticValue(){
        return self::$my_static;
    }
}

xcq_echo(Foo::$my_static . PHP_EOL);


$foo = new Foo();

xcq_echo($foo -> staticValue());

// Final 关键字
// PHP 新增了一个final 关键字，如果父类中的方法被声明为final，则子类无法覆盖该方法。如果一个类被声明为final，则不能被继承

//连接数据库测试

$servername = "localhost";
$username = "root";
$password = "chomp1688";

//创建连接
$connect = new mysqli($servername,$username,$password);

//检测连接
if($connect->connect_error){
    die("连接失败：： ".$connect->connect_error);
}else{
    xcq_echo("数据库连接成功！！！！！");
}






?>  


<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
NAME: <input type="text" name="fname">
<input type="submit">
</form>

<a href="test_get.php?subject=PHP&web=runoob.com">$_GET 测试</a>


</body>
</html>