<?php 
require_once 'PHPMailer/PHPMailerAutoload.php';

if (!function_exists('SMTP'))
{
	// SMPT SETUP \\
	function SMTP($mail,$smtp_details)
	{
		GLOBAL $crypt;
		
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->SMTPKeepAlive = true; 	// SMTP connection will not close after each email sent, reduces SMTP overhead
		
		/*
		$mail->Port = 465;
		
		$file_contents = $crypt->decrypt(file_get_contents(SERVER.'admin/master/smtp/'.$smtp_details.'.txt'));
		$smtp_settings = json_decode($file_contents,true);		

		$mail->Host 	= $crypt->decrypt($smtp_settings['hostname']);
		$mail->Username = $crypt->decrypt($smtp_settings['username']);
		$mail->Password = $crypt->decrypt($smtp_settings['password']);
		*/

		$mail->Host 	= 'smtp.mandrillapp.com';
		$mail->Port 	= 465;

		$mail->Username = 'The Lighthouse Restaurant';
		$mail->Password = 'md-i-qLq-Zw9Gq56K3B4HtwVw';
		
		return $mail;
	}

	function phpmailer($data_array,$smtp_details='master')
	{
		if (!$data_array[0]['from'])
		{
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->Encoding = "base64";
			$mail->ReturnPath = 'info@lighthouserestaurant.co.uk';

			if ($data_array['debug'] == true) {$mail->SMTPDebug = 3;}

			$mail = SMTP($mail,$smtp_details);

			// FROM \\
			if (is_array($data_array['from']) && count($data_array['from'])) 
			{
				$mail->setFrom(
					($data_array['from']['email']>''?$data_array['from']['email']:''),
					($data_array['from']['name']>''?$data_array['from']['name']:'')
				);
			}
			else
			{
			    echo 'Please supply a from address.';
			    exit();
			}			
			
			// REPLY TO \\
			if (is_array($data_array['reply']) && count($data_array['reply']))
			{
				$mail->addReplyTo(
					($data_array['reply']['email']>''?$data_array['reply']['email']:''),
					($data_array['reply']['name']>''?$data_array['reply']['name']:'')
				);
			}

			// EMAIL TO \\
			if (is_array($data_array['to']) && count($data_array['to']))
			{
				foreach ($data_array['to'] as $key => $data) 
				{
					$mail->addAddress(
						($data['email']>''?$data['email']:''),
						($data['name']>''?$data['name']:'')
					);
				}
			}
			else
			{
				echo 'Please supply address(s) to send the mail to.';
			    exit();
			}

			// EMAIL TO CC \\
			if (isset($data_array['cc']) && is_array($data_array['cc']) && count($data_array['cc']))
			{
				foreach ($data_array['cc'] as $key => $data) 
				{
					$mail->addCC(
						($data['email']>''?$data['email']:''),
						($data['name']>''?$data['name']:'')
					);
				}	
			}

			// EMAIL TO BCC \\
			if (isset($data_array['bcc']) && is_array($data_array['bcc']) && count($data_array['bcc']))
			{
				foreach ($data_array['bcc'] as $key => $data) 
				{
					$mail->addBCC(
						($data['email']>''?$data['email']:''),
						($data['name']>''?$data['name']:'')
					);
				}
			}

			// ADD CUSTOM HEADERS \\
			if (isset($data_array['customheader']) && is_array($data_array['customheader']) && count($data_array['customheader']))
			{
				foreach ($data_array['customheader'] as $key => $data)
				{
 					$mail->addCustomHeader(
						($data['header']>''?$data['header']:''),
						($data['value']>''?$data['value']:'')
					);
				}
			}	

			// ADD ATTACHMENTS \\
			if (isset($data_array['attachments']) && is_array($data_array['attachments']) && count($data_array['attachments']))
			{
				foreach ($data_array['attachments'] as $key => $data) 
				{
					$mail->addAttachment(
						($data['filepath']>''?(!strpos(' '.$data['filepath'], SERVER)?SERVER:'').$data['filepath']:''),
						($data['name']>''?$data['name']:'')/*,
						($data['encoding']>''?$data['encoding']:''),
						($data['type']>''?$data['type']:'')*/
					);
				}
			}

			// ADD STRING ATTACHMENTS \\
			if (isset($data_array['attachments_string']) && is_array($data_array['attachments_string']) && count($data_array['attachments_string']))
			{
				foreach ($data_array['attachments_string'] as $key => $data) 
				{
					$mail->addStringAttachment(
						($data['data']>''?$data['data']:''),
						($data['name']>''?$data['name']:'')
					);
				}
			}

			// ADD INLINE ATTACHMENTS \\
			if (isset($data_array['attachments_inline_image']) && is_array($data_array['attachments_inline_image']) && count($data_array['attachments_inline_image']))
			{
				foreach ($data_array['attachments_inline_image'] as $key => $data) 
				{
					$mail->addEmbeddedImage(
						($data['name']>''?$data['name']:''),
						($data['cid']>''?$data['cid']:'')
					);
				}
			}

			// IF HTML IS PRESENT IN EMAIL \\
			if ($data_array['html_email'] == true) {$mail->isHTML(true);}

			if ($data_array['subject']) {$mail->Subject = $data_array['subject']; /* ADD SUBJECT */	}
			if ($data_array['body']) 	{$mail->Body 	= $data_array['body'];	  /* ADD BODY 	 */	}
			if ($data_array['altbody']) {$mail->AltBody = $data_array['altbody']; /* ADD ALTBODY */	}

			return $mail->send(); 
		}
		else
		{
			echo 'For an array of emails you need to use phpmailer_multiple function.';
			exit();
		}
	}

	function phpmailer_multiple($email_setup_array,$smtp_details='client')
	{
		foreach ($email_setup_array as $data_array) 
		{
			$send = phpmailer($data_array,$smtp_details);
			if ($send == false)
			{
				return false;
			}
		}
	}
}
/*
$email_setup = array(
	'debug' => true,
	'from' => array(
		'email' => 'info@lighthouserestaurant.co.uk'
	),
	'to' => array(
		array('email' => 'james@worldwidewebdesign.co.uk'),
	),
	'subject' => 'TESTING',
	'body' => 'THIS IS A TEST MESSAGE',
	'html_email' => true
);

$sent_mail = phpmailer($email_setup);
if ($sent_mail)
{
	echo 'SENT';
}
*/
?>