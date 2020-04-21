<?php 

include "header.php";

//  PHP 过滤器
// 用于验证和过滤来自非安全来源的数据
// 测试、验证和过滤用户输入或自定义数据是任何web 应用程序的重要组成部分
// PHP 的过滤器扩展的设计目的是使数据过滤更轻松快捷
// 什么是外部数据
// 1. 来自表单的输入数据
// 2. Cookies
// 3. Web services data
// 4. 服务器变量
// 5. 数据库查询结果

// 过滤函数
// filter_var() 通过一个指定的过滤器来过滤单一的变量
// filter_var_array() 通过相同或不同的过滤器来过滤多个变量
// filter_input()  获取一个输入变量，并对他进行过滤
// filter_input_array() 获取多个输入变量 ，并通过相同或者不同的过滤器对它们进行过滤


$int = 100 ;

if (!filter_var($int,FILTER_VALIDATE_INT)) {
     echo "不是一个正确的整数";
}else{
    echo '是一个正确的整数';
}

// Validating 和 Sanitizing 
// 两种过滤器

// validating 过滤器：

// 用于验证用户输入
// 严格的格式规则  比如 URL 和 email 验证


// Sanitizing

// 用于允许或者禁止字符串中指定的字符
// 无数据格式规则
// 始终返回字符串

// 选项和标识
// 选项和标识用于向指定的过滤器添加额外的过滤选项
// 不同的过滤器有不同的选项和标识






?>