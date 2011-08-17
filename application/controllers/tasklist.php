<?php
class Tasklist extends CI_Controller {

	protected $days = NULL;
	protected $months = NULL;
	protected $years = NULL;
	protected $errors = FALSE;
    
    function __construct() {
     
        parent::__construct();
		$this->load->model('Task_model', 'tasks');
        $this->load->helper('form', 'url');
		$this->load->library('form_validation');
		
		$this->days = range(1, 31);
		$this->months = range(1, 12);
		$current_year = date('Y');
		$this->years = range($current_year, $current_year + 5);
		
    }
    
    function index() {
        
        $data['errors'] = $this->errors;
        $data['tasks']  = $this->tasks->get_current_tasks();
		$data['days']   = $this->days;
		$data['months']   = $this->months;
		$data['years']   = $this->years;
     
        $this->load->view('index', $data);
        
    }
    
	/**
	 * add a task to our todo list
	 */
    function add() {
		
		// validation rules
		$this->form_validation->set_rules('task_name', 'Task Name', 'required');		
		$this->form_validation->set_rules('day', 'Date', 'callback_check_date');		
		
		if($this->form_validation->run() == FALSE) {
			$this->errors = TRUE;
		}
        else {
    		
    		// construct date from POST fragments
    		$due_date  = implode('-', array(
    			$this->input->post('year'), 
    			$this->input->post('month'), 
    			$this->input->post('day')
    		));
    		$due_date .= ' 23:59:59';
    		
    		// create data for database insert
    		$data = array(
    			'task_name' => $this->input->post('task_name'),
    			'task_due'  => $due_date
    		);
    		
    		// use the insert method of the tasks model
    		$this->tasks->insert_task($data);
        }
		
		$this->index();
        
    }
    
	/**
	 * mark a task as complete
	 */
    function complete() {
        $this->tasks->mark_task_complete($this->uri->segment(3));
		$this->index();
    }
	
	/**
	 * callback function to validate date
	 */
	function check_date() {
		
		$day   = $this->input->post('day');
		$month = $this->input->post('month');
		$year  = $this->input->post('year');
		$error = false;
		
	    // check if date entries are numbers
		if(!ctype_digit($day) || !ctype_digit($month) || !ctype_digit($year)) {
			$error = true;
			$this->form_validation->set_message('check_date', 'Sorry, something went wrong with the date information.');
		}
		// check date is a real date ie not 30 Feb
		if(!checkdate($month, $day, $year)) {
			$error = true;
			$this->form_validation->set_message('check_date', 'That date is not valid.');
		}
		// check date is in the future
		// if the date is today that is OK so I'll compare current timestamp with timestamp for the end of the day
		if(mktime(23, 59, 59, $month, $day, $year) < time()) {
			$this->form_validation->set_message('check_date',  "The date you've given is in the past.");
			$error = true;
		}
		
		if($error) return false;
		else return true;

	}
    
}