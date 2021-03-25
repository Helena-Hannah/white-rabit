<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class History_DataTable extends CI_Model{

 var $table = 'history';
 var $table2="file_info";
    var $column_order = array(null, 'action','path',null); //set column field database for datatable orderable
    var $column_search = array('action','name'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 
    
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
    $table2=$this->table2;
    $table=$this->table;
         
        $this->db->from($this->table)->join($table2, "$table2.id = $table.fid")->select("$table2.name,$table.*");
 
        $i = 0;
        if(!empty($_POST['search']['value']))
        {
            $search_string=strtolower($_POST['search']['value']);
            $search_string=isset($search_string[0])&&$search_string[0]=="*"?".".$search_string:$search_string;//reg exp search
            $sq = $this->db->escape($search_string);
            $this->db->where('LOWER(name) REGEXP ', $sq, false); 
        }
       
               
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables()
    {
        $this->_get_datatables_query();
        if(!empty($_POST['length'])&&$_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}
