<?php

namespace App\Core;

use PDO;

abstract class Models
{
    protected $db;
    protected $table;

    function __construct() {
        if (file_exists(APP_ROOT.'/database/database.sqlite')) {
            $this->db = new PDO('sqlite:'.APP_ROOT.'/database/database.sqlite');
        } else {
            echo 'Connection error';
        }
    }

    //get record by $field filed
    function get($field, $value)
    {
        $pr = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = ?');
        $pr->execute([$value]);
        $data = $pr->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    //filter records by filters
    function filter(array $filers)
    {
        if (empty($filers)) {
            $pr = $this->db->query('SELECT * FROM ' . $this->table);
        } else {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE ';
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
}
