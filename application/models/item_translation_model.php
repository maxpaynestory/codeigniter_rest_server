<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Item_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'lang_model' );
	}
	
	public function getItem($id = FALSE, $lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		
		if ($id === FALSE) {
			$query = $this->db->get ( 'item' );
			return $query->result_array ();
		}
		
		$query = $this->db->get_where ( 'item', array ('idItems' => $id ) );
		return $query->row_array ();
	}
}
?>