<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Load codeigniter-restserver Library
 * 
 * This plugin was written by Philip Sturgeon
 * https://github.com/chriskacerguis/codeigniter-restserver
 */
require APPPATH . '/libraries/REST_Controller.php';

class v1 extends REST_Controller {
	/**
	 * @var Array This array contains request's header values. 
	 * 
	 */
	public $headers=array();
	
	public function __construct() {
		parent::__construct ();
		$this->headers= $this->getRequestHeaders ();
	}
	//---------------- RESTful API Methods ----------------
	/**
	 * Handle HTTP GET on the resource `api/v1/items` to retrieve a list of available items.
	 */
	function items_get() {
		//Load required models for this operation
		$this->load->model ( 'item_model' );
		//Get all items 
		$items = $this->item_model->getItems ();
		//Output response		
		if ($items) {
			$this->response ( $items, 200 );
		} else {
			$this->response ( array ('error' => 'Couldn\'t find any items!' ), 404 );
		}
	}
	
	/**
	 * Handle HTTP GET on the resource `api/v1/wishlist` to retrieve a single wishlist 
	 * by setting the request variable `list` .
	 */
	function wishlist_get() {
		//Load required models for this operation
		$this->load->model ( 'wishlist_model' );
		//Get alias ID
		$aliasId = $this->get ( 'list' );
		//Return 404 if no alias ID is provided
		if (empty ( $aliasId )) {
			$this->response ( array ('error' => 'No wishlist was specified!' ), 404 );
		}
		//Get the user who issued this request
		$user = $this->getUserFromHeader ();
		//Get the specified user's wish list
		$wishlist = $this->wishlist_model->getWishList ( $user ['idUser'], $aliasId );
		//check if there is a wishlist on the specified Wishlist ID
		if (! isset ( $wishlist ['id'] )) {
			$this->response ( array ('error' => 'You don\'t have a wishlist with this alias!' ), 404 );
		}
		//Get items in the selected wishlist
		$items = $this->wishlist_model->getWishlistItems ( $wishlist ['id'] );
		//Calculate total price of books
		$wishlist ['totalAmout'] = $this->sumItemsPrice ( $items );
		//Add items list to the wishlist object
		$wishlist ['items'] = $items;
		//Output response
		if ($wishlist) {
			$this->response ( $wishlist, 200 );
		} else {
			$this->response ( array ('error' => 'Couldn\'t find specified wishlist!' ), 404 );
		}
	
	}
	
	/**
	 * Handle HTTP POST on the resource `api/v1/wishlist` to add new items to wishlist.
	 */
	function wishlist_post() {
		//Load required models for this operation
		$this->load->model ( 'item_model' );
		$this->load->model ( 'wishlist_model' );
		$this->load->model ( 'wishlist_has_item_model' );
		//Get array of items' IDs 
		$add = (array_key_exists ( "add", $_REQUEST )) ? $_REQUEST ["add"] : null;
		if (count ( $add ) == 0) {
			$this->response ( array ('error' => 'There are no items to be added!' ), 400 );
		}
		$newItems = array ();
		foreach ( $add as $id ) {
			$newItem = $this->item_model->getItemById ( $id );
			if (empty ( $newItem )) {
				$this->response ( array ('error' => 'One of the items is not valid item! Item ID=' . $id ), 400 );
			}
			$newItems [] = $newItem;
		}
		//Get wishlist alias ID
		$aliasId = (array_key_exists ( "list", $_REQUEST )) ? $_REQUEST ["list"] : null;
		//Return 400 (Bad Request) if no alias ID is provided
		if (empty ( $aliasId )) {
			$this->response ( array ('error' => 'No wishlist was specified to update!' ), 400 );
		}
		//Get the user who issued this request
		$user = $this->getUserFromHeader ();
		//Get the specified user's wish list
		$wishlist = $this->wishlist_model->getWishList ( $user ['idUser'], $aliasId );
		//check if there is a wishlist on the specified Wishlist ID
		if (! isset ( $wishlist ['id'] )) {
			$this->response ( array ('error' => 'You don\'t have a wishlist with this alias!' ), 404 );
		}
		//Get items in the selected wishlist
		$items = $this->wishlist_model->getWishlistItems ( $wishlist ['id'] );
		
		//Apply changes to DB
		foreach ( $newItems as $newItem ) {
			$affectedRows = $this->wishlist_has_item_model->addItemToWishlist ( $wishlist ['id'], $newItem ['id'] );
			//Add the item to the list only if there are affected 
			//rows, i.e. if the item was actually added to DB
			if ($affectedRows > 0) {
				$items [] = $newItem;
			}
		}
		
		//Calculate total price of books
		$wishlist ['totalAmout'] = $this->sumItemsPrice ( $items );
		//Add items list to the wishlist object
		$wishlist ['items'] = $items;
		//Output response
		if ($wishlist) {
			$this->response ( $wishlist, 200 );
		} else {
			$this->response ( array ('error' => 'Internal Error!' ), 500 );
		}
	}
	
	/**
	 * Handle HTTP DELETE on the resource `api/v1/wishlist` to delete items from wishlist.
	 */
	function wishlist_delete() {
		//Load required models for this operation
		$this->load->model ( 'item_model' );
		$this->load->model ( 'wishlist_model' );
		$this->load->model ( 'wishlist_has_item_model' );
		
		//Get array of items' IDs 
		$delete = $this->delete ( 'delete' );
		if (count ( $delete ) == 0 || ! is_array ( $delete )) {
			$this->response ( array ('error' => 'No items were selected to be deleted!' ), 400 );
		}
		$itemsToDelete = array ();
		foreach ( $delete as $id ) {
			$item = $this->item_model->getItemById ( $id );
			if (empty ( $item )) {
				$this->response ( array ('error' => 'One of the items is not valid item! Item ID=' . $id ), 400 );
			}
			$itemsToDelete [$id] = $item;
		}
		//Get wishlist alias ID
		$aliasId = $this->delete ( "list" );
		//Return 400 (Bad Request) if no alias ID is provided
		if (empty ( $aliasId )) {
			$this->response ( array ('error' => 'No wishlist was specified to update!' ), 400 );
		}
		//Get the user who issued this request
		$user = $this->getUserFromHeader ();
		//Check if this user can do this action if not response as (401 - Unauthorized) 
		if (! $this->user_model->isAdmin ( $user ['idUser'] )) {
			$this->response ( array ('error' => 'Unauthorized to do this action!' ), 401 );
		}
		//Get the specified user's wish list
		$wishlist = $this->wishlist_model->getWishList ( $user ['idUser'], $aliasId );
		//check if there is a wishlist on the specified Wishlist ID
		if (! isset ( $wishlist ['id'] )) {
			$this->response ( array ('error' => 'You don\'t have a wishlist with this alias!' ), 404 );
		}
		//Get items in the selected wishlist
		$items = $this->wishlist_model->getWishlistItems ( $wishlist ['id'] );
		
		//Apply changes to DB
		foreach ( $itemsToDelete as $item ) {
			$affectedRows = $this->wishlist_has_item_model->deleteItemFromWishlist ( $wishlist ['id'], $item ['id'] );
			//Delete the item from the list only if there are affected 
			//rows, i.e. if the item was actually deleted from DB
			if ($affectedRows > 0) {
				foreach ( $items as $key => $wishlistItem ) {
					if ($wishlistItem ['id'] == $item ['id']) {
						unset ( $items [$key] );
					}
				}
			}
		}
		//Without the following line the format will look like this :
		//------------------------------------------------------|----- 
		//{"id":"1","name":"redone","totalAmout":6.01,"items":{"1":{"id":"2","name":"If I Stay","price":"6.01"}}}
		// Note that this "1" this make sthe array associative rather than indexed
		$items = array_values ( $items );
		//Calculate total price of books
		$wishlist ['totalAmout'] = $this->sumItemsPrice ( $items );
		//Add items list to the wishlist object
		$wishlist ['items'] = $items;
		//Output response
		if ($wishlist) {
			$this->response ( $wishlist, 200 );
		} else {
			$this->response ( array ('error' => 'Internal Error!' ), 500 );
		}
	}
	
	//---------------- Helpers Methods ----------------// 
	

	/**
	 * Get the user invoking the request
	 * 
	 * @return Array user
	 */
	public function getUserFromHeader() {
		//Load users model
		$this->load->model ( 'user_model' );
		//Get access token
		$token = $this->headers['X-API-KEY'];
		//Get the user who issued this request
		return $this->user_model->getUserByToken ( $token );
	}
	
	/**
	 * 
	 * Calculate total price of all items in wishlist
	 * @param items array $items
	 * @return The sum of all items' price
	 */
	public function sumItemsPrice($items) {
		$sum = 0;
		foreach ( $items as $item ) {
			$sum += $item ['price'];
		}
		;
		return $sum;
	}
	
	/**
	 * 
	 * Gets HTTP request headers
	 * @return Array of headers as header name is the <b>key</b> and header value is the <b>value</b>
	 */
	public function getRequestHeaders() {
		if (! function_exists ( 'getallheaders' )) {
			foreach ( $_SERVER as $name => $value ) {
				/* RFC2616 (HTTP/1.1) defines header fields as case-insensitive entities. */
				if (strtolower ( substr ( $name, 0, 5 ) ) == 'http_') {
					$headers [str_replace ( ' ', '-', ucwords ( strtolower ( str_replace ( '_', ' ', substr ( $name, 5 ) ) ) ) )] = $value;
				}
			}
		} else {
			$headers = getallheaders ();
		}
		return $headers;
	}

}