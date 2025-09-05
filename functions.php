<?php
function email_template($body)
{  
  	$emailbody = '<html>'."\n".
	    '<head>'."\n".
	      	'<title>'.$title.'</title>'."\n".
	      	'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n".
	      	'<style type="text/css">'.
			'body {
				margin: 0;
			    font-family: Roboto, sans-serif;
			    width: 100%;
			}
			.container {
				
			}
			.page {
				
			}
			.textbox {
				background-color: #fff;
				color: rgba(54, 54, 54, 1);
				padding: 30px;
			}
			.textbox p {
				margin: 0px;
			}'.
			'</style>'.
      	'</head>'."\n".
      	'<body bgcolor="#FFFFFF">'.
      		'<div class="container">'.
				'<div class="page">'.
					'<div class="textbox">'.
        				'<img src="http://bookings.lighthouserestaurant.co.uk/images/lighthouse-restaurant-logo-dark.png" style="padding-bottom:30px;">'."<br />".
        				$body."\n".
        			'</div>'.
        		'</div>'.
        	'</div>'.
      	'</body>'."\n".
    '</html>';
    
  	return $emailbody;
}
?>