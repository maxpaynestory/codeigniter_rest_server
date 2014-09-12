Note:
- To install: 
	1- Extract attached file to your www directory 
	2- if the DB is not already installed apply this script (https://www.dropbox.com/s/vyfg3zv1e5nky4p/wishlist.sql?dl=0)
	3- Update DB to version 2.0 by applying the script `v2_updates.sql` found under `wishlist/db_updates`.
- In the `doc` directory you can find a list of API FUNCTIONS in details.
- json is the default format for response, however, you can change this by setting the `Accept` header in the http request to be xml or plain text.
- Tests are attached in a separate folder `test_wishlist`, to run tests just point your browser to the http://your-server:yourport/test_wishlist
