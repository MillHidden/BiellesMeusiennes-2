<?php

namespace Core\Orm;

/**
 * Class QueryBuilder
 * @package Core\Orm
 */
Class QueryBuilder
{

    /**
     * @var
     */
    private $db;
    /**
     * @var
     */
    private $statement;
    /**
     * @var
     */
    private $attributes;
    /**
     * @var
     */
    private $table;
    /**
     * @var
     */
    private $model;
    /**
     * @var bool
     */
    private $one = false;

    /**
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * @param $table
     * @return $this
     */
    private function find($table)
    {
        $this->statement = "SELECT * FROM " . strtolower($table);
        $this->__setTableModel($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function findOne($table)
    {
        $this->find($table);
        $this->one = true;
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function findAll($table)
    {
        $this->find($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function contain($table)
    {
        $this->statement .= " LEFT JOIN " . $table . " ON (" . $this->table . ".id = " . $table . "." . substr_replace($this->table, '', -1) . "_id ) ";
        return $this;
    }

    public function save($table, $datas)
    {

        $this->statement .= 'INSERT INTO ' . strtolower($table);
        $this->statement .= ' (';
        foreach ($datas as $key => $value) {
            $this->statement .= $key . ', ';
        }
        $this->statement .= ' created ) VALUES (';

        foreach ($datas as $key => $value) {
            $this->statement .= ':' . $key . ', ';
        }
        $this->statement .= ' NOW() ) ';
        $this->__setTableModel($table);
        $this->attributes = $datas;
        $this->find_method = false;
        return $this;
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function where($attributes = [])
    {
        $this->statement .= " WHERE ";
        foreach ($attributes as $k => $v) {

            if (!is_array($v)) {
                $a = explode('.', $k);
                $a = end($a);
                $this->statement .= $k . " = :" . $a . " AND ";
            } else {
                $this->statement .= $k . " IN (";
                foreach ($v as $kk => $val) {
                    if ((count($v) -1) != $kk) {
                        $this->statement .= ":".$k.$kk.", ";
                    } else {
                        $this->statement .= ":".$k.$kk." ";
                    }
                }
                $this->statement .=  ")";
            }
        }
        if (substr($this->statement, -4) === "AND ") {
            $this->statement = str_replace("AND " , "", $this->statement);
        }
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function update($table, $conditions, $newValues)
    {
        $this->statement .= "UPDATE ".strtolower($table)." SET ";
        $lastKey = end($newValues);
        $lastKey = key($newValues);
        foreach ($newValues as $col => $val) {
            if ($lastKey != $col) {
                $this->statement .= $col." = :".$col." , ";
            } else {
                $this->statement .= $col." = :".$col." ";
            }
        }
        $this->statement .= "WHERE ";
        foreach ($conditions as $col => $val) {
            $a = explode('.', $col);
            $a = end($a);
            $this->statement .= $col . " = :" . $a . " AND ";
            if (substr($this->statement, -4) === "AND ") {
                $this->statement = str_replace("AND " , "", $this->statement);
            }
        }
        $this->attributes = array_merge($conditions, $newValues);;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->statement .= " LIMIT " . $limit;
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function delete($table)
    {
        $this->statement .= "DELETE FROM ".strtolower($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function deleteAll($table)
    {
        $this->statement .= "truncate table ".strtolower($table);
        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function orderBy($order)
    {
        $this->statement .= " ORDER BY ";
        foreach ($order as $k => $v) {
            $this->statement .= $k. " ". $v;
        }
        return $this;
    }
    /**
     * @return mixed
     */
    public function execute()
    {
        $query =  $this->db->q($this->statement, $this->attributes, $this->model, $this->one);
        $this->__reset();
        return $query;
    }

    /**
     * @param $table
     * @return $this
     */
    private function __setTableModel($table)
    {
        $this->table = strtolower($table);
        $this->model = 'App\\Models\\';
        $this->model .= ucfirst($table);
        return $this;
    }
    /**
     * @param $table
     * @return $this
     */
    private function __reset()
    {
        $this->statement ="";
        $this->attributes =[];
        $this->model= "";
        $this->one = false;
    }
}
