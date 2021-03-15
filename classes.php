<?php
class Database
{
    private $user ;
    private $host;
    private $pass ;
    private $db;
    
    public function __construct()
    {
        $this->user = "b61beb79453c91";
        $this->host = "us-cdbr-east-03.cleardb.com";
        $this->pass = "1ace52d3";
        $this->db = "heroku_bb41ce8a2314cd4";
        //onlocal we need to change values to 
        // $this->user = "root";$this->host = "localhost";$this->pass = "";$this->db = "full_stack";
    }
    public function connect()
    {
        $mysqlhost = $this->host;
        $dbname = $this->db;
        $username = $this->user;
        $password = $this->pass;
        $pdo = new PDO('mysql:host='.$mysqlhost.';dbname='.$dbname.';charset=utf8', $username, $password);
        if($pdo){
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }else{
            die("Could not create PDO connection.");
        }
    }
}

class Post
{
    function __construct()
    {
        $db = new Database();
        $sql = $db->connect();
        $this->sql = $sql;
    }
    
    public function create($user_id, $title, $body)
    {
        $stmt = $this->sql->prepare("INSERT INTO posts (user_id, title, body) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $user_id, PDO::PARAM_STR);
        $stmt->bindParam(2, $title, PDO::PARAM_STR);
        $stmt->bindParam(3, $body, PDO::PARAM_STR);
        if($stmt->execute()){
            return $this->sql->lastInsertId();
        }
    }
    
    public function searchById($post_id)
    {
        $stmt = $this->sql->prepare("SELECT * from posts where id = ?");
        $stmt->bindParam(1, $post_id, PDO::PARAM_STR);
        if($stmt->execute()){
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            return json_encode($post);
        }
    }
    
    public function checkDuplicate($title)
    {
        $stmt = $this->sql->prepare("SELECT * from posts where title = ?");
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        if($stmt->execute()){
            return $stmt->rowCount()>0 ? 1 : 0;
        }
    }
    
    public function searchByUserId($user_id)
    {
        $stmt = $this->sql->prepare("SELECT * from posts where user_id = ?");
        $stmt->bindParam(1, $user_id, PDO::PARAM_STR);
        if($stmt->execute()){
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($posts);
        }
    }
    
    public function searchByContent($string)
    {
        $stmt = $this->sql->prepare("SELECT * from posts where body LIKE ?");
        $string = "%$string%";
        $stmt->bindParam(1, $string, PDO::PARAM_STR);
        if($stmt->execute()){
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return json_encode($posts);
        }
    }
}

class User
{
    function __construct()
    {
        $db = new Database();
        $sql = $db->connect();
        $this->sql = $sql;
    }
    
    public function checkDuplicate($email)
    {
        $stmt = $this->sql->prepare("SELECT * from users where email = ?");
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        if($stmt->execute()){
            return $stmt->rowCount()>0 ? 1 : 0;
        }
    }
    
    public function create($name, $email)
    {
        $stmt = $this->sql->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bindParam(1, $name, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        if($stmt->execute()){
            return $this->sql->lastInsertId();
        }
    }
}