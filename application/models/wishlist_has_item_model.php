<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Wishlist_has_item_model extends CI_Model {
	
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
	}
	
	public function addItemToWishlist($wishlist_id, $item_id) {
		if (empty ( $wishlist_id ) || empty ( $item_id )) {
			return null;
		}
		$data = array ('Wishlist_idWishlist' => $wishlist_id, 'Item_idItems' => $item_id );
		
		$insert_query = $this->db->insert_string ( 'wishlist_has_item', $data );
		$insert_query = str_replace ( 'INSERT INTO', 'INSERT IGNORE INTO', $insert_query );
		$this->db->query ( $insert_query );
		return $this->db->affected_rows ();
	}
	
	public function deleteItemFromWishlist($wishlist_id, $item_id) {
		if (empty ( $wishlist_id ) || empty ( $item_id )) {
			return null;
		}
		$data = array ('Wishlist_idWishlist' => $wishlist_id, 'Item_idItems' => $item_id );
		
		$this->db->delete ( 'wishlist_has_item', $data );
		return $this->db->affected_rows ();
	}

}
?>