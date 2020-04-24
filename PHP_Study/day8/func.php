<!-- 1.函数
call_user_func 
call_user_func_array
spl_autoload_register __autoload
-->
<?php

function today($a)
{
     echo '今天天气不错!!'.$a.'<br>';
}

call_user_func('today','天晴');
call_user_func_array('today',['大晴天']);

class Dog
{
    function wang()
    {
        echo "汪汪汪<br>";
    }

    function eat()
    {

        call_user_func([$this,'wang']);
        echo "我特么要吃饭了 <br>";
    }
}


$dog = new Dog();
call_user_func([$dog,'eat']);


function myAutoload($className)
{
     echo $className ;
}

spl_autoload_register('myAutoload');

$dog2 = new Dog ;


?>

