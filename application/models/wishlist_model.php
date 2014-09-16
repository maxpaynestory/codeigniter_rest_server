<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wishlist_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		$this->load->model ( 'lang_model' );
	}
	
	/**
	 * Gets a wishlist with provided alias for a specified user
	 * 
	 * @param Integer $user_id list owner
	 * @param Integer $aliasId list's unique ID for this user. 
	 */
	public function getWishList($user_id, $aliasId) {
		$this->db->select ( "idWishlist AS id,name", FALSE );
		$this->db->from ( "wishlist" );
		$this->db->where ( 'User_idUser', $user_id );
		$this->db->where ( 'aliasId', $aliasId );
		$query = $this->db->get ();
		return $query->row_array ();
	}

	/**
	 * Gets wishlist's items translated to specified language of to the default language if 
	 * no language is provided. 
	 * 
	 * @param Integer $wishlist_id list ID
	 * @param Language $lang Language object. 
	 */
	public function getWishlistItems($wishlist_id, $lang = FALSE) {
		if ($lang === FALSE) {
			$lang = $this->lang_model->getDefaultLang ();
		}
		//use this method of selection from DB to join with `items` table and with `translations` and 
		//to use columns aliases 
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