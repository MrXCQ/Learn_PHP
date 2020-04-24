<?php
namespace m1;
class Man
{
    public function eat()
    {
        echo "我在吃午饭<br>";
    }

}

namespace m2;
class Man
{
    public function eat()
    {
        echo "我在吃晚饭<br>";
    }
}


// $man = new Man();

// $man -> eat();

// 根空间的概念 \
$man = new \m1\Man();

$man -> eat();

$man2 = new \m2\Man();
$man2 -> eat();

// use 的使用




?>

<!-- 2、命名空间
在一个文件中不能用相同的类名 
namespace 命名空间
user : 使用
1.第一个命名空间的命名，前面不能有任何的代码
2.根空间、子空间
3.use 使用 as 使用
-->

