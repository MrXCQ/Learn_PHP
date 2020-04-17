<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PHP表单</title>
</head>

<body>

<?php 

function xcq_echo($content){
    echo "<br>" . $content ; 
}

    // PHP 中的$_GET 和 $_POST 变量用于检索表单中的信息，比如用户输入。
    // PHP 表单处理，当处理HTML 表单时 PHP能把HTML 页面中的表单元素变成可供PHP 脚本使用
    xcq_echo("您提交的名称为： " . $_POST["fname"]);
    xcq_echo("您的年龄为： ".$_POST['age']);   
  
    // PHP 獲取下拉菜单的数据

    $q = isset($_GET['q'])?htmlspecialchars($_GET['q']):'';

    if ($q) {
        if ($q == 'RUNOOB') {
            xcq_echo("菜鸟教程 == http://www.runoob.com");
        } 
        elseif ($q == 'Google') {
            xcq_echo("google == http://www.google.com");
        }
        elseif ($q == 'BaiDu') {
            xcq_echo('百度 === http://www.baidu.com');
        }
        else {
            xcq_echo("淘宝 == http://www.taobao.com");

        }
        
    }



    // PHP 获取多选下拉菜单数据

    $q2 = isset($_POST['q']) ? $_POST['q'] :'';

    if (is_array($q2)) {
        $sites = array('RUNOOB'=>'http:www.runoob.com',
                        'Google'=>'http:www.google.com',
                        'BaiDu'=>'http:www.baidu.com',
                        'TAOBAO'=>'http:www.taobao.com');

        foreach ($q2 as $val) {
             xcq_echo($sites[$val]);
        }
    }
    

    // PHP单选按钮
    $q3 = isset($_GET['q3']) ? $_GET['q3'] : '' ; 

    if ($q3) {
        if ($q3 == 'RUNOOB') {
            xcq_echo("菜鸟教程 == http://www.runoob.com");
        } 
        elseif ($q3 == 'Google') {
            xcq_echo("google == http://www.google.com");
        }
        elseif ($q3 == 'BaiDu') {
            xcq_echo('百度 === http://www.baidu.com');
        }
        else {
            xcq_echo("淘宝 == http://www.taobao.com");

        }
    }


    // PHP 复选框


 $q4 = isset($_POST['q4']) ? $_POST['q4'] :'';

    if (is_array($q4)) {
        $sites = array('RUNOOB'=>'http:www.runoob.com',
                        'Google'=>'http:www.google.com',
                        'BaiDu'=>'http:www.baidu.com',
                        'TAOBAO'=>'http:www.taobao.com');

        foreach ($q4 as $val) {
             xcq_echo($sites[$val]);
        }
    }


// 表单验证
// 我们应该尽可能的对用户的输入进行验证。浏览器速度更快，并且可以减轻服务器的压力。如果用户输入需要插入数据库，您应该考虑使用服务器验证
// 在服务器验证表单的一种好的方式是，把表单的数据传给当前页面（异步提交的方式），而不是跳转不同的页面 用户可以在同一张表单页面得到错误信息

$name = $email = $gender = $comment = $website = '' ;
$nameErr = $emailErr = $genderErr = $commentErr = $websiteErr = '' ;


// 都是可选的信息
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//      $name    = getDomData($_POST['name']);
//      $email   = getDomData($_POST['email']);
//      $website = getDomData($_POST['website']);
//      $comment = getDomData($_POST['comment']);
//      $gender  = getDomData($_POST['gender']);
// }

// 去除可能被攻击的符号
function getDomData($data){
    // trim()函数去除用户输入数据不必要的字符（空格、tab、换行）
    $data = trim($data);
    // stripslashes() 函数去除用户输入的反斜杠
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// 表单的必填字段
// 上述的表单提交设置的都不是必需填写的，皆为可选

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         if (empty($_POST['name'])) {
             $nameErr  = "名字是必填信息哦" ;
         }else{
            $name = getDomData($_POST['name']);
         }

         if (empty($_POST['email'])) {
            $emailErr  = "名字是必填信息哦" ;
        }else{
           $email = getDomData($_POST['email']);
        }

        if (empty($_POST['website'])) {
            $websiteErr  = "名字是必填信息哦" ;
        }else{
           $website = getDomData($_POST['website']);
        }

        if (empty($_POST['comment'])) {
            $commentErr  = "名字是必填信息哦" ;
        }else{
           $comment = getDomData($_POST['comment']);
        }

        if (empty($_POST['gender'])) {
            $genderErr  = "名字是必填信息哦" ;
        }else{
           $gender = getDomData($_POST['gender']);
        }
    }



    xcq_echo("<h2>您输入的用户信息为：</h2>");
    xcq_echo("姓名：".$name);
    xcq_echo("邮箱：".$email);
    xcq_echo("网址：".$website);
    xcq_echo("备注：".$comment);
    xcq_echo("性别：".$gender);













?>



</body>
</html>