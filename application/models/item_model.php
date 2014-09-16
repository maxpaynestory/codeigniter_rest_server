<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Item_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'lang_model' );
	}
	
	/**
	 * Gets a list of items translated to passed <b>language</b>, or to <b>default</b> language 
	 * if no language is provided.  
	 *  
	 * @param String $lang
	 */
	public function getItems($lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		//use this method of selection from DB to join items table with translations and to use columns aliases 
		$this->db->select ( "idItems AS id,name,price", FALSE );
		$this->db->from ( "item" );
		$this->db->join ( "item_translation", "item_translation.item_id=item.idItems" );
		$this->db->where ( "lang_id", $lang ['id'] );
		$query = $this->db->get ();
		return $query->result_array ();
	}
	
	/**
	 * Gets an item by ID translated to passed <b>language</b>, or to <b>default</b> language 
	 * if no language is provided.  
	 * 
	 * @param Integer $id
	 * @param String $lang
	 */
	public function getItemById($id, $lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		if (empty ( $id )) {
			return null;
		}
		//use this method of selection from DB to join items table with translations and to use columns aliases
		$this->db->select ( "idItems AS id,name,price", FALSE );
		$this->db->from ( "item" );
		$this->db->join ( "item_translation", "item_translation.item_id=item.idItems" );
		$this->db->where ( "lang_id", $lang ['id'] );
		$this->db->where ( "id", $id );
		$query = $this->db->get ();
		return $query->row_array ();
	}
}
?>