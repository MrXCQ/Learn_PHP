<?php

require_once __DIR__.'/Error.php';


class User
{
    // 数据库连接对象 PDO
    private $_db;

    public function __construct(PDO $_db1)
    {
        $this->$_db = $_db1;
    }

    // 用户注册
    public function register($username, $userpwd)
    {
        // echo '注册的用户名称:'.$username.'<br>';

        if (empty($username)) {
            throw new Exception('用户名不能为空', IMp_Error::USERNAME_CANNOT_NULL);
        }
        if (empty($userpwd)) {
            throw new Exception('用户密码不能为空', IMp_Error::USERPASS_CANNOT_NULL);
        }

        if ($this->_isUsernameExists($username)) {
            throw new Exception('用户名已存在', IMp_Error::USERNAME_EXISTS);
        }

        date_default_timezone_set("Asia/Shanghai");
        $create_time = date('Y-m-d H:i:s', time());

        $resultArr =  $this->addUser($username, $userpwd, $create_time);

        return $resultArr;
    }


    // 用户登录
    public function Login($username, $userpwd)
    {
        if (empty($username)) {
            throw new Exception('用户名不能为空', IMp_Error::USERNAME_CANNOT_NULL);
        }

        if (empty($userpwd)) {
            throw new Exception('用户密码不能为空', IMp_Error::USERPASS_CANNOT_NULL);
        }

        $sql = "select * from `user` where `username` = :username and `userpassword` = :userpwd";

        // 预处理
        $sm = $this->$_db1->prepare($sql);

        //密码Md5 加密
        $userpwd = $this-> _md5($userpwd) ;

        // 绑定参数
        $sm->bindParam(':username', $username) ;
        $sm->bindParam(':userpwd', $userpwd) ;

        //echo "<br> 登录： ";

        // $sm->debugDumpParams();

        if (!$sm->execute()) {
            throw new Exception("登录失败！！", IMp_Error::LOGIN_FAIL);
        }

        $result = $sm->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new Exception("用户名或者密码错误", IMp_Error::LOGIN_USERNAME_OR_PWDERROR);
        }


        return $result;
    }



    private function addUser($username, $password, $register_date)
    {
        $sql = "insert into user(`username`,`userpassword`,`register_date`) values(:username,:password,:register_date)";

        // 预处理
        $sm  = $this->$_db->prepare($sql);

        // 密码加密
        $password = $this -> _md5($password);


        //绑定参数
        $sm->bindParam(':username', $username);
        $sm->bindParam(':password', $password);
        $sm->bindParam(':register_date', $register_date);
        
        // $sm->debugDumpParams();

        if (!$sm->execute()) {
            throw new Exception("注册失败", IMp_Error::REGISTER_FAIL);
        }

        return [
            'username'=>$username,
            'user_id'=>$this->$_db->lastInsertId(),
            'addtime'=> $register_date,
        ];
    }

    // 判断用户名是否已经存在
    private function _isUsernameExists($username)
    {
        $sql = "select * from user where username = :username";

        // 预处理
        $sm  = $this->$_db->prepare($sql);

        //绑定参数
        $sm->bindParam(':username', $username);

        $sm->execute();

        // $sm->debugDumpParams();

        $result = $sm->fetch(PDO::FETCH_ASSOC);

        return !empty($result);
    }


    // MD5 加密
    private function _md5($password)
    {
        return md5($password.SALT);
    }
}
