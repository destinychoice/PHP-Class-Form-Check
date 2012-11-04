<?php 
	/* 
		CLASS NAME: form_check
		AUTHOR: Jason
		DATE UPDATED: 27-09-2012
		
		This class is meant to standardise the way we check form input and reduce the amount of coding required on new sites.
		
		'rules' => 'required,email,number,telephone,mobile,postcode',
	*/
	class form_check {
		
		public $post_data;
		
		function __construct($user_post_data = NULL) {		
			if (is_array($user_post_data)) {
				$this->post_data = $user_post_data;
			} else {
				$this->post_data = array();
			}
		}
		
		function __destruct() {	/* Run any thing you need to when the class is deconstructed */	}
		
		function run_validation($error_page = NULL, $success_page = NULL, $redirect = 1) {
			if ($error_page == NULL) {
				$error_page = $_SERVER['HTTP_REFERER'];
			}
						
			if ($this->post_data == array()) {
				exit("<pre>Post data is empty please correct.</pre>");
			}
			
			$error = 0;
			
			foreach ($this->post_data as $key => $row) {
				/* TEST FOR BLANK REQUIRED FIELD */
				if (preg_match("/required/", $row['rules']) && $row['value'] == '') {
					$_SESSION['temp']['error'][$key] = "$row[name] cannot be blank";
				}
				if ($row['value'] != '') {
					if (preg_match('/email/', $row['rules']) && !preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $row['value'])) {
						/* TEST FOR EMAIL FIELDS */
						$_SESSION['temp']['error'][$key] = "$row[name] is an invalid email";
						$error++;
					}

					if (preg_match('/number/', $row['rules']) && preg_match('/[^0-9]/', $row['value'])) {
						/* TEST FOR NUMBER FIELDS */
						$_SESSION['temp']['error'][$key] = "$row[name] can only contain numbers";
						$error++;
					}
					if (preg_match('/telephone/', $row['rules']) && (preg_match('/[^0-9 \+]/', $row['value']) or $row['value'][7] == '')) {
						/* TEST FOR TELEPHONE FIELDS */
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid phone number";
						$error++;
					}
					if (preg_match('/mobile/', $row['rules']) && (preg_match('/[^0-9 \+]/', $row['value']) or $row['value'][9] == '')) {
						/* TEST FOR MOBILE FIELDS */
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid mobile number";
						$error++;
					}
					if (preg_match('/postcode/', $row['rules']) && (preg_match('/[^0-9]/', $row['value']) or $row['value'][3] == '')) {
						/* TEST FOR MOBILE FIELDS */
						$_SESSION['temp']['error'][$key] = "$row[name] seems to be an invalid postcode";
						$error++;
					}
				}
			}
			
			if ($_SESSION['temp']['error'] && $redirect == 1) {
				header('Location: ' . $error_page) or die('dying');
				exit;
			}
		}
		
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