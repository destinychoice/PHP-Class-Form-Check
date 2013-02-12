<?php 
	/* 
		CLASS NAME: form_check
		AUTHOR: Jason
		DATE UPDATED: 16-12-2012
		
		A class designed to be a way to validate forms in a standard way to avoid human errors
		
		'rules' => 'required,email,number,telephone,mobile,postcode',
	*/
	class form_check {
		
		public $post_data;
		public $error_page;
		public $redirect;
		
		function __construct($user_post_data = NULL) {
			$this->post_data = array();
			$this->error_page = $_SERVER['HTTP_REFERER'];
			$this->redirect = 0;
			
			if (is_array($user_post_data)) {
				$this->post_data = $user_post_data;
			} 
		}
		
		function __destruct() {	/* Run any thing you need to when the class is deconstructed */	}
		
		function run_validation() {	
			if ($this->post_data == array() || empty($this->post_data)) {
				exit("<pre>Post data is empty please correct.</pre>");
			}
			
			foreach ($this->post_data as $key => $row) {
				/* TEST FOR BLANK REQUIRED FIELD */
				if (preg_match("/required/", $row['rules']) && empty($row['value'])) {
					$_SESSION['temp']['error'][$key] = "$row[name] cannot be blank";
				}
				/* DO THE FOLLOWING CHECKS ONLY IF NOT EMPTY */
				if (!empty($row['value'])) {
					/* TEST FOR EMAIL FIELDS */
					if (preg_match('/email/', $row['rules']) && !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $row['value'])) {
						$_SESSION['temp']['error'][$key] = "$row[name] is an invalid email";
					}
					/* TEST FOR NUMBER FIELDS */
					if (preg_match('/number/', $row['rules']) && preg_match('/[^0-9]/', $row['value'])) {
						$_SESSION['temp']['error'][$key] = "$row[name] can only contain numbers";
					}
					/* TEST FOR TELEPHONE FIELDS */
					if (preg_match('/telephone/', $row['rules']) && (preg_match('/[^0-9 \+]/', $row['value']) || $row['value'][7] == '')) {
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid phone number";
					}
					/* TEST FOR MOBILE FIELDS */
					if (preg_match('/mobile/', $row['rules']) && (preg_match('/[^0-9 \+]/', $row['value']) || $row['value'][9] == '')) {
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid mobile number";
					}
					/* TEST FOR POSTCODE FIELDS */
					if (preg_match('/postcode/', $row['rules']) && (preg_match('/[^0-9]/', $row['value']) || $row['value'][3] == '')) {
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid postcode";
					}
				}
			}
		}
		
		function redirect() {
			if ($this->error_page == NULL) {
				exit("<pre>Error page is empty please correct.</pre>");
			}
		
			if ($_SESSION['temp']['error'] && $this->redirect == 1) {
				header('Location: ' . $this->error_page);
				exit;
			}			
		}
		
		// HELPER
		// Creates a array of any fields in the $_post to make it easy to build the validation array
		function generate_validation_list() {
			$field = "\$field = array( \n";
			foreach ($_POST as $key => $row) {
				$field .= "	'$key' => array( \n" .
						  "		'name' => '', \n" .
						  "		'rules' => '', \n" .
						  "		'value' => \$post_data['$key'] \n" .
						  "	), \n";
			}
			$field .= "); \n";
			echo "<pre>$field</pre>";
		}
	}	
?>