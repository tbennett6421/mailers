<?php

	/** uses a SMTP library to send an email message
	 * 
	 * uses Manuel Lemos' SMTP class and his SASL class to send an email message with a subject and a body. 
	 * While PHP has email capatabilites, they rely on sendmail being set up, or wrappers to sendmail. 
	 * Sendmail was really confusing to set up for me, and it would be usefult to have emailing functions in 
	 * a locked-down system where you cannot mess with the sendmail configuration, or sendmail is not in the path,
	 * or there are permission issues, or on Windows/Apple systems.
	 *
	 * @link http://www.phpclasses.org/package/14-PHP-Sends-e-mail-messages-via-SMTP-protocol.html
	 * @link http://www.phpclasses.org/package/1888-PHP-Single-API-for-standard-authentication-mechanisms.html
	 *
	 * @author Tyler Bennett
	 * 
  	 * @param string $to        The address to send to (can not be empty)
	 * @param string $subject   The subject line to add  (can not be empty)
  	 * @param string $body      The body of the message. Can include newlines (can not be empty)
  	 * @return bool true|false to indicate success. Logs error to error_log
  	 */
	function mailTo($to, $subject, $body){
	
		// Parame checking
		$to 	 = empty($to) 	 	? false : (string) $to;
		$subject = empty($subject)  ? false : (string) $subject;
		$body	 = empty($body) 	? false : (string) $body;
		
		if(!($to && $subject && $body)){
			error_log("Could not send message - empty param passed\n");
			return false;
		}
	
		/** smpt.php offers the smtp_class */
		require FP_PHP.'smtp_mail/smtp/smtp.php';
		/** the sasl folder provides authentication methods like LOGIN, PLAIN, NTLM, etc... */
		require FP_PHP.'smtp_mail/sasl/sasl.php';
		
		// Authentication info
		$user  = 'username';							//<-------- EDIT THIS
		$pass  = 'password';							//<-------- EDIT THIS
		$realm = 'example.com';							//<-------- EDIT THIS
		$email = $user.'@'.$realm;						//<-------- EDIT THIS
		$from = $email;
			
		$smtp = new smtp_class();
		//the following are sent in headers to identify host in email logs
		$smtp->localhost = 'www.example.com';			//<-------- EDIT THIS
		$smtp->host_name = 'smtp.example.com';			//<-------- EDIT THIS
		$smtp->host_port = 25;
		$smtp->ssl = 0;
		
		/** Set to the number of seconds wait for a successful connection to the SMTP server */
		$smtp->timeout = 10;
		/** Use smtp->timeout */
		$smtp->data_timeout = 0;
			
		$smtp->debug = 1;                     // Set to 1 to output the communication with the SMTP server
		$smtp->html_debug = 1;                // Set to 1 to format the debug output as HTML
		
		$smtp->user		= $user;
		$smtp->realm	= $realm;
		$smtp->password	= $pass;
		
		//negotiate with server
		$smtp->authentication_mechanism = ""; 	//Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
		
		$headers = array(
				"From: $from",
				"To: $to",
				"Subject: $subject",
				"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		);
	
		$bool = $smtp->SendMessage($from, array($to), $headers, $body);
		if($bool){
			return true;
		}
		else{
			error_log("Could not send message to $to.\nError: ".$smtp->error."\n");
			return false;
		}
	}
