<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  /*
    Table Name:history
----------------------------------------------------------------
Field | Type         |  Null |  Key  | Default | Extra          |
----------------------------------------------------------------
id    |int(11)       |NO     |PRI     |NULL    | auto_increment |
---------------------------------------------------------------
fid  | varchar(100) |NO     |    MUL    |NULL    |                |
----------------------------------------------------------------
action  | varchar(255) |NO     |        |NULL    |                |
-----------------------------------------------------------------
created_at |timestamp| NO    |        | current_timestamp()|    |
----------------------------------------------------------------
  */
class History_model extends CI_Model{

var $table_name="history";
public function __construct()
    {
  
        parent::__construct();
       }

function log($data=[]){

return $this->db->insert($this->table_name,$data); 
}
}
