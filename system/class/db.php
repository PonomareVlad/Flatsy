<?php

define('MYSQL_CONNECTION',mysql_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD)); // Подключение к БД
mysql_select_db('tm'); // Выбор БД

class DB {

    public function __construct(){

    }

    protected function select($table_name, $fields, $where = "", $order = "", $up = true, $limit = ""){
        for ($i = 0; $i < count ($fields); $i++){
            if ((strpos($fields[$i], "(") === false) && ($fields[$i] != "*")) $fields[$i] = "`".$fields[$i]."`";
        }
        $fields = implode(",", $fields);
        if (!$order) $order = "";
        else {
            if ($order != "RAND()"){
                $order = "ORDER BY `$order`";
                if (!$up) $order .= " DESC";
            }
            else $order = "ORDER BY $order";
        }
        if ($limit) $limit = "LIMIT $limit";
        if ($where) $query = "SELECT $fields FROM $table_name WHERE $where $order $limit";
        else $query = "SELECT $fields FROM $table_name $order $limit";
        $res = mysql_query($query) or die(mysql_error());
        return $res;
    }

    protected function update ($table_name, $upd_fields, $where){
        $query = "UPDATE $table_name SET ";
        foreach ($upd_fields as $field => $value) $query .= "`$field` = '".addslashes($value)."',";
        $query = substr($query, 0, -1);
        if ($where){
            $query .= " WHERE $where";
            $res = mysql_query($query) or die(mysql_error());
            return $res;
        }
        else return false;
    }

    protected function insert ($table_name, $new_value){
        $table_name = $table_name;
        $query = "INSERT INTO $table_name (";
        foreach ($new_value as $field => $value) $query .= "`".$field."`,";
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";
        foreach ($new_value as $value) $query .= "'".addslashes($value)."',";
        $query = substr($query, 0, -1);
        $query .= ")";
        $res = mysql_query($query) or die(mysql_error());
        return $res;
    }



    protected function delete ($table_name, $where = ""){
        if ($where){
            $query = "DELETE FROM $table_name WHERE $where";
            $res = mysql_query($query) or die(mysql_error());
            return $res;
        }
        else return false;
    }
}