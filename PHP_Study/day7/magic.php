<?php
// 魔术方法

// __get 
// 触发时机： 对象在外部访问私有成员或者受保护属性时调用，有一个参数，参数就是属性名

// __set 
// 触发时机： 对象在外部设置私有或者受保护成员属性值时调用。
// 两个参数： 成员属性名，需要设置的值

// 在外部可以通过unset 销毁对象中的public 属性

// __unset  
// 触发时机： 对象在外部销毁私有或者受保护成员属性的时候调用
// 一个参数： 参数就是私有的成员属性名

// __isset 
// 触发时机： 对象在外部判断私有或者受保护成员属性时调用
// 一个参数： 参数就是私有的成员属性名

// __construct 构造方法
// 触发时机： 在创建对象的 方法时调用

// __destruct 析构方法
// 触发时机： 对象在销毁的时候调用,脚本结束之后

// __toString
// 触发时机 echo 一个对象被触发

// debugInfo
// 触发时机 var_dump 一个对象的时候触发
// 需要return 一个数组

// __call 
// 触发时机 当调用一个不存在对象的方法是触发
// 参数： 函数名，一个数组函数的参数放在此数组当中


// __callStatic
// 触发时机 当调用一个不存在静态方法的时候触发
// 参数： 函数名。一个数组，函数中的参数都被存放到这个数组中

// serialize  序列化
// unserialize 反序列化

// __sleep

// __wakeup

// __clone

// __autoload

class Person1
{
    public $name = '小明';
    protected $age = 18;
    private $height = 180;

    public function __get($name)
    {
        echo "__get 魔术方法" . $name ."<br>";
    }

    public function __set($key,$value)
    {
        var_dump($key,$value);
        if ($key == 'age') {
             $this ->age = $value ;
        }
    }

    public function __isset($name)
    {
        echo "调用了此方法";
    }

    public function __destruct()
    {   

        echo "<br>对象被销毁了...";
    }

    public function __toString()
    {
        echo "<br> echo 了数据";
        return "echo 数据";
    }

    public function __debugInfo()
    {
         return ['age' => $this->age,"height" => $this ->height];
    }

    public function test()
    {
         echo '测试方法';
    }

    public function __call($key,$value)
    {
         var_dump($key,$value);
    }

}


$p3 = new Person1();
echo $p3 ->age;


$p3->age = 30;

echo $p3->age;



$p4 = new Person1();

var_dump($p4);

var_dump(isset($p4->name));


$p4 -> demo(1,2,3);


class People
{
    public $name ;
    public $age ;
    public $height ;

    public function __construct($name,$age,$height)
    {
         $this->name = $name ; 
         $this->age = $age ; 
         $this->height = $height ; 
    }

    // 序列化存储部分的对象属性
    public function __sleep()
    {
         echo "<br>我要睡觉了";
         return ['age','height'];
    }

    public function __wakeup()
    {
         echo '<br> 我睡醒了';
    }

    public function __clone()
    {
        $this->age = 100;
    }
}


$people = new People('德玛西亚',20,180);

// 序列化存储对象 
$str =  serialize($people);

echo '<br>'.$str;

// 保存文件
file_put_contents('people.txt',$str);


$people2 = new People("诺克萨斯",17,182);

// 反序列化 读取文件
$contents =  file_get_contents('people.txt');

$obj =  unserialize($contents) ;
 
var_dump($obj);


// clone  克隆拷贝一个对象 会调用__clone 方法
$obj = clone $people2 ;

var_dump($obj);


function __autoload($className)
{   
    $file = $className.'.php';
    include $file;

    echo $className;
}

// 这里会报错  __autoload
$dog = new Dog();

$dog -> run();
