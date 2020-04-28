<?php
require_once __DIR__.'/Article.php';
require_once __DIR__.'/Error.php';
require_once __DIR__.'/User.php';




class Rest
{
    private $_user;

    private $_article ;

    // 请求方法
    private $_requestMethod ;
    // 请求资源
    private $_requestResource ;
    // 允许请求的资源 限制接口路径
    private $_allowResource = ['users','articles','day9'];
    // 允许请求的方法
    private $_allowMethod = ['GET','POST','PUT'];

    // 版本号
    private $_version ;
    // 资源标识
    private $_requestUrl ;

    //HTTP 请求的状态码和请求信息
    private $_statusCode = [
        200 => 'OK',
        204 => 'No Content',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not allowed',
        500 => 'Server Internal Error'
    ];

    public function __construct(User $_user, Article $_article)
    {
        $this->_user = $_user;
        $this->_article = $_article;
    }

    public function run()
    {
        try {
            $this->setMethod();
            $this->setResource();

            if ($this->_requestResource == 'users') {
                $this->sendUsers();
            } else {
                $this->sendArticles();
            }
        } catch (Exception $e) {
            // 捕获异常通过json 输出
            $this->_json($e->getCode(), $e->getMessage());
        }
    }

    private function setMethod()
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];

        if (!in_array($this->_requestMethod, $this->_allowMethod)) {
            throw new Exception('请求方法不被允许', 405);
        }
    }


    private function setResource()
    {
        $path = $_SERVER['PATH_INFO'];

        $params = explode('/', $path);

        $this->_requestResource = $params[1];

        if (!empty($params[2])) {
            $this->_requestUrl = $params[2];
        }

  
        if (!in_array($this->_requestResource, $this->_allowResource)) {
            throw new Exception('请求资源不被允许', 405);
        }
    }


    // 处理用户的逻辑
    private function sendUsers()
    {
        if ($this->_requestMethod != "POST") {
            throw new Exception("请求方法不被允许", 405);
        }

        if (empty($this->_requestUrl)) {
            throw new Exception("请求参数缺失", 400);
        }

        if ($this->_requestUrl == "login") {
            $this -> doLogin();
        } elseif ($this ->_requestUrl == "register") {
            $this->doregister();
        } else {
            throw new Exception("请求资源不被允许", 405);
        }
    }

    /**
     * 用户注册
     *
     */
    private function doregister()
    {
        $data = $this->getBody();

        if (empty($data['name'])) {
            throw new Exception("用户名不能为空", 400);
        }

        if (empty($data['password'])) {
            throw new Exception("用户密码不能为空", 400);
        }

        $user =  $this->_user->register($data['name'], $data['password']);

        if ($user) {
            $this->_json(200, "注册成功");
        }
    }


    private function getBody()
    {
        $data = file_get_contents("php://input");

        if (empty($data)) {
            throw new Exception("请求参数不能为空", 400);
        }

        return json_decode($data, true);
    }

    private function doLogin()
    {
        $data = $this->getBody();

        if (empty($data['name'])) {
            throw new Exception("用户名不能为空", 400);
        }

        if (empty($data['password'])) {
            throw new Exception("用户密码不能为空", 400);
        }

        $user = $this->_user->login($data['name'], $data['password']);

        // var_dump($user);

        date_default_timezone_set("Asia/Shanghai");
        $create_time = date('Y-m-d H:i:s', time());

        session_start();

        $data = [
            'data' => [
                'userid' => $user['userid'],
                'name' =>$user['username'],
                'token' => session_id(),
                'login_time' => $create_time,
             ],
             'message' => '用户登录成功',
             'code' => 200
        ];

        echo json_encode($data);
    }


  

    // 处理文章的逻辑
    private function sendArticles()
    {
        if (empty($this->_requestUrl)) {
            throw new Exception("请求参数缺失", 400);
        }

        switch ($this->_requestUrl) {
            case 'view':
                 $this->viewArticle();
                break;
            case 'add':
                 $this->addArticle();
                break;
            case 'edit':
                 $this->editArticle();
                break;
            case 'delete':
                 $this->deleteArticle();
                break;
            case 'list':
                 $this->listArticle();
                break;
            
            default:

            throw new Exception("请求资源不被允许", 405);
                break;
        }
    }


    //新增一篇文章
    private function addArticle()
    {
        $data = $this->getBody();
        if (empty($data['title'])) {
            throw new Exception("文章标题不能为空", 400);
        }

        if (empty($data['content'])) {
            throw new Exception("文章内容不能为空", 400);
        }

        if (!$this->isLogin($data['token'])) {
            throw new Exception("请重新登录", 400);
        }

        $article = $this->_article->create($data['title'],$data['content'],10);

        if ($article) {
             $this->_json(200,"文章发表成功！");
        }
    }

    // 文章修改的api
    private function editArticle()
    {
        $data = $this->getBody();

        if (!$this->isLogin($data['token'])) {
            throw new Exception("请重新登录", 400);
        }

        // $article = $this->_article->view($data['arid']);
        // if ($article['user_id'] != 3/* $_SESSION['userInfo']['id] */) {
        //     throw new Exception("请重新登录", 403);
        // }
        $article = $this->_article->view($data['articleid']);

        // if ($article['userid'] != 10) {
        //     throw new Exception("请重新登录", 400);
        // }


        $this->_article->edit($data["articleid"],$data['title'],$data['content'],$data['userid']);

        if ($article) {
             $this->_json(200,"文章编辑成功!");
        }
    }


    /**
     * 判断用户是否登录
     */
    private function isLogin($token){

        session_start();

        $sessionID = session_id();

        if ($sessionID != $token) {
            return false ; 
        }

        return true;
    }

    // 获取一片文字的api
    private function viewArticle()
    {
        $data = $this->getBody();
        if (empty($data['articleid'])) {
             throw new Exception("文字的id不能为空", 400);
        }

        $article = $this->_article->view($data['articleid']);

        if (!$article) {
            throw new Exception("暂无此文章", 404);
        }

        $data = [
            'data' => [
                'articleid' => $article['articleid'],
                'title' =>$article['title'],
                'edit_time' => $article['edit_time'],
                'content' => $article['content'],
                'userid' => $article['userid']
             ],
             'message' => 'success',
             'code' => 200
        ];

        echo json_encode($data);
    }

    private function deleteArticle()
    {
        $data = $this->getBody();
        if (empty($data['articleid'])) {
             throw new Exception("文字的id不能为空", 400);
        }

        if (empty($data['userid'])) {
            throw new Exception("用户id不能为空", 400);
       }


       $article = $this->_article->delete($data['articleid'],$data['userid']);

       if ($article) {

        $this->_json(200,"文章删除成功！");

       }
    }


    private function listArticle()
    {
        $data = $this->getBody();
        if (empty($data)) {
             throw new Exception("请输入查询的启动和数量", 400);
        }
            // var_dump($data);
        $article  = $this->_article->alist((int)$data['startIndex'],(int)$data['datanum']);

        if (!$article) {
            throw new Exception("暂无文章", 400);
        }

        $data = [
            'data' => $article,
            'message' => 'success',
            'code' => 200,
            "start"=> $data['startIndex'],
            "article_num"=> $data['datanum']
        ];

        echo json_encode($data);
    }
    
    public function _json($code, $message)
    {
        if ($code !== 200 && $code > 200 && $code < 600) {
            header("HTTP/1.1 ".$code." ".$this->_statusCode[$code]);
        }

        header("Content-type:application/json;charset=utf8");

        if (!empty($message)) {
            echo json_encode(['code'=>$code,'message'=>$message]);
        }
        die;
    }
}
