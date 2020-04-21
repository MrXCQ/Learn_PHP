<?php 

// PHP Session 
// 此变量用于存储关于用户会话 session 的信息，或者更改用户会话的设置，Session 变量存储单一用户的信息，并且对于应用程序中的所有页面都是可用的

// 在计算机上操作某个应用程序 ，每次动作都是一个对话 session 计算机知道是何时打开和关闭程序的，每次操作的动作的时间点是什么。HTTP 短连接，web 服务器不知道您是说以及具体的动作

// Session 解决了此问题，通过服务器上存储用户信息以便随后使用，然而 会话信息是临时的，在用户离开网站后将被删除。需要持久化存储可以使用数据库进行保存

// Session 的工作机制，为每个访客创建一个唯一的id。并基于这个UID 来存储变量。UID 存储在cookie 中。或者通过URL 进行传递

// 1、开始  Session
// 把信息存储到session 之前 首先必须要启动会话。

session_start();
// 此行代码会向服务器注册用户的会话，以便于开始保存用户信息，同时会为用户会话分配一个UID

// 存储 Session变量
// 存储和取回 session 变量的正确方法是使用 PHP $_SESSION 变量
$_SESSION['views'] = 10000 ;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session</title>
</head>
<body>
    <?php 
        include 'header.php';
        //检索 session 数据
        xcq_echo("浏览量 : ".$_SESSION['views']);

        // 销毁 Session 
        // unset() 或者 session_destroy() 函数。


        if (isset($_SESSION['views'])) {
            // 销毁session
            unset($_SESSION['views']) ;
            xcq_echo("session 数据已销毁");
        }    
    ?>


    <?php 
        // 发送电子邮件
        // 语法
        // mail(to,subject,message,headers,parameters)
        
        xcq_echo("PHP 电子邮件 ==>") ;

//         to	必需。规定 email 接收者。
//     subject	必需。规定 email 的主题。注释：该参数不能包含任何新行字符。
//     message	必需。定义要发送的消息。应使用 LF (\n) 来分隔各行。每行应该限制在 70 个字符内。
//     headers	可选。规定附加的标题，比如 From、Cc 和 Bcc。应当使用 CRLF (\r\n) 分隔附加的标题。
// parameters	可选。对邮件发送程序规定额外的参数。

    $to = "IMpBear@163.com" ; //邮件接收
    $subject = "邮件标题" ;     //标题
    $message = "这是一份电子邮件"; // 正文
    $from = "someone@163.com" ; // 邮件发送者
    $headers = "Froms" . $from ;  // 邮件同步信息设置

    mail($to,$subject,$message,$headers ) ;
    xcq_echo("PHP 电子邮件已发送 ==>") ;

        
    // PHP 的错误处理
    // 1、简单的die() 语句
    // 2、自定义错误和错误触发器
    // 3、错误报告

    // if (!file_exists("welcome.txt")) {
    //          die("文件不存在") ;  程序会在这行停止执行
    // }else{
    //     echo "文件存在";
    // }
    
    // 创建自定义错误处理器

    // 创建一个自定义的错误处理器， 
    // error_function(error_level,error_message);
    // error_file,error_line,error_context
    // error_level	    必需。为用户定义的错误规定错误报告级别。必须是一个数字。参见下面的表格：错误报告级别。
    // error_message	必需。为用户定义的错误规定错误消息。
    // error_file	    可选。规定错误发生的文件名。
    // error_line	    可选。规定错误发生的行号。
    // error_context	可选。规定一个数组，包含了当错误发生时在用的每个变量以及它们的值。

    // 2	E_WARNING	        非致命的 run-time 错误。不暂停脚本执行。
    // 8	E_NOTICE	        run-time 通知。在脚本发现可能有错误时发生，但也可能在脚本正常运行时发生。
    // 256	E_USER_ERROR	    致命的用户生成的错误。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_ERROR。
    // 512	E_USER_WARNING	    非致命的用户生成的警告。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_WARNING。
    // 1024	E_USER_NOTICE	    用户生成的通知。这类似于程序员使用 PHP 函数 trigger_error() 设置的 E_NOTICE。
    // 4096	E_RECOVERABLE_ERROR	可捕获的致命错误。类似 E_ERROR，但可被用户定义的处理程序捕获。（参见 set_error_handler()）
    // 8191	E_ALL	            所有错误和警告。（在 PHP 5.4 中，E_STRICT 成为 E_ALL 的一部分）
    
    function customError($errorno,$errorstr){
        xcq_echo("<b> Error: </b> [$errorno] $errorstr");
    }

    set_error_handler("customError");

    //触发错误
    echo($test111);

    // 在脚本中用户输入数据的位置，当用户输入无效时触发错误是很有用的。在PHP中，这个任务由 trigger_error() 函数完成
    $test = 2 ;

    if ($test > 1) {
         trigger_error("test 变量值必须小于等于 1");
    }else{
        echo "见了鬼了";
    }

    // E_USER_ERROR - 致命的用户生成的run-time 错误。错误无法恢复，脚本被中断
    // E_USER_WARNING - 非致命的用户生成的run-time 警告。脚本执行不被中断
    // E_USER_NOTICE  默认。用户生成的run-time 通知。在脚本发现可能有错误时发生，但也可能在脚本正常运行时发生。



    // 错误记录
    // 在默认的情况下，根据在php.ini 中的error_log 配置，PHP 向服务器的记录系统或文件发生错误记录。通过使用error_log() 函数可以发送错误文件记录

    // 错误处理函数
    function customMailError($errno, $errstr)
    {
        echo "<b>Error:</b> [$errno] $errstr<br>";
        echo "已通知网站管理员";
        error_log("Error: [$errno] $errstr",1,
        "someone@example.com","From: webmaster@example.com");
    }


    // 设置错误函数
    set_error_handler("customMailError") ;

    $test3 = 10 ;

    if ($test > 1) {
        trigger_error("test 变量值必须小于等于 1");
   }else{
       echo "见了鬼了";
   }


   // PHP的异常的基本使用

//    当异常被抛出时，其后的代码不会被执行，php 会尝试查找匹配的 ‘catch’ 代码块
//    如果异常没有被捕获，而且没有使用 set_exception_handler() 作相应的处理会发生一个严重的错误  Uncaught  Exception

function checkNum($number)
{
    if ($number > 1) {
         throw new Exception("number 值不能大于1");
    }
}

// Try  throw 和 catch 
// 1、 try 使用异常的函数应该位于 try 代码块内。如果没有触发异常，则代码将照常继续执行。但是如果异常被触发 则会抛出异常。
// 2、 Throw 内规定如何触发异常。每一个 throw 必须对应至少一个 catch 
// 3、 Catch 代码块会捕获异常，并创建一个包含异常信息的对象


try {
    checkNum(3);
    xcq_echo("如果输出该内容，说明 $number 不符合要求");
} catch (Exception $th) {
    xcq_echo("Message : " . $th->getMessage());
}

// 创建一个自定义的 Exception 类

// 创建自定义的异常处理程序非常简单，我们简单地创建了一个专门的类，当PHP 中发生异常，可调用其函数。该类必须是 Exception 类的一个扩展

class customException extends Exception{
    public function errorMessage()
    {
        //  错误信息
        $errMsg = '错误行号' . $this->getLine().'in'.$this->getFile().':<b>'.$this->getMessage().'</b> 不是一个合法的E-Mail 地址';
        return $errMsg ; 
    }
}


$email = "someone@163.com" ;

try {
    // 检测邮箱

    if (filter_var($email,FILTER_VALIDATE_EMAIL) == FALSE) {
        // 如果不是合法的邮箱地址 抛出异常
        throw new customException($email);
    }

    // 检测 example 是否在邮箱地址当中
    if (strpos($email,"example") !== FALSE) {    
        throw new customException("$email 是example 邮箱");
    }


} 
catch (customException $e) {
    xcq_echo($e->errorMessage());
}

catch (customException $e) {
    xcq_echo($e->getMessage());
}







?>

</body>
</html>