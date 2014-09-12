<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Extend API V 1.0 to provide backward compatablity.
 */
require 'v1.php';

class v2 extends v1 {
	public function __construct() {
		parent::__construct ();
	}
	
	//---------------- RESTful API Methods ----------------
	/**
	 * Handle HTTP GET on the resource `api/v2/items` to retrieve a list of available items.
	 */
	function items_get() {
		//Get request headers
		$headers = $this->getRequestHeaders ();
		//Check if language is provided
		$headerLang=(array_key_exists("Accept-Language", $headers))?$headers ['Accept-Language']:null;
		//Get the language code that the client accepts
		$langCode = substr ( $headerLang, 0, 2 );
		//Get the language from DB
		$lang = $this->lang_model->getLangByCode ( $langCode );
		//If language is unsupported get defaul language
		if(!isset($lang['id'])){
			$lang = $this->lang_model->getDefaultLang ();
		}
		//Get items in the requested language 
		$items = $this->item_model->getItems ( $lang );
		//Output response		
		if ($items) {
			$this->response ( $items, 200 );
		} else {
			$this->response ( array ('error' => 'Couldn\'t find any items!' ), 404 );
		}
	}
	
	/**
	 * Handle HTTP GET on the resource `api/v2/wishlist` to retrieve a single wishlist 
	 * by setting the request variable `list` .
	 */
	function wishlist_get() {
		//Get alias ID
		$aliasId = $this->get ( 'list' );
		//Return 404 if no alias ID is provided
		if (empty ( $aliasId )) {
			$this->response ( array ('error' => 'No wishlist was specified!' ), 404 );
		}
		//Get request headers
		$headers = $this->getRequestHeaders ();
		//Get access token
		$token = $headers ['X-API-KEY'];
		//Get the user who issued this request
		$user = $this->user_model->getUserByToken ( $token );
		//Get the specified user's wish list
		$wishlist = $this->wishlist_model->getWishList ( $user ['idUser'], $aliasId );
		//Check if languaage is provided
		$headerLang=(array_key_exists("Accept-Language", $headers))?$headers ['Accept-Language']:null;
		//Get the language code that the client accepts
		$langCode = substr ( $headerLang, 0, 2 );
		//Get the language from DB
		$lang = $this->lang_model->getLangByCode ( $langCode );
		//If language is unsupported get defaul language
		if(!isset($lang['id'])){
			$lang = $this->lang_model->getDefaultLang ();
		}
		//Get items in the selected wishlist
		$items = $this->wishlist_model->getWishlistItems ( $wishlist ['id'], $lang );
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

}