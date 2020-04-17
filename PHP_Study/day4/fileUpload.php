<?php 
 
 include 'header.php';

 xcq_echo('文件上传!!');
// 增加了对文件上传的限制，用户只能上传gif/jpeg/jpg/png 文件，文件大小必须小于200kb

// 允许上传的图片后缀
$allowedExts = array('gif','jpeg','jpg','png');
$temp = explode('.',$_FILES['file']['name']);
$extension = end($temp) ;// 获取文件后缀名

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 204800)    // 小于 200 kb
&& in_array($extension, $allowedExts)){
    if ($_FILES['file']['error'] > 0) {
        xcq_echo('错误'. $_FILES['file']['error']);
     }else{
        
        xcq_echo('上传的文件名 : ' . $_FILES['file']['name']);
        xcq_echo('文件类型 : '.$_FILES['file']['type']) ;
        xcq_echo('文件大小 : '.$_FILES['file']['size']/1024 .'KB') ;
        xcq_echo('文件临时存储的位置 : '.$_FILES['file']['tmp_name']) ;

        // 上传的文件进行保存 判断当前upload 目录是否存在该文件
        // 如果没有upload 目录，你需要创建他，upload 目录权限为 777
        if (file_exists("upload/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下 chmod -R 777 upload 要更改文件夹权限
            move_uploaded_file($_FILES['file']['tmp_name'], "upload/" . $_FILES["file"]["name"]);
            echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
        }          
     }
}
else
{
    xcq_echo("非法的文件格式!!!!!");
}


// PHP  Cookie 
// cookie 常用于识别用户。cookie 是一种服务器留在用户计算机上的小文件。每当同一台计算机通过浏览器请求页面时，这台计算机将会发送 cookie。通过PHP，您能够创建并取回cookie的值

// 创建 Cookie 
// setcookie() 函数用于设置cookie() 必须位于<html> 标签之前

// setcookie(name,value,expire,path,domain);

// 创建名为 "user" 的cookie  并赋值为runoob，一个小时候后过期

// setcookie("user","runoob",time()+3600);

// 在发送cookie 时，cookie 的值会进行url 编码，在取回时进行自动编码，为防止URL 编码（setrawcookie()） 


// 得到cookie 的值
// $_COOKIE 变量用于取回cookie 的值

// xcq_echo($_COOKIE['user']) ; 


//删除cookie 
// 删除cookie 是 应对是过期日期变更为过去的时间点
// setcookie("user","",time()-3600)  ;
?>
