<?php

// mysqli 连接数据库
$severname = '127.0.0.1';
$username = 'root' ;
$password = 'chomp1688';

// $connect = mysqli_connect($severname,$username,$password);

//  if (!$connect) {
//      die('服务器连接错误'.mysqli_error($connect));
//  }

//  echo '<br> 服务器连接成功！！';

// $createDBSql = 'CREATE DATABASE IF NOT EXISTS userDB DEFAULT CHARSET UTF8 COLLATE UTF8_GENERAL_CI';

// $ret = mysqli_query($connect,$createDBSql);

// if (!$ret) {
//      die('数据库创建失败！！'.mysqli_error($connect)) ;
// }

// echo '<br> userDB 数据库创建成功！！';

// mysqli_close($connect);


// PDO 连接数据库 推荐使用PDO 防止sql 注入

try {
    $connect = new PDO("mysql:host=$severname;dbname=userDB;charset=utf8", $username, $password);

    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //新增数据库
    //$sql = 'CREATE DATABASE userDB';

    // $sql = 'drop DATABASE userDB'; 删表

    //$connect->exec($sql) ;

    // echo "<br>数据库创建成功";

    
    // 新增数据表
    // $createTable = 'CREATE TABLE userInfo(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    //             userName VARCHAR(30) NOT NULL,
    //             userAddress VARCHAR(30) NOT NULL,
    //             email VARCHAR(30) NOT NULL)';

    $randName = random_int(0, 100) . "德玛西亚";

    // 插入数据
    $sql = "insert into userInfo(userName,userAddress,email) values('$randName','符文大陆','demaxiya@163.com')" ;


    if ($connect->query($sql)) {
        echo '<br> userInfo SQL'.$sql.'执行成功!!!';
    } else {
        echo '<br> userInfo SQL'.$sql.'执行失败!!!'.$connect->error;
    }
} catch (Exception $e) {
    echo "<br>数据库创建失败".$sql.'<br>错误信息:'.$e->getMessage();
}

// 创建数据表 常见的数据格式要求解释见 https://www.runoob.com/sql/sql-datatypes.html





// 删除数据库
// drop DATABASE userDB
