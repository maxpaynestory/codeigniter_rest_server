<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Lang_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	/**
	 * Gets language record by language Code. If no code is passed the English language is returned
	 * 
	 * @param Two characters code $code
	 */
	public function getLangByCode($code = "en") {
		$query = $this->db->get_where ( 'lang', array ('lang' => $code ) );
		return $query->row_array ();
	}
	/**
	 * Get the defaul language in the System
	 *  
	 */
	public function getDefaultLang() {
		//Only get one language from DB even if (mistakenly) more than one was set to be default
		$this->db->limit ( 1, 0 );
		$query = $this->db->get_where ( 'lang', array ('is_defualt' => 1 ) );
		return $query->row_array ();
	}
}
?>