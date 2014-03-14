<?php
	
	require('filestore.php');

	class AddressDataStore extends Filestore {

		function __construct($filename = ''){
	
			$filename = strtolower($filename);
			parent::__construct($filename);
		}
	}
?>		