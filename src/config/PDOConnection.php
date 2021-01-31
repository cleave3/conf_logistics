<?php

namespace App\config;

class PDOConnection
{
    protected $conn;
    private $dbhost;
    private $dbname;
    private $dbuser;
    private $dbpassword;
    private $dbdriver;

    public function __construct()
    {
        try {
            $this->dbhost = getenv("DB_HOST");
            $this->dbname = getenv("DB_NAME");
            $this->dbuser = getenv("DB_USER");
            $this->dbpassword = getenv("DB_PASSWORD");
            $this->dbdriver = getenv("DB_TYPE");

            $driver = "{$this->dbdriver}:dbname={$this->dbname};host={$this->dbhost}";

            $options  = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            );

            $this->conn = new \PDO($driver, $this->dbuser, $this->dbpassword, $options);
            return $this->conn;
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    public function __destruct()
    {

        $this->conn = null;
    }
}
