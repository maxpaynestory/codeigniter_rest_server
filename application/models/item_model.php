<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Item_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'lang_model' );
	}
	
	public function getItems($lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		$this->db->select ( "idItems AS id,name,price", FALSE );
		$this->db->from ( "item" );
		$this->db->join ( "item_translation", "item_translation.item_id=item.idItems" );
		$this->db->where ( "lang_id", $lang ['id'] );
		$query = $this->db->get ();
		return $query->result_array ();
	}
	
	public function getItemById($id, $lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		if (empty ( $id )) {
			return null;
		}
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