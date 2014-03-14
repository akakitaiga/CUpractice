<?php

	// addressdatastore.php to access class AddressDataStore
	require_once('addressdatastore.php');

	class InputException extends Exception {}

	$chat = new AddressDataStore('CUpractice/public/address_book.csv');

	$addressbook = array();
	$entries = array();
	$address_book = $chat->read();

	//errors $arrayName = array( ' ' => ,);
	$errors = [];

	if (!empty($POST)) {
		$entry = [];
		$entry ['name'] = $_POST['name'];
		$entry ['address'] = $_POST['address'];
		$entry ['city'] = $_POST['city'];
		$entry ['state'] = $_POST['state'];
		$entry ['zip code'] = $_POST['zip code'];
		$entry ['phone number'] = $_POST['phone number'];

		try {
			//$entry value is a temp stored in $value
			foreach ($entry as $key => $value){
				if (empty($value)){
					$errors[] = "this must have a value"; // double check on this one - "<em>" . ucfirst($key) . "</em>" . "must have a value";
					throw new InputException("$key cannot be empty");
				}else {
					$entries [] = $value;
				}
				
				if	(strlen($value) > 125){
					throw new InputException("$key must be less that 125 characters");

				}		
			}

		} catch (InputException $e){
			echo "Error: " . $e->getMessage();
		}
		if (empty($errors)){
			array_push($addres_book, array_($entries));
			$chat -> write($address_book);
			header('Location: addressbook.php');
		}
	}
	if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0 && $_FILES['files1']['type'] == 'text/css') {
		$upload_dir = '/CUpractice/public/';
		$file_name = basename($_FILES['file1']['name']);
		$saved_filename = $upload_dir . $file_name;
		move_upload_file($_FILE['file1']['tmp_name'], $saved_filename);
		$chat->filename = $saved_filename;
		$uploaded_items = $saved_filename;
		$chat->filename = 'address_book.csv';
		$items = array_merge($address_book, $uploaded_items);
		$chat->write($items);
		header('Location: addressbook.php');
		exit(0);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Address Book</title>
	</head>
	<body>
		<h1>Address Book</h1> 	
			<table>
					<? foreach ($addressbook as $key => $value){ ?>
					<tr>
						<? foreach ($value as $new_row) { ?>

							<td><? echo $new_row; ?></td>

							<? }	?>

							<td><?= " <a href=?remove={$key}>Remove Contact</a>"; ?></td>

							
							<? if (isset($_GET['remove'])) {

								$key = $_GET['remove'];

								unset($address_book[$key]);

								$chat->write($address_book);

								header('Location: address_book.php');

								exit(0);

							}
							?>

				</tr>
						<? } ?>

			</table>
			<ul>

				<?php
					foreach ($errors as $error) {

						echo "<li>" . $error . "</li>";

					}
				?>

			</ul>
		<h1>New Entry</h1>

			<form method="POST" action="/address_book.php">
				<p>

					<label for="name">Name: </label>
					<input id="name" name="name" type="text">

				</p>
				<p>

					<label for="address">Address: </label>
					<input id="address" name="address" type="text">

				</p>
				<p>

					<label for="city">City: </label>
					<input id="city" name="city" type="text">

				</p>
				<p>

					<label for="state">State: </label>
					<input id="state" name="state" type="text">

				</p>
				<p>

					<label for="zip">Zip: </label>
					<input id="zip" name="zip" type="text">

				</p>
				<p>

					<label for="phone">Phone: </label>
					<input id="phone" name="phone" type="text">

				</p>

				<button type="submit">Submit</button>

			</form>
		<h2>Upload File</h2>

			<form method="POST" enctype="multipart/form-data" action="/address_book.php">
				<p>

					<label for="file1">File to upload: </label>	
					<input type="file" id="file1" name="file1">

				</p>
				<p>

					<input type="submit" value="Upload">
				</p>

			</form>
		
	</body>

</html>