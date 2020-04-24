<!-- 异常处理：
代码按照正常的逻辑运行，如果出现异常，则执行事先准备好的另一套方案
try
catch 映射到代码中，try 是尝试执行某一块的代码，然后如果出现异常情况，那么需要手动抛出异常 

try - catch 是一种结构，一个try 必须要最少有一个 catch

Exception
官方的异常处理类 是所有异常类的基类
getMessage() 得到抛出异常的信息
getCode() 得到抛出异常的Code值


自定义异常处理函数：
set_exception_handler()

try - catch 可以嵌套


-->

<?php

try {
    
    echo '清早起床美美哒 <br>';

    throw new Exception("有点拉肚子<br>", 10);
    

    echo "刷牙洗脸吃早饭 <br>";

} catch (Exception $e) {
    echo $e -> getMessage();
    echo $e -> getCode();

}


echo '吃完饭去上班 <br>';


// 自定义的异常捕获类

class myException extends Exception
{
    function demo()
    {
        echo "自定义的异常捕获方法 <br>" ;
    }

}

try {
    
    echo '清早起床美美哒2 <br>';

    throw new myException("有点拉肚子2<br>", 20);

    echo "刷牙洗脸吃早饭2 <br>";

} 

catch (myException $e) {
    echo $e -> getMessage();
    echo $e -> getCode();
    echo $e -> demo();
}

catch (Exception $e) {
    echo $e -> getMessage();
    echo $e -> getCode();
    
}


// 自定义捕获异常函数
function customException($e)
{
    echo "<br>自定义捕获函数".$e -> getMessage();
    echo "<br>自定义捕获函数".$e -> getCode();
}

set_exception_handler('customException');

throw new Exception('<br>代码存在异常', 1);









