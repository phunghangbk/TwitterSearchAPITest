<?php
class DatabasePDO {
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;
    private $port;
    private $Debug;
    private $socket;
    function __construct($params = array()) {
        $this->conn = false;
        $this->host = empty($params['host']) ? getenv('DB_HOST') : $params['host'];
        $this->user = empty($params['username']) ? getenv('DB_USERNAME') : $params['username']; //username
        $this->password = empty($params['password']) ? getenv('DB_PASSWORD') : $params['password']; //password
        $this->baseName = empty($params['dbname']) ? getenv('DB_DATABASE') : $params['dbname']; //name of your database
        $this->port = empty($params['port']) ? getenv('DB_PORT') : $params['port'];
        $this->socket = empty($params['socket']) ? getenv('DB_SOCKET') : $params['socket'];
        $this->debug = true;
        $this->connect();
    }
 
    function __destruct() {
        $this->disconnect();
    }
    
    function connect() {
        if (!$this->conn) {
            try {
                if (! empty($this->socket)) {
                    $q = 'mysql:unix_socket=' . $this->socket . ';host='.$this->host . ';dbname=' . $this->baseName. '';
                } else {
                    $q = 'mysql:host='.$this->host.';dbname='.$this->baseName.'';
                }

                $this->conn = new PDO($q, $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));  
            }
            catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
 
            if (!$this->conn) {
                $this->status_fatal = true;
                echo 'Connection BDD failed';
                die();
            } 
            else {
                $this->status_fatal = false;
            }
        }
 
        return $this->conn;
    }
 
    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }
    
    function getOne($query) {
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$query;
           die();
        }
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $reponse = $result->fetch();
        
        return $reponse;
    }
    
    function getAll($query) {
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$query;
           die();
        }
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $reponse = $result->fetchAll();
        
        return $reponse;
    }
    
    function execute($query) {
        if (!$response = $this->conn->exec($query)) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: '.$query;
            die();
        }

        return $response;
    }
}