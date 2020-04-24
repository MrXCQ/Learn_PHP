<?php

// 抽象类
// 1、抽象类不能实例化对象
// 2、抽象类存在的目的是为了让子类继承
// 3、抽象类的定义和普通类的定义一样，只不过是前面加了一个关键字
// 5、抽象方法只能是public 或 protected
// 6、抽象方法如果有参数，参数有默认值，实现该方法时候参数和默认值也都要有
// 7、抽象类可以继承抽象类，子类在实现的时候所有的抽象方法都得实现

// 使用场景 : 通用的东西放到一块，写一个父类
// 不同的东西各个子类自己来实现

 
abstract class Person
{
    public $name ; 
    
    public function sayHello()
    {
         echo "hello world!";
    }
    // 申明方法
    abstract public function eat();
}


//$person1 = new Person();// 抽象类不能实例化

class Man extends Person
{
    public function eat()
    {
        echo "<br> 吃东西";
    }
}

$man = new Man();

$man -> sayHello();

$man -> eat();



// 接口 抽象的抽象类

// interface ： 接口
// implements : 实现

// 接口中的方法都是抽象方法，所以abstract 省略不写
// 接口中的方法必须是pubic 
// 接口中智能规定方法，不能写属性（接口中可以写常量）
// 一个类可以实现多个接口，分开
// 一个类可以先继承父类 再实现接口
// 接口可以继承接口，但是里面的方法都需要实现
// 面向接口开发

/*
abstract class Test
{
    abstract function test1();
    abstract function test2();
}
*/

interface Run
{
    function fastRun();
}

interface Eat
{
    function eatDinner();
}

interface Test extends Eat 
{

}

class Person2 implements Run,Eat 
{
    public function fastRun()
    {
        echo "<br>快速跑步";
    }

    public function eatDinner()
    {
        echo "<br>吃晚餐啦";
    }
}











