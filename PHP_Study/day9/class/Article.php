<?php

require_once __DIR__.'/Error.php';

class Article
{

private $_db;


public function __construct(PDO $db)     
{
    $this->$_db = $db;
}

/****
 * 文章发表
 */
public function create($title,$content,$userid)
{
     if (empty($title)) 
     {
          throw new Exception('文章标题不能为空', IMp_Error::ARTICLE_TITLE_NOT_NULL);
     }

     if (empty($content)) 
     {
        throw new Exception('文章内容不能为空', IMp_Error::ARTICLE_CONTENT_NOT_NULL);
    }

    date_default_timezone_set("Asia/Shanghai");
    $create_time = date('Y-m-d H:i:s', time());

    $sql = "insert into article(`title`,`content`,`userid`,`edit_time`) values (:title,:content,:userid,:edit_time)";

    echo $sql;
    //预处理
    $sm = $this->$_db->prepare($sql);

 
    //绑定参数
    $sm->bindParam(":title",$title);
    $sm->bindParam(":content",$content);
    $sm->bindParam(":userid",$userid);
    $sm->bindParam(":edit_time",$create_time);

    if (!$sm->execute()) {
        throw new Exception("文章发表失败", IMp_Error::ARTICLE_CREATE_FAIL);
    }

    return [
        'title'=>$title,
        'content'=>$content,
        'articleid'=>$this->$_db->lastInsertId(),
        'edit_time'=>$create_time,
        'userid'=>$userid
    ];


}

/**
 * 查看发表的文章 参数文章的id
 */
public function view($articleid)
{
    if (empty($articleid)) {
        throw new Exception("文章ID不可为空", IMp_Error::ARTICLEID_CANNOTNULL);
    }

    $sql = "select * from `article` where `articleid` = :articleid";

    //预处理
    $sm = $this->$_db->prepare($sql);

    //绑定参数
    $sm->bindParam(":articleid",$articleid);

    if (!$sm->execute()) {
        throw new Exception("获取文章失败", IMp_Error::ARTICLEID_GET_FAIL);
    }

    $article = $sm->fetch(PDO::FETCH_ASSOC);

    if (empty($article)) {
        throw new Exception("文章获取失败", IMp_Error::ARTICLEID_NOTEXISTS);
    }

    return $article;
}

/**
 * 修改文章
 * 参数 articleid、title、content、userid
 */
public function edit($articleid,$title,$content,$userid)
{
    $article = $this->view($articleid);

    if ($userid != (int)$article['userid']) {
        throw new Exception("您无法编辑此文章", IMp_Error::ARTICLEID_CANNOT_EDIT);
    }  
    
    $title  = empty($title)?$article['title']:$title;
    $content  = empty($content)?$article['content']:$content;


    if ($title == $article['title'] &&$content == $article['content']) {
         return $article ;
    }

    $updateSql = "update `article` set `title` = :title,`content`=:content where articleid = :articleid";

    $sm = $this->$_db->prepare($updateSql);

    $sm->bindParam(":title",$title) ;
    $sm->bindParam(":content",$content);
    $sm->bindParam(":articleid",$articleid);

    if(!$sm->execute()){
        throw new Exception("文章编辑失败", IMp_Error::ARTICLEID_EDIT_FAIL);
    }

    return [
        "title"=>$title,
        "content"=>$content,
        "articleid"=>$articleid,
        "userid"=>$userid
    ];

}

public function delete($articleid,$userid)
{
    $article = $this->view($articleid);

    if ($userid != (int)$articleid['userid']) {
        throw new Exception("您无法编辑此文章", IMp_Error::ARTICLEID_CANNOT_EDIT);
    }

        

}


public function _list()
{
     
}

}