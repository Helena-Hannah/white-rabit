<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fileinfo extends CI_Model {
  var $table_name = "file_info";
  /*
    Table Name:file_info
----------------------------------------------------------------
Field | Type         |  Null |  Key  | Default | Extra          |
----------------------------------------------------------------
id    |int(11)       |NO     |PRI     |NULL    | auto_increment |
---------------------------------------------------------------
name  | varchar(100) |NO     |        |NULL    |                |
----------------------------------------------------------------
path  | varchar(255) |NO     |        |NULL    |                |
----------------------------------------------------------------
is_deleted |tinyint(1)|NO    |        | 0      |                |
-----------------------------------------------------------------
created_at |timestamp| NO    |        | current_timestamp()|    |
----------------------------------------------------------------
deleted_at | timestamp| YES  |        | NULL   |                |
----------------------------------------------------------------
  */
  public function __construct()
    {
        parent::__construct();
       }
function get_file_info($fid){//get file info by id

  $query = $this->db->where("id",$fid)->get($this->table_name);
  if ($query->num_rows() > 0)
  {
      $row = $query->row(); 
      return $row;
  }
  return null;
}
  function save($data=[]){
 
     if($this->db->insert($this->table_name,$data)){

      return $this->db->insert_id();
    }  
    return false;

  }


  function delete($id){//soft delete 

    $this->db->where('id',$id);
        return $this->db->update($this->table_name, ['is_deleted'=>1]);
  }

}
