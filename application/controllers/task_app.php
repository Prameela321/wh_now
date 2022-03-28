<?php 

class Task_app extends CI_Controller{
	private $data = [];
	private $list_per_page = 2;
	
	public function __construct()
	{
		 parent::__construct();
		 
 		$this->load->model('taskapp_m');
 		$this->load->library(array('form_validation','session'));
 		// database library autoloaded

	}

	public function index(){

			$this->load->view('user_view.php');
	}
	public function register(){

		if($this->input->post())
		{
			
			$this->form_validation->set_rules('phone','phone','trim|required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('password','password','required');

			
			$phone = $this->input->post('phone');
			$password = $this->input->post('password');

			$register_arr = array(
				'user_name' => $phone,
				'password' => $password);

			// echo '<pre>';
			// print_r($register_arr);exit;
			if($this->form_validation->run() == TRUE)
			{
				$exist_user = $this->taskapp_m->check_user($phone);
				if(!$exist_user)
				{
					if($this->taskapp_m->insert_data('users',$register_arr))
					{
						$this->session->set_flashdata('register','<div>Registered Successfully</div>');
						// $this->load->view('login.php');
						header('Location:'.base_url().'task_app/login');
					}
			   }
			   else
			   {
			   		$this->session->set_flashdata('phone_duplicate','<div>User is already Registered with Email</div');
			   		$this->load->view('register.php');
			   }
		   }
			// $this->
			
		}
		else
		$this->load->view('register.php');
	}
	public function login()
	{
		if($this->input->post())
		{
			
			$this->form_validation->set_rules('phone','phone','trim|required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('password','password','required');
				
			$phone = $this->input->post('phone');
			$password = $this->input->post('password');

			$login_arr = array(
				'user_name' => $phone,
				'password' => $password);

			// echo '<pre>';
			// print_r($register_arr);exit;
			if($this->form_validation->run() == TRUE)
			{
				if($user_detail = $this->taskapp_m->user_info($login_arr))
				{
					$this->session->set_flashdata('login','<div>Hi '.$user_detail['user_name'].'</div>');
					
					// $this->data['post_details'] = $this->taskapp_m->get_user_post();
					// $this->load->view('post_view.php',$this->data);
					header('Location:'.base_url().'task_app/view_post');
				}
				else
				{
					// echo 'siodji';exit;
					$this->session->set_flashdata('login_error','<div>Email or Password Invalid</div');
					$this->load->view('login.php');

				}
		   }
			// $this->
			
		}
		else
		$this->load->view('login.php');
	}
	public function view_post()
	{
		$list_per_page = 2;
			
		$count =count($this->taskapp_m->get_user_task());
		
        $this->data['page'] = $page = $this->input->get('page');  
        if(!$page)
          $this->data['page'] = $page = 1; 

        $this->data['total_pages'] = $total_pages = ceil($count/$list_per_page);
        $this->data['post_details'] = $this->taskapp_m->get_user_task($list_per_page,($page-1)*$list_per_page);
        $this->data['search'] = $search = '';
       
		$this->load->view('list_tasks.php',$this->data);
	}

	public function view_category()
	{
		$list_per_page = 2;
			
		$count =count($this->taskapp_m->get_category());
		
        $this->data['page'] = $page = $this->input->get('page');  
        if(!$page)
          $this->data['page'] = $page = 1; 

        $this->data['total_pages'] = $total_pages = ceil($count/$list_per_page);
        $this->data['post_details'] = $this->taskapp_m->get_category($list_per_page,($page-1)*$list_per_page);
        $this->data['search'] = $search = '';
       
		$this->load->view('list_category.php',$this->data);
	}

	public function search_post()
	{
		// $list_per_page = 1;

		
		parse_str($_SERVER['QUERY_STRING'],$params);
		
			
		$this->data['search'] = $search = $params['search'];

		$this->data['post_details'] = false;
		// echo '<pre>';
		// print_r($search);
        $this->data['post_details'] = $this->taskapp_m->get_search_post($search);
         // echo "last_query - ".$this->db->last_query();exit; 
        // echo '<pre>';

        if($this->data['post_details'])
        {

				$this->load->view('search_view.php',$this->data);
		}
		else
		{

			$this->session->set_flashdata('search_error','<div>No result Found</div>');
			header('Location:'.base_url().'index.php/blog/view_post');
		}
	}

	public function  add_category()
	{	
		// echo 'sdjos';
		if($this->input->post())
		{
			// echo 'skdjk';
			

			$this->form_validation->set_rules('name','name','required');
			

			$name = $this->input->post('name');
			

	      
				$post_arr = array(
					'name' => $name
				);
		   
			// echo '<pre>';
			// print_r($this->input->post());exit;
			if($this->form_validation->run() == TRUE)
			{
				if($this->taskapp_m->insert_data('category',$post_arr))
				{
					$this->session->set_flashdata('task','<div>Category Added Successfully</div>');
					// $this->data['post_details'] = $this->taskapp_m->get_user_post();
					header('Location:'.base_url().'task_app/view_category');
				}
				else
				{
					$this->session->set_flashdata('not_register','<div>User not Registered</div');
				}
		   }
  
		}  
		else
		{
			$this->data['post_edit'] = 0;
			$this->data['is_edit'] = 1;
			$this->load->view('add_category.php',$this->data);
		}
	}
	public function  add_task()
	{	
		// echo 'sdjos';
		$this->data['all_category'] = $this->taskapp_m->get_category();
		if($this->input->post())
		{
			
			$this->form_validation->set_rules('name','name','required');
			$this->form_validation->set_rules('description','description','required');
			// $this->form_validation->set_rules('category','category','trim|required');
			// $this->form_validation->set_rules('password','password','required');

			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$category = $this->input->post('category_select');
			
			
	       if ($_FILES['userfile']['size'] > 0){
	          echo "<p>".$_FILES['userfile']['name']." => file input successfull</p>";

    
        
         	  if(!is_dir("attachment"))
                mkdir("attachment", 0777, TRUE);

              $target_dir =  "attachment/";
       
              $file_name = $_FILES['userfile']['name'];
              $file_tmp = $_FILES['userfile']['tmp_name'];
              if (move_uploaded_file($file_tmp, $target_dir.$file_name)) {
        
				$post_arr = array(
					'name' => $name,
					'description' => $description,
					'category' => $category,
			        'attachment_url' => base_url().$target_dir.$file_name
				);
		     }
		  }
			// echo '<pre>';
			// print_r($this->input->post());exit;
			if($this->form_validation->run() == TRUE)
			{
				if($this->taskapp_m->insert_data('task_details',$post_arr))
				{
					$this->session->set_flashdata('task','<div>Task Added Successfully</div>');
					// $this->data['post_details'] = $this->taskapp_m->get_user_post();
					header('Location:'.base_url().'task_app/view_post');
				}
				else
				{
					$this->session->set_flashdata('not_register','<div>User not Registered</div');
				}
		   }
  
		}  
		else
		{
			
			$this->data['post_edit'] = 0;
			$this->data['is_edit'] = 1;
			$this->load->view('add_task.php',$this->data);
		}
	}
	public function  edit_task()
	{	
		$this->data['all_category'] = $this->taskapp_m->get_category();
		if($this->input->post())
		{
			// echo 'skdjk';
			$this->form_validation->set_rules('name','name','required');
			$this->form_validation->set_rules('description','description','required');
			$this->form_validation->set_rules('category','category','trim|required');
			// $this->form_validation->set_rules('password','password','required');

			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$category = $this->input->post('category_list');
			

	       if ($_FILES['userfile']['size'] > 0){
	          echo "<p>".$_FILES['userfile']['name']." => file input successfull</p>";

    
        
         	  if(!is_dir("attachment"))
                mkdir("attachment", 0777, TRUE);

              $target_dir =  "attachment/";
       
              $file_name = $_FILES['userfile']['name'];
              $file_tmp = $_FILES['userfile']['tmp_name'];
              if (move_uploaded_file($file_tmp, $target_dir.$file_name)) {
        
				$post_arr = array(
					'name' => $name,
					'description' => $description,
					'category' => $category,
			        'attachment_url' => base_url().$target_dir.$file_name
				);
		     }
		  }
			// echo '<pre>';
			// print_r($this->input->post());exit;
			if($this->form_validation->run() == TRUE)
			{
				if($this->taskapp_m->insert_data('task_details',$post_arr))
				{
					$this->session->set_flashdata('task','<div>Task updated Successfully</div>');
					// $this->data['post_details'] = $this->taskapp_m->get_user_post();
					header('Location:'.base_url().'task_app/view_post');
				}
				else
				{
					$this->session->set_flashdata('not_register','<div>User not Registered</div');
				}
		   }
		}  
		else
		{

			$post_id = $this->input->get('id');
			$this->data['post_edit'] = $post_edit = $this->taskapp_m->get_task_edit($post_id);
			$this->data['is_edit'] = 1;
			// echo '<pre>';
			// print_r($this->data);exit;
			$this->load->view('edit_task.php',$this->data);
		}
	}
	
	public function login_select()
	{
		$this->load->view('loginselect_view.php');
	}

    
	

	
}
?>