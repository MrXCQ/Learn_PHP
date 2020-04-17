<?php 

function xcq_echo($content){
    echo "<br>" . $content ; 
}

// 连接MySQL localhost 有时候会报错 具体解释请参考： https://blog.csdn.net/zhaoerduo/article/details/51076456
$severname = '127.0.0.1';
$username = 'root' ;
$password = 'chomp1688';

// 面向对象连接
//创建连接
// $connect = new mysqli($severname,$username,$password) ;

// //检测是否连接
// if ($connect->connect_error) {
//     die('连接失败： ' . $connect->connect_error);
// }

// echo "连接成功";

// 关闭连接
// $connect->close();


// 面向过程连接
$connect  = mysqli_connect($severname,$username,$password,"myDB") ;

// 检测是否连接
if ($connect->connect_error) {
    die('连接失败： ' . $connect->connect_error);
}else{
    xcq_echo("连接成功");
}


// mysqli_close($connect);


// 示例PDO

// try {
//     $connect = new PDO("mysql:host=$severname;",$username,$password);
//     echo '连接成功!';
// } catch (PDOException $e) {
//     echo $e->getMessage();
// }

//关闭连接
// $connect = null ;



// 开始创建数据库
$sqlStr = "CREATE DATABASE myDB";
if ($connect->query($sqlStr) === TRUE) {
    xcq_echo("数据库创建成功!!!");
}else{
    xcq_echo("数据库创建失败".$connect->error); 
}

 

// $connect->close();


// 使用 sql 创建数据表
$sqlCreate = "CREATE TABLE UserInfo (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
userage INT(6) NOT NULL,
email VARCHAR(50))" ;

xcq_echo($sqlCreate);

if ($connect->query($sqlCreate) === TRUE) {
    echo "数据表新建成功";
}else{
    echo "数据表新建失败" . $connect->error;
}

// 增

$sqlAdd = "INSERT INTO UserInfo (username,userage,email) VALUES ('Jhon',20,'Jhon@163.com'),('Jhon1',21,'Jhon@163.com'),('Jhon2',22,'Jhon@163.com')";

if ($connect->query($sqlAdd) === TRUE) {
    xcq_echo("记录插入成功");
}else{
    xcq_echo("记录插入失败".$sqlAdd."<br>".$connect->error);
}

// MySQL 插入多条数据

// mysqli_multi_query() 函数可以用来执行多条SQL 语句

$sql1  = "INSERT INTO UserInfo (username,userage,email) VALUES ('name1',24,'name1@163.com'),('name2',24,'name2@163.com'), ('name3',24,'name3@163.com')" ;
// $sql1 .= "INSERT INTO UserInfo (username,userage,email) VALUES ('name2',24,'name2@163.com')" ;
// $sql1 .= "INSERT INTO UserInfo (username,userage,email) VALUES ('name3',24,'name3@163.com')" ;

if ($connect->multi_query($sql1) === TRUE) {
    xcq_echo('多条数据插入成功');
}else{
    xcq_echo('多条数据插入失败'.$connect->error);
}

// 使用预处理语句
// mysqli 拓展提供了第二种方式用于插入语句
// 预处理语句以及绑定参数
// mysql 拓展可以不带数据发送语句或查询到mysql 数据库。可以向列关联或 绑定 变量


if ($connect->connect_error) {
     die("连接失败：  ".$connect->connect_error) ;
}else{

    xcq_echo("连接成功!!!");

    $sql2 = "INSERT INTO UserInfo(username,userage,email) VALUES(?,?,?)";

    // 为 mysqli_stmt_prepare() 初始化 statement 对象
    $stmt = mysqli_stmt_init($connect); 

    //预处理语句
    if (mysqli_stmt_prepare($stmt,$sql2)) {
        //  绑定参数
        mysqli_stmt_bind_param($stmt,'sss',$username,$userage,$email);


        $username = "小明" ;
        $username = 23 ;
        $email = "xiaoming@163.com" ; 
        mysqli_stmt_execute($stmt) ;

        xcq_echo("插入成功");
    }else{
        xcq_echo("插入失败");
    }

    $stmt->close();
    $connect->close();
}


$dbname = "myDB";

// PHP MySQL PDOz中的预处理语句
try {
    $connect_PDO = new PDO("mysql:host=$severname;dbname=myDB","root",$password);

    // 设置PDO 错误模式为异常
    $connect_PDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION) ; 

    // 预处理 SQL 并绑定参数
    $stmt = $connect_PDO->prepare("INSERT INTO UserInfo(username,userage,email) VALUES (:username,:userage,:email)");
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':userage',$userage);
    $stmt->bindParam(':email',$email);

    // 插入行
    $username = "PDO用户1";
    $userage = 25 ;
    $email = "pdo1@163.com";
    $stmt->execute();

    $username = "PDO用户2";
    $userage = 25 ;
    $email = "pdo2@163.com";
    $stmt->execute();

    $username = "PDO用户3";
    $userage = 25 ;
    $email = "pdo3@163.com";
    $stmt->execute();

    $username = "PDO用户4";
    $userage = 25 ;
    $email = "pdo4@163.com";
    $stmt->execute();

    xcq_echo("PDO 新纪录插入成功!!!!");

} catch (PDOException $th) {
    xcq_echo("Error : ".$th->getMessage());
}








?>