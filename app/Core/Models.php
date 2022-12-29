<?php

namespace App\Core;

use PDO;

abstract class Models
{
    protected $db;
    protected $table;

    function __construct() {
        // this connction if use MySQL
        $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

        // this connectin if use SQLite
        // if (file_exists(APP_ROOT.'/database/database.sqlite')) {
        //     $this->db = new PDO('sqlite:'.APP_ROOT.'/database/database.sqlite');
        // } else {
        //     echo 'Connection error';
        // }
    }

    //get record by $field filed
    function get($field, $value, $table = null)
    {
        if ($table) {
            $pr = $this->db->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = ?');
        } else {
            $pr = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = ?');
        }

        $pr->execute([$value]);
        $data = $pr->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    //filter records by filters
    function filter(array $filers = [], $table = null)
    {
        $table_name = isset($table) ? $table : $this->table;

        if (empty($filers)) {
            $pr = $this->db->query('SELECT * FROM ' . $table_name);
        } else {
            $query = 'SELECT * FROM ' . $table_name . ' WHERE ';
            foreach($filers as $key => $value) {
                if (gettype($value) == 'string') $value = "'$value'";
                $query .= $key . '=' . $value . ' AND ';
            }
            $query = substr($query, 0, -5);
            $pr = $this->db->query($query);
        }

        return $pr->fetchAll(PDO::FETCH_ASSOC);
    }

    //create record with array $data
    function create(array $data)
    {
        $query = 'INSERT INTO ' . $this->table . ' (';
        foreach(array_keys($data) as $key) {
            $query .= $key . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ') VALUES (';

        foreach($data as $value) {
            if (gettype($value) == 'string') $value = "'$value'";
            $query .= $value . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ')';

        $this->db->query($query);
    }

    //update record by id field
    function update(int $id, array $data)
    {
        $query = 'UPDATE ' . $this->table . ' SET ';
        foreach($data as $field => $value) {
            if (gettype($value) == 'string') $value = "'$value'";
            $query .= $field . ' = ' . $value . ', ';
        }
        $query = substr($query, 0, -2);
        $query .= ' WHERE id = ' . $id;

        $this->db->query($query);
    }

    //delete record by id filed
    function delete($field, $value) {
        if (gettype($value) == 'string') $value = "'$value'";

        $query = "DELETE FROM $this->table WHERE $field = $value";
        $this->db->query($query);
    }

    // execute row sql query
    function raw(string $sql_quary, $many = false)
    {
        $pr = $this->db->query($sql_quary);

        if ($many) {
            return $pr->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $pr->fetch(PDO::FETCH_ASSOC);
        }
    }
}
