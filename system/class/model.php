<?php
require_once ROOT.'system/class/db.php';
/*
class Model extends DB {
    public static function __construct(){
        $this->db = new DB(); // Вызов БД
        //$this->selectVisProjectUser();
        //$this->deleteVisProjectUser();
        //$this->selectVisProjectUser();
    }

    public static function pre_arr($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public static function selectVisProjectUser(){
        $table_name = "visprojectuser";
        $res  = $this->select($table_name,array("iduser","idproject"));
        while($row = mysqli_fetch_assoc($res)){
            $this->pre_arr($row);
        }
    }

    public static function updateVisProjectUser(){
        $table_name = "visprojectuser";
        $where = "idproject = 2";
        $res  = $this->update($table_name,array("iduser" => 12,"idproject"=>2),$where);
    }

    public static function insertVisProjectUser(){
        $table_name = "visprojectuser";
        $res  = $this->insert($table_name,array("iduser" => 13,"idproject"=>3));
    }

    public static function deleteVisProjectUser(){
        $table_name = "visprojectuser";
        $where = "iduser=12";
        $res  = $this->delete($table_name,$where);
    }
}*/