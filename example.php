<?php 
	require('form_check.php');
	
	if (isset($_POST['submit'])) {
		$post_data = $_POST;
		
		$fields = array(
			'required' => array(
				'name' => 'Required name',
				'rules' => 'required',
				'value' => $post_data['required']
			),
			'telephone' => array(
				'name' => 'Telephone name',
				'rules' => 'telephone',
				'value' => $post_data['telephone']
			),
			'required_telephone' => array(
				'name' => 'Required telephone name',
				'rules' => 'required,telephone',
				'value' => $post_data['required_telephone']
			),
			'email' => array(
				'name' => 'Email name',
				'rules' => 'email',
				'value' => $post_data['email']
			),
			'required_email' => array(
				'name' => 'Required email name',
				'rules' => 'required,email',
				'value' => $post_data['required_email']
			)			
		);
		
		$validate = new form_check($fields);
		$validate->run_validation(NULL, NULL, 0);
	}
?>

<form method="post" action="">
	<p>Required <input name="required" type="text" value="<?php echo $_POST['required']; ?>" /> </p>
	<p>Telephone <input type="text" name="telephone" value="<?php echo $_POST['telephone']; ?>"/> </p>
	<p>Required Telephone <input type="text" name="required_telephone" value="<?php echo $_POST['required_telephone']; ?>"/> </p>
	<p>Email <input type="text" name="email" value="<?php echo $_POST['email']; ?>"/> </p>
	<p>Required Email <input type="text" name="required_email" value="<?php echo $_POST['required_email']; ?>"/> </p>
	<p> <input type="submit" name="submit" value="Submit" /> </p>
</form>