<?php

//读取文件

// readfile("file.txt");

// var_dump(readfile("file.txt"));

// var_dump(file("file.txt"));

// $contents =  file_get_contents("file.txt");

// echo $contents ;

// 在对应文件的内容 修改后新的的内容 没有文件 则新建
// file_put_contents('file.txt','写入文件的内容11111');

// file_put_contents('filetest.txt','写入文件的内容11111');

// var_dump(pathinfo('file.txt'));
echo "<br>";
var_dump(basename('file.txt'));

var_dump(dirname('file.txt'));



// 读取文件
$fp = fopen("file.txt",'r');

$str = "测试字符串";

fwrite($fp, $str);

fclose($fp);


// 删除文件夹
rm("file");

function rm($path){
    //打开目录
    $dir = opendir($path) ;

    // 跳过两特殊的目录结构
    readdir($dir) ;
    readdir($dir) ; 

    //循环删除  
    while ($newFile = readdir($dir)) {
         // 判断是否是文件还是文件夹

         $newPath = $path.'/'.$newFile ;

         if (is_file($newPath)) {
              unlink($newPath);
         }else{
             rm($newPath);
         }
    }

    closedir($dir) ;
    rmdir($path) ;


}


?>