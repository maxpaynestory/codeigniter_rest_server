<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Lang_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	public function getLangByCode($code = "en") {
		$query = $this->db->get_where ( 'lang', array ('lang' => $code ) );
		return $query->row_array ();
	}
	
	public function getDefaultLang() {
		$this->db->limit ( 1, 0 );
		$query = $this->db->get_where ( 'lang', array ('is_defualt' => 1 ) );
		return $query->row_array ();
	}
}
?>