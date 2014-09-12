<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wishlist_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'lang_model' );
	}
	
	public function getWishList($user_id, $aliasId) {
		$this->db->select ( "idWishlist AS id,name", FALSE );
		$this->db->from ( "wishlist" );
		$this->db->where ( 'User_idUser', $user_id );
		$this->db->where ( 'aliasId', $aliasId );
		$query = $this->db->get ();
		return $query->row_array ();
	}
	
	public function getWishlistItems($wishlist_id, $lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		
		$this->db->select ( "item.idItems AS id ,item_translation.name,item_translation.price", FALSE );
		$this->db->from ( "wishlist_has_item" );
		$this->db->join ( "item", "item.idItems=wishlist_has_item.Item_idItems" );
		$this->db->join ( "item_translation", "item_translation.item_id=item.idItems" );
		$this->db->where ( "lang_id", $lang ['id'] );
		$this->db->where ( "Wishlist_idWishlist", $wishlist_id );
		$query = $this->db->get ();
		return $query->result_array ();
	}

}
?>