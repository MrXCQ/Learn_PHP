<!-- PHP 高级语法  

mysql sqserver  oracle db2  多种数据库 pdo 可以连接所有的数据库
主要是三个类 : PDO , PDOStatement, PDOException


PDO 执行sql 语句
exec 执行不要结果集的语句   增删改
query 执行要结果集的语句    查 
lastInsertId             最后插入语句的id号
-->


<?php

// PDO 连接数据库
$severname = '127.0.0.1';
$username = 'root' ;
$password = 'chomp1688';

$dsn = 'mysql:host=127.0.0.1;dbname=myDB;charset=utf8';

// 1.字符串形式
// $pdo = new PDO($dsn,$username,$password);

// var_dump($pdo);

try {
    $pdo  = new PDO($dsn, $username, 'chomp1688');

    // 设置错误模式
    // 警告
    // $pdo ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);

    //异常
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('数据库连接失败'.$e->getMessage());
}

var_dump($pdo);


// 2、文件形式连接

// try {
//     $pdo  = new PDO("uri://file://dsn.txt",$username,'chomp1688');
// } catch ( Exception $e) {
//     die($e->getMessage());
// }

// var_dump($pdo);

// 3.更改 php.ini


// 增删改查

try {
    $randName = random_int(0, 100) . "德玛西亚";

    // $sql = 'insert into UserInfo(username,userage,email) value ("'.$randName.'",200,"demaxiya@163.com")';

    // echo $sql."<br>";

    // $ret = $pdo ->exec($sql);
    
    // $updateSql = 'update UserInfo set username = "'.$randName.'" where id > 0';

    // echo '<br>'.$updateSql."<br>";

    // $ret = $pdo ->exec($updateSql);


    $selectSql = 'select * from UserInfo';

    $ret = $pdo ->query($selectSql);

    var_dump($ret);



    if ($ret) {
        echo "<br>SQL 执行成功";
        echo "<br>SQL 执行的最新一条数据的ID".$pdo->lastInsertId().'<br>';
    } else {
        echo "<br>SQL 执行失败";
    }
} catch (PDOExcepton $e) {
    echo $e -> getMessage();
}
