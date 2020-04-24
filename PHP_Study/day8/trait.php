

<!-- PHP 高级语法 -->

<!-- 1、trait 特性
  用来模拟实现多继承的 
  instance ： 实例 -->

<!-- 定义trait 要以trait关键字开头，然后里面的写法和类的写法相同，一般情况下 trait 不添加属性 只添加方法 -->

<!-- 
trait 不能实例化。
trait 中的方法想让子类来使用，函数方法必须是public
trait 可以 嵌套 trait
-->


<?php

trait Shield 
{
    public function defense()
    {
         echo "抵抗100点攻击 <br>";
    }
}


trait Sword
{
    public function attack()
    {
         echo "增加80点伤害 <br>";
    }
}
// $test1 = new Shield(); 不能实例化

class Hero
{
    use Shield,Sword;
}

$Timor = new Hero();

$Timor->defense();


?>

