<?php
class Task_model extends CI_Model {

	var $name     = '';
	var $due      = '';
	var $complete = '';
	
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * returns an array of tasks that haven't been completed
	 */
	function get_current_tasks() {
		$data = array();
		$sql = "SELECT * FROM tasks WHERE complete IS NULL";
		$query = $this->db->query($sql);
		foreach($query->result() as $row) {
			$data[] = array(
				'id'        => $row->id,
				'task_name' => $this->_escape_html($row->task_name),
				'task_due'  => $this->_friendly_date($row->task_due),
				'class'     => $this->_expired_class($row->task_due)
			);
		}
		return $data;
	}
	
	/** 
	 * format a date for view
	 */
	protected function _friendly_date($date = FALSE) {
		if(!$date) return '';
		$timestamp = strtotime($date);
		$friendly_date = date('jS M, Y', $timestamp);
		return $friendly_date;
	}
	
	/**
	 * escape for HTML view
	 */
	protected function _escape_html($string = FALSE) {
		if(!$string) return '';
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * check if a task needs to be marked as overdue based on date
	 */
	protected function _expired_class($date) {
		$class = '';
		if(strtotime($date) < time()) {
			$class = 'expired';
		}
		return $class;
	}
	
	/**
	 * insert a task into the database
	 */
	function insert_task($data) {
		$this->db->insert('tasks', $data);
	}
	
	/**
	 * update a task to mark it as complete
	 */
	function mark_task_complete($id) {
		$data = array('complete' => date('Y-m-d h:i:s'));
		$this->db->update('tasks', $data, array('id' => $id));
	}

}