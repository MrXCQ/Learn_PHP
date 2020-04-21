<?php 

function xcq_echo($content){
    echo "<br>" . $content ; 
}

// 连接MySQL localhost 有时候会报错 具体解释请参考： https://blog.csdn.net/zhaoerduo/article/details/51076456
$severname = '127.0.0.1';
$loginname = 'root' ;
$password = 'chomp1688';
$dbname = "myDB";

// PHP MySQL 读取数据

// SELECT 语句用户从数据表中读取数据 ：
// SELECT column_name(s) FROM table_name


$connect = new mysqli($severname,$loginname,$password,$dbname)  ;

if ($connect->connect_error) {
     die("数据库连接失败！！！".$connect->connect_error) ;
}else{
    xcq_echo("数据库连接成功!!!");
}

$sqlStr = "SELECT * FROM UserInfo" ; 

xcq_echo($sqlStr);

// $result = $connect->query($sqlStr);


// if ($result->num_rows > 0) {
//     //输出数据
//     while ($row = $result->fetch_assoc()) {
//          xcq_echo("id : ".$row["id"] ."  名字 : ".$row["username"]."  年龄 : ".$row["userage"]." 电子邮箱 : ".$row["email"]);
//     }
// }else{
//     xcq_echo("暂时无查找结果") ;
// }

$connect->close();

// 使用PDO 预处理

xcq_echo("<table style='border: solid 1px black;'");
xcq_echo("<tr><th>ID</th><th>姓名</th><th>年龄</th><th>邮箱</th></tr>");

class TableRows extends RecursiveIteratorIterator{

    function __construct($it){
        parent::__construct($it,self::LEAVES_ONLY);
    }

    function current(){
        return "<td style='width:150px;border:1px solid black;'>" .parent::current()."</td>" ; 
    }

    function beginChildren(){
        echo "<tr>";
    }

    function endChildren(){
        echo "</tr>" ; 
    }
}

try {
    $connect = new PDO("mysql:host=$severname;dbname=$dbname",$loginname,$password);

    $connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION) ;
    $stmt = $connect->prepare("SELECT * FROM UserInfo WHERE username ='name1' order by id DESC") ; 
    $stmt->execute();

    //设置查询的结果为关联数据
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

    foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $key => $v) {
        echo $v;

    }

} catch (PDOException $e) {
    xcq_echo("Error : ".$e->getMessage());
}

echo "</table>";

// WHERE 子句 用于提取满足指定标准的记录
// order by 关键词用于对记录集中的数据进行排序  ASC | DESC













?>