<?php
function getItems($token,$version=1,$lang="en",$format="json"){
	echo "<br/><br/>\r\n";
	$url = 'http://localhost/wishlist/index.php/api/v'.$version.'/items';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-API-KEY: '.$token,
		'Accept: application/'.$format,
		'Accept-Language: '.$lang
		));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec ($ch);

	curl_close ($ch);
	print_r($result);
	echo "<br/><br/>\r\n";
}

function getWishlist($list,$token,$version=1,$lang="en",$format="json"){
	echo "<br/><br/>\r\n";
	$url = 'http://localhost/wishlist/index.php/api/v'.$version.'/wishlist/list/'.$list;

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-API-KEY: '.$token,
		'Accept: application/'.$format,
		'Accept-Language: '.$lang
		));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec ($ch);

	curl_close ($ch);
	print_r($result);
	echo "<br/><br/>\r\n";
}

function addItemsToWishlist($add,$list,$token,$version=1,$lang="en",$format="json"){
	echo "<br/><br/>\r\n";
	$url = 'http://localhost/wishlist/index.php/api/v'.$version.'/wishlist';
	
	$data = array('add' => $add,"list"=>$list);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-API-KEY: '.$token,
		'Accept: application/'.$format,
		'Accept-Language: '.$lang
		));

	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec ($ch);

	curl_close ($ch);
	print_r($result);
	echo "<br/><br/>\r\n";
}

function deleteItemsFromWishlist($delete,$list,$token,$version=1,$lang="en",$format="json"){
	echo "<br/><br/>\r\n";
	$url = 'http://localhost/wishlist/index.php/api/v'.$version.'/wishlist';
	//$delete=array(1,3);
	//$list=1;
	$data = array('delete' => $delete,"list"=>$list);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'X-API-KEY: '.$token,
		'Accept: application/'.$format,
		'Accept-Language: '.$lang
		));

	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec ($ch);

	curl_close ($ch);
	echo($result);
	echo "<br/><br/>\r\n";
}

echo "<h1>Test API V 1.0</h1><br/>";
//Items list
echo "<h3>Try getting items without using token!</h3>";
getItems("");
echo "<h3>Try getting items using invalid token!</h3>";
getItems("abcsdd");
echo "<h3>Try getting items using valid token!</h3>";
getItems("redtoken");

//Wishlist view
echo "<h3>Try getting wishlist without using token!</h3>";
getWishlist("1","");
echo "<h3>Try getting wishlist using invalid token!</h3>";
getWishlist("1","asd132");
echo "<h3>Try getting wishlist using valid token!</h3>";
getWishlist("1","greentoken");
echo "<h3>Try getting invalid wishlist using valid token!</h3>";
getWishlist("11","greentoken");
echo "<h3>Give null as wishlist to get!</h3>";
getWishlist(null,"greentoken");

//Wishlist add items
echo "<h3>Try adding invalid item to wishlist using valid token!</h3>";
addItemsToWishlist(array(1,2,6),1,"redtoken");
echo "<h3>Try adding items to invalid wishlist using valid token!</h3>";
addItemsToWishlist(array(1,2,3),44,"redtoken");
echo "<h3>Try adding no items to wishlist using valid token!</h3>";
addItemsToWishlist(null,1,"redtoken");
echo "<h3>Try adding items to no wishlist using valid token!</h3>";
addItemsToWishlist(array(1,2,3),null,"redtoken");
echo "<h3>Try adding items to wishlist using valid token!</h3>";
addItemsToWishlist(array(1,2,3),1,"redtoken");

//Wishlist delete items
echo "<h3>Try deleting invalid item from wishlist using valid token!</h3>";
deleteItemsFromWishlist(array(6),1,"redtoken");
echo "<h3>Try deleting items from invalid wishlist using valid token!</h3>";
deleteItemsFromWishlist(array(1,2),44,"redtoken");
echo "<h3>Try deleting no items from wishlist using valid token!</h3>";
deleteItemsFromWishlist(null,1,"redtoken");
echo "<h3>Try deleting items from no wishlist using valid token!</h3>";
deleteItemsFromWishlist(array(1,3),null,"redtoken");
echo "<h3>Try deleting items from wishlist using valid token!</h3>";
deleteItemsFromWishlist(array(3),1,"redtoken");

echo "<h1>Test API V 2.0</h1><br/>";
//Items list
echo "<h3>Try getting items without specifying lang!</h3>";
getItems("redtoken",2);
echo "<h3>Try getting items using null language!</h3>";
getItems("redtoken",2,null);
echo "<h3>Try getting items using empty language!</h3>";
getItems("redtoken",2,"");
echo "<h3>Try getting items using unsupported language!</h3>";
getItems("redtoken",2,"fr");
echo "<h3>Try getting items translated to German!</h3>";
getItems("redtoken",2,"de");

//Wishlist view
echo "<h3>Try viewing wishlist without specifying lang!</h3>";
getWishlist("1","redtoken",2);
echo "<h3>Try viewing wishlist using null language!</h3>";
getWishlist("1","redtoken",2,null);
echo "<h3>Try viewing wishlist using empty language!</h3>";
getWishlist("1","redtoken",2,"");
echo "<h3>Try viewing wishlist using unsupported language!</h3>";
getWishlist("1","redtoken",2,"fr");
echo "<h3>Try viewing wishlist translated to German!</h3>";
getWishlist("1","redtoken",2,"de");

echo "<h1>End of Test</h1><br/>";
?>