<?php

	// site owner
	$to = trim( $_POST['to'] );
	$subject_prefix = trim( $_POST['subject_prefix'] );
	$subject = trim( $_POST['subject'] );
	
	
	// contact form fields
	$name = trim( $_POST['name'] );
	$email = trim( $_POST['email'] );
	$message = trim( $_POST['message'] );
	
	
	$sum_user = "";
	$sum_random = "";
	$sum = "incorrect";
	
	$sum_user = $_POST['sum_user'];
	$sum_random = $_POST['sum_random'];
	
	
	if ( $sum_random == $sum_user )
	{
		$sum = 'correct';
	}
	
	
	$error = false;
	
	if ( $name === "" )
	{
		$error = true;
	}
	elseif ( $email === "" )
	{
		$error = true;
	}
	elseif ( $message === "" )
	{
		$error = true;
	}
	// end if
	
	
	if ( $error == false )
	{
		if ( $sum == 'correct' )
		{
			$body = "Name: $name \n\nEmail: $email \n\nMessage: $message";
			
			$headers = "From: " . $name . " <" . $email . "> " . "\r\n";
			$headers .= "Reply-To: " . $email . "\r\n";
			
			mail( $to, $subject_prefix . ' ' . $subject, $body, $headers );
			
			echo 'success';
		}
		else
		{
			echo 'incorrect';
		}
		// end if
	}
	else
	{
		echo 'error';
	}
	// end if
	
?>