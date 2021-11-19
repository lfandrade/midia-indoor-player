<?php
class DB_CONNECT
{
    private $con;

    function __construct() {
        
        $this->connect();
    }

    function __destruct() {
        $this->close();
    }

    function connect()
    {
        if ($this->con) {
            return NULL;
        }

        require_once __DIR__ . '/constants.php';
        $this->con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_error());
        $this->con->set_charset("utf8");
        
        
    }

    function getConnection()
    {
        return $this->con;
    }

    function close()
    {
        mysqli_close($this->con);
    }
}