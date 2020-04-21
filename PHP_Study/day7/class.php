<?php

// PHP 类和对象的概念
// 面向对象思想是人类思想的高度抽象
// 面向对象三大特征： 封装、继承、多态
// 类是对象的抽象、对象是类的具象

// 定义个Person类

class Person{

    public $age ,$name ;

    // 构造方法
    public function __construct($name,$age){
        
        $this->name = $name;
        $this->age = $age;
        echo "<br>姓名： ".$this->name."年龄： ".$this->age;

    }


    public function eat(){
        echo "<br> 我在吃饭";
    }

    public function test1(){
        echo "<br>今天天气不错 ";
    }

    public function test2()
    {
         $this-> test1();
         echo "<br>我想出门走一走<br>";
    }

}

// 直接创建初始化
// $shaDiao = new Person();
// $shaDiao->age = 20 ;

// var_dump($shaDiao);
 
// $shaDiao->eat();

// // 类名字符串创建对象
// $name = 'Person';

// $xiaoming = new $name();

// $xiaoming->eat();

//构造方法和this关键字

$p1 = new Person('小明',20);

$p1->test1();


$p2 = new Person('weihua',30);

$p2->test2();


// 继承
// 认识继承
// 基本概念： 继承、派生
// 继承和派生其实是同一个概念，从不同的角度来理解。如果从相同的属性来理解的话，种类包含（生物->动物->哺乳动物->人类）
// 称为继承。如果从独有的属性来理解的话，称之为派生。哺乳动物派生了人类，人类继承于哺乳动物
// 父类=》子类，基类 == 派生类  单继承语言
// 基本语法
// extends : 继承
// 子类继承了父类，那么就拥有了父类的属性和方法

class Animal{
    public $name ;
    public function eat()
    {
        echo "会吃饭<br>";
    }
}

class Human extends Animal
{
    public $age = 20;
    public function __construct($name)
    {
        $this->name = $name;
        echo $this->name;
    }
}

$human = new Human("小明");
echo $human ->age."岁";
$human -> eat();

// public 和 protected 都可以被子类继承，（protected 外部不可以访问）
// private 不可以被子类继承

// 重写方法（重载）
// 重写作用： 父类的方法不适合子类使用，子类可以重写父类的方法
// parent 关键字（普通方法和构造方法）

// parent::work(); 先调用父类的方法，然后增加自己的功能

// final 关键字

// final 修饰类，此类不可继承
// final 修饰方法，方法不可重写

// 重写权限的时候 ，只能放大不能够缩小

class Father
{
    public function jump()
    {
         echo "我能够跳3米高<br>";
    }

    public function work()
    {
         echo '我工作十分的认真努力<br>';
    }
}

class Son extends Father
{

    public $height ;
    public $weight ; 
 
    public function jump()
    {
        echo "我能够跳5米高<br>";
    }

    public function work()
    {
         parent::work();
         // 构造方法也能同步继承使用 parent::__construct();
         echo "我工作之外还能谈个小恋爱<br>";
    }

}

$father = new Father();
$father -> jump();
$father -> work();

$son = new Son();
$son -> jump();
$son -> work();

 