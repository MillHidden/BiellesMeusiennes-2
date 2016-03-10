<?php

namespace Core\Orm;

use \PDO;

/**
 * Class Database
 * @package Core\Orm
 */
Class Database {

    /**
     * @var string
     */
    private $db_name = "bielles";
    /**
     * @var string
     */
    private $db_user = "root";
    /**
     * @var string
     */
    private $db_pass = "";
    /**
     * @var string
     */
    private $db_host = "localhost";
    /**
     * @var
     */
    private $pdo;

    /**
     * @param $db_name
     * @param string $db_user
     * @param string $db_pass
     * @param string $db_host
     */
    public function __construct($db_name, $db_user="root", $db_pass="", $db_host="localhost"){
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    /**
     * @return PDO
     */
    private function getPDO() {

        if ( $this->pdo === null ) {
            $pdo = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name, $this->db_user, $this->db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec('SET NAMES utf8');
            $this->pdo = $pdo;
        }
        return $this->pdo;

    }

    /**
     * @param $statement
     * @param $attributes
     * @param $class_name
     * @param bool|false $first
     * @return array|mixed
     */
    public function q($statement, $attributes, $class_name, $first = false) {
        $req = $this->getPDO()->prepare($statement);
        if ( $attributes ) {
            $at = [];
            foreach($attributes as $attr => $val) {
                $a = explode('.', $attr);
                $a = end($a);
                $at[$a] = $val;
                if (is_array($val)){
                    $at = [];
                    foreach ($val as $k => $v) {
                        $at[$a.$k] = $v;
                    }
                }
            }
            $attributes = $at;
        }
        try {
            $req->execute($attributes);
        } catch (\PDOException $e) {
            return false;
        }
        switch (substr($statement, 0 , 6)) {
            case "INSERT":
                return intval($this->getPDO()->lastInsertId());
                break;
            case "DELETE":
                return true;
                break;
            case "SELECT":
                $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
                if ( $first ) {
                    $datas = $req->fetch();
                } else {
                    $datas = $req->fetchAll();
                }
                return $datas;
                break;
            case "trunca":
                return true;
                break;
            case "UPDATE":
                return true;
                break;
        }
    }
}
