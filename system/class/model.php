<?php
require_once ROOT.'system/class/db.php';
/*
class Model extends DB {
    public function __construct(){
        $this->db = new DB(); // Вызов БД
        //$this->selectVisProjectUser();
        //$this->deleteVisProjectUser();
        //$this->selectVisProjectUser();
    }

    public function pre_arr($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    public function selectVisProjectUser(){
        $table_name = "visprojectuser";
        $res  = $this->select($table_name,array("iduser","idproject"));
        while($row = mysqli_fetch_assoc($res)){
            $this->pre_arr($row);
        }
    }

    public function updateVisProjectUser(){
        $table_name = "visprojectuser";
        $where = "idproject = 2";
        $res  = $this->update($table_name,array("iduser" => 12,"idproject"=>2),$where);
    }

    public function insertVisProjectUser(){
        $table_name = "visprojectuser";
        $res  = $this->insert($table_name,array("iduser" => 13,"idproject"=>3));
    }

    public function deleteVisProjectUser(){
        $table_name = "visprojectuser";
        $where = "iduser=12";
        $res  = $this->delete($table_name,$where);
    }
}*/