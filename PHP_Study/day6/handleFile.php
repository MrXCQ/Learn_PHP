<?php

$upload = new Upload();
$uploadFilePath =  $upload->uploadFile("file");

echo "上传文件的路径: ". $uploadFilePath ."<br>";

var_dump($upload -> errorNo);
var_dump($upload -> errorInfo);

//以上传图片为例
// 成员属性有 
// 需要初始化的成员
// 1.文件上传的路径
// 2.允许上传后缀
// 3.允许上传的mime类型
// 4.允许上传的文件大小
// 5.是否启用随机名字
// 6.加上文件的前缀

// 自定义的错误号码和错误信息，要保存的文件信息
// 1.文件名
// 2.文件后缀
// 3.文件大小
// 4.文件mime
// 5.文件临时路径

// 文件的新名字
// 对外公开的方法

// uploadFile()；上传成功过返回文件路径

class Upload
{
protected $path = "./upload/";
protected $allowSuffix = ["jpg","png","jpeg","gif"];
protected $allowMime = ["image/jpeg","image/png","image/gif"];
protected $maxSize = 2000000;
protected $isRandName = true ;
protected $prefix = "IMp_";

//错误号码和错误信息
protected $errorNo;
protected $errorInfo;


//文件的信息
protected $oldName,$suffix,$size,$mime,$tmpName;

//文件的新名字
protected $newName; 

public function __construct($arr = [])
{
     foreach ($arr as $key => $value) {
          $this->setOption($key,$value);
     }
}

//判断这个$key 是不是我的成员属性 如果是则设置
protected function setOption($key,$value){
    //得到的所有的成员属性
    $keys = array_keys(get_class_vars(__CLASS__));

    echo $key. $value."<br>";
    //如果$key 是我的成员属性 那么设置
    if (in_array($key,$keys)) {
         $this->$key = $value ; 
    }
}


// 文件上传函数
// $key 就是你 input 框的name 属性值 

public function uploadFile($key)
{
    
    //  判断有没有设置路径 path
    if (empty($this->path)) {
         $this->setOption("errorNo",-1);
         return false ;
    }

    //  判断该路径是否存在、是否可写
    if (!$this->check()) {
        $this->setOption("errorNo",-2);
        return false ;
    }

    //  判断$_FILES 里面的error 信息是否为0 如果为0说明文件信息在服务器可以直接获取，提取信息保存在成员属性中
    
    $error = $_FILES[$key]["error"] ;

    if ($error) {
         $this->setOption("errorNo",$error);
    }else{
        // 提取文件相关信息 并且保存到成员属性中
        $this->getFileInfo($key);
    }
    
    
    //  判断文件的大小、mime、后缀是否符合

    if (!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()) {
        return false ;
    }

    //  得到新的文件名字
    $this->newName = $this->createNewName();

    //  判断是否是上传文件 并且移动上传文件
    if (is_uploaded_file($this->tmpName)) {
         //移动文件
         if (move_uploaded_file($this->tmpName,$this->path.$this->newName)) {

            return $this->path.$this->newName ;

         }else{
            $this ->setOption("errorNo",-7);
            return false ; 
        }

    }else{
        $this ->setOption("errorNo",-6);
        return false ; 
    }

}

protected function createNewName(){

    if ($this -> isRandName) {
        $name = $this->prefix.uniqid().".".$this->suffix;
    }else{
        $name = $this->prefix.$this->oldName;
    }

    return $name;
}


protected function check(){

    // 文件夹 不存在或者不是目录。创建文件夹
    if (!file_exists($this->path) || !is_dir($this->path)) {
        return mkdir($this->path,0777,true);
    }

    // 判断文件是否可写
    if (!is_writeable($this->path)) {
        return chmod($this->path,0777);   
    }

    return true;
}


protected function getFileInfo($key)
{
    // 得到文件名字
     $this->oldName = $_FILES[$key]["name"];
    // 得到文件的mime 类型
     $this->mime = $_FILES[$key]["type"];
     // 得到文件的size大小
     $this->size = $_FILES[$key]["size"];
     // 得到文件的临时路径
     $this->tmpName = $_FILES[$key]["tmp_name"];
     // 得到文件的后缀
     $this->suffix = pathinfo($this->oldName)["extension"];
}

protected function checkSize(){
    if ($this->size > $this->maxSize) {
         $this->setOption("errorNo",-3);
         return false;
    }

    return true ;
}

protected function checkMime(){

    if (!in_array($this->mime,$this->allowMime)) {
         $this->setOption("errorNo",-4);
         return false;
    }
    return true;
}


protected function checkSuffix(){

    if (!in_array($this->suffix,$this->allowSuffix)) {
         $this->setOption("errorNo",-5);
         return false;
    }
    return true;
}

public function __get($name){

    if ($name == "errorNo") {
        return $this->errorNo;
    }else if ($name == "errorInfo") {
        return $this->getErrorInfo();
    }
}

protected function getErrorInfo(){

    switch ($this->errorNo) {
        case -1:
            $str = "文件路径没有设置";
            break;
        case -2:
            $str = "文件路径不是一个目录或没有权限";
            break;
        case -3:
            $str = "文件大小超过最大值";
            break;
        case -4:
            $str = "文件mime不符合";
            break;
        case -5:
            $str = "文件后缀不符合";
            break;
        case -6:
            $str = "不是上传路径";
            break;
        case -6:
            $str = "文件移动失败";
            break;
        case 1:
            $str = "文件超出php.ini 设置大小";
            break;
        case 2:
            $str = "文件超出html设置大小";
            break;
        case 3:
            $str = "文件部分上传";
            break;
        case 4:
            $str = "没有文件上传";
            break;
        
        case 6:
            $str = "找不到临时文件";
            break;
        case 7:
            $str = "文件写入失败";
            break;            
        default:
            # code...
            break;
        }

        return $str;

    }
}
?>
