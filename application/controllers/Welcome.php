<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){

		parent::__construct();
   
		// Load model
		$this->load->model('Fileinfo','fs');//model loaded with alias
		$this->load->model('Datatable_model','dt');
		$this->load->model('History_model','lg');
	 }
	public function index()
	{
		$this->load->view('index');
	}

	public function upload(){
		//$dirpath= './images/'.date('d-m-y');//upload path
		$dirpath= './assets/uploads';
		$config['upload_path'] =$dirpath;
		// if (!is_dir($dirpath)) {  create folder if not exist in linux we give this controller to create permission
		// 	mkdir($dirpath, 0777, TRUE);
		
		// }
		$config['allowed_types'] = 'txt|doc|docx|pdf|png|jpeg|jpg|gif';
		$config['max_size'] = 2000;
		$this->load->library('upload', $config);//load file upload helper
		if (!$this->upload->do_upload('user_file')) {
		  //$data = array('error' => $this->upload->display_errors());
		  $this->session->set_flashdata('error', $this->upload->display_errors());//set file upload error message
	  } else {
		  $data = array('image_metadata' => $this->upload->data());
		 
		  $data =[ 
			'name'     =>$this->upload->data()['file_name'],  
			'path'  =>$dirpath,  
			];  
		$fid=$this->fs->save($data);// save uploaded files info to data base
		if($fid)//last inserted file id
		{
			$this->session->set_flashdata('message', 'File uploaded successfully');//
			$this->lg->log(['action'=>"Uploaded",'fid'=>$fid,'ipaddress'=>$this->input->ip_address(),'browser'=>$this->agent->browser(),'os'=>$this->agent->platform()]);//log file operartion
		}
		
	  }
	 
	   
	  redirect('/', 'refresh');
	
	}

	public function ajax_list()
    {
        $list = $this->dt->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $file) {
            $no++;
            $row = array();
			$file_type=explode(".",$file->name);
            $row['slno'] = $no;
			$view_url=base_url(ltrim($file->path,"./")."/".$file->name);
			$view_url=file_exists(FCPATH.ltrim($file->path,"./")."/".$file->name)?"<a target='_blank' href='$view_url'>$file->name</a>":$file->name." ( hardcopy file missing)";//if file exist in our system generate view link
            $row['name'] = $view_url;
            $file_url=isset($file_type[1])?base_url('assets/icons/'.$file_type[1].".png"):base_url('assets/icons/file.png');
			$file_url=file_exists(FCPATH."assets/icons/".$file_type[1].".png")?"<img height=\"50px\" src='$file_url'>":"";
			$row['type'] =$file_url;
            $row['created'] = $file->created_at;
           	$id= $this->encrypt($file->id);//basic encryption for security
			   $link=base_url("delete")."/$id";
            $row['option'] ="<a onclick=\"return confirm('Are You sure to delete this file')\" href='$link'><i class=\"far fa-trash-alt delete\"></i></a>";
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dt->count_all(),
                        "recordsFiltered" => $this->dt->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

	function delete($id=""){

	
		$decrypted_fid=$this->decrypt($id);//decode encrypted id
		$file_info=$this->fs->get_file_info($decrypted_fid); //get file info by id
		
		if($file_info)//check file exists
		{
			if($this->fs->delete($decrypted_fid))//perform soft delete
			{
				$this->session->set_flashdata('message', " $file_info->name deleted successfully");//
				$this->lg->log(['action'=>"Delete",'fid'=>$decrypted_fid,'ipaddress'=>$this->input->ip_address(),'browser'=>$this->agent->browser(),'os'=>$this->agent->platform()]);
				/*
				file remove from storage
				$file_path=FCPATH.ltrim($file_info->path,"./")."/".$file_info->name;
				if(file_exists($file_path)){
					unlink($file_path);
				}
				*/
		}
		else{
			$this->session->set_flashdata('error', 'File not found');//set success message
		}
		}
		else{
			$this->session->set_flashdata('error', 'File not found');//set error msg
		}
			
		redirect('/', 'refresh');
	}
	protected function encrypt($str){//encrypt base 64
		return $output = strtr(base64_encode($str),
		array(
			'+' => '.',
			'=' => '-',
			'/' => '~'
		)
	);
	}
	protected function decrypt($str){//decrypt base 64
		return base64_decode(strtr(
			$str,
			array(
				'.' => '+',
				'-' => '=',
				'~' => '/'
			)
		));

	}

function delete_history(){
	$this->load->model('History_DataTable','hd');//alias hd=>History_DataTable
	$list = $this->hd->get_datatables();//get file history from model
	$data = array();
	$no = $_POST['start'];
	foreach ($list as $file) {
		$no++;
		$row = array();
		$file_type=explode(".",$file->name);
		$row['slno'] = $no;
		$row['name'] = $file->name;
		$row['action'] = $file->action;
		$file_url=isset($file_type[1])?base_url('assets/icons/'.$file_type[1].".png"):base_url('assets/icons/file.png');
		$file_url=isset($file_type[1])&&file_exists(FCPATH."assets/icons/".$file_type[1].".png")?"<img height=\"50px\" src='$file_url'>":"";//map file type to img icon
		$row['type'] =$file_url;
		$row['ip'] = $file->ipaddress;
		$os=file_exists(FCPATH."assets/icons/".strtolower($file->os).".png")?"<img height='50px' src='".base_url('assets/icons/'.strtolower($file->os).'.png')."'>":$file->os;//map os name to image if file exists
		$row['os'] = $os;
		$browser=file_exists(FCPATH."assets/icons/".strtolower($file->browser).".png")?"<img height='50px' src='".base_url('assets/icons/'.strtolower($file->browser).'.png')."'>":$file->browser;//map browser to image if file exist
		$row['browser'] = $browser;
		$row['created'] = $file->created_at;
		 
		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->hd->count_all(),
					"recordsFiltered" => $this->hd->count_filtered(),
					"data" => $data,
			);
	//output to json format
	echo json_encode($output);

}
}
