<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Datatable_model extends CI_Model{

 var $table = 'file_info';
    var $column_order = array(null, 'name','path',null); //set column field database for datatable orderable
    var $column_search = array('name'); //set column field database for datatable searchable 
    var $order = array('id' => 'desc'); // default order 
    
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query()
    {
         
        $this->db->from($this->table)->where("is_deleted",0);
 
        $i = 0;
        if(!empty($_POST['search']['value']))
        {
            $search_string=strtolower($_POST['search']['value']);
            $search_string=isset($search_string[0])&&$search_string[0]=="*"?".".$search_string:$search_string;
            $sq = $this->db->escape($search_string);
            $this->db->where('LOWER(name) REGEXP ', $sq, false); 
        }
        
        // foreach ($this->column_search as $item) // loop column 
        // {
        //     if(!empty($_POST['search']['value'])) // if datatable send POST for search
        //     {
              
                 
        //         if($i===0) // first loop
        //         {
        //             $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
        //             $this->db->like($item, $_POST['search']['value']);
        //         }
        //         else
        //         {
        //             $this->db->or_like($item, $_POST['search']['value']);
        //         }
 
        //         if(count($this->column_search) - 1 == $i) //last loop
        //             $this->db->group_end(); //close bracket
        //     }
        //     $i++;
        // }
         
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
        $this->db->from($this->table)->where("is_deleted",0);
        return $this->db->count_all_results();
    }

}
