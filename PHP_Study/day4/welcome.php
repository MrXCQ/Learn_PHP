
<?php 

// 何时使用 method = 'get'
// 在HTML 表单中使用method = get 的时候，所有的变量名称和值都会显示在url 当中
// 在发送密码或者敏感信息时不应该使用get 方法
// 然而在变量在url 中能够显示收藏夹收藏该页面。
// HTTP GET 方法不适合大小的变量值类型。他的值是不能够超过2000个字符的

function xcq_echo($content){
    echo "<br>" . $content ; 
}
xcq_echo("GET 模式 : ");
xcq_echo('欢迎： '.$_GET['uname']);

xcq_echo('您的年龄是: '.$_GET['uage'].'岁');


// $_POST 
// 预定义的post 变量用于收集来自于method = post 的表单中的值
// 从带有POST 的方法的表单发送的信息。POST方法表单发送的信息，不会在浏览器的地址栏显示，对发送的信息量也无限制
// POST 方法发送的最大值为 8MB 可以通过设置php.ini 文件中的post_max_size 进行更改设置

xcq_echo("POST模式 ：");

xcq_echo('欢迎： '.$_POST['pname']);

xcq_echo('您的年龄是: '.$_POST['page'].'岁');


// PHP $_REQUEST 变量
// 预定义的$_REQUEST 变量包含l  $_GET 、$_POST 和$_COOKIE 的内容

// $_REQUEST 变量可用来收集通过GET 和 POST 方法发送的表单数据
 
xcq_echo('欢迎： '.$_REQUEST['pname']);

xcq_echo('您的年龄是: '.$_REQUEST['page'].'岁');

?>