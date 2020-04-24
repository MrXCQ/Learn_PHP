<?php

$db =  require_once __DIR__.'/lib/db.php';

require_once __DIR__.'/class/User.php';

require_once __DIR__.'/class/Article.php';


$user = new User($db);
$article = new Article($db);

$userName = 'admin'.random_int(10,100);

// 注册用户
// $user->register($userName, 'admin134');

var_dump($user->login('admin21','admin134'));

// var_dump($article->create('这是第一篇文章','今天天气可真好哈',10));

var_dump($article->view(1));


var_dump($article->edit(3,"德玛西亚编辑了标题111","德玛西亚永世长存",10));