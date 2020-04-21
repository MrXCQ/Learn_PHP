
<?php

// 1、类常量
// 调用方法
// 类外部： 类名：：常量名 （$obj::常量名）
// 类内部： self::常量名 （$this::常量名）
// 在类外部可以使用define 和 const 定义常量。但是在类内部职能使用const 定义
// 常量前面不能够加修饰符

// 2. 静态属性和方法
// static 如果用来修饰属性和方法之后，那么该属性和方法是  属于整个类的 ，不是属于某个对象的。
// 对象属性和对象方法

// 3.静态方法注意事项
// 1.静态属性和方法前面可以加属性修饰符
// 2.静态属性和静态方法效率高
// 3.通过静态方法来创建单例
// 4.静态方法不能出现$this关键字


class Person
{

    public $name ;
    public $age ;
    

    // const 关键字定义常量
    const A = 100 ;

    //静态属性
    public static $height = 180 ;

    public function test()
    {
         echo "<br>".self::A;
    }

    // 静态方法
    public static function staticFunc()
    {
        // $this ->name; 静态方法中不能出现$this
         echo "<br> 这是一个静态方法";
    }

    public function test2()
    {
         self::staticFunc();
    }


}


echo Person::A;

$p1  = new Person();
$p1 -> test();

//静态属性的方法

echo "<br>静态属性".Person::$height;

Person::staticFunc();

$p1->test2();