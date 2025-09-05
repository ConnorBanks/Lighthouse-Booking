<?php
function select_db($database,$return='') {
	if ($_SERVER['DOCUMENT_ROOT'] == 'C:/xampp/htdocs')
	{
	  	define('SERVER', 'C:/xampp/htdocs/L/Lighthouse Restaurant/');
	}
	else
	{
  		define('SERVER', '/home/aldelighthouse/devbooking.lighthouserestaurant.co.uk/');
	}

	//include SERVER.'admin/includes/db.inc.php';
	include SERVER.'db.inc.php';

	if ($database == '') {$dbname = $dbinc['default'];} 
	else {$dbname = $database;}

	if ($return == 'name') {
		return $dbname;
	} else {
		return $dbinc;
	}
}
function connect($forcepdo,$database='')
{
	$dbname = select_db($database,'name');
	$dbinc = select_db($database);
	foreach ($dbinc[$dbname] as $key => $value) {${$key} = $value;}

	if (function_exists('mysql_connect') && $forcepdo == false)
	{
		$dbcnx = @mysql_connect($dbhn, $dbun, $dbpw);
  		if (!$dbcnx) { exit('<p>Unable to connect to the database server at this time.</p>'); }
  		if (!@mysql_select_db($dbname)) { exit('<p>Unable to locate the database at this time.</p>');  }
  		
  		return $dbcnx;
  	}
  	else
  	{
		$dsn = 'mysql:dbname='.$dbname.';host='.$dbhn;
		$user = $dbun;
		$password = $dbpw;
		
		try {
    		$pdo = new PDO($dsn, $user, $password);
    		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
    		exit('Connection failed: ' . $e->getMessage());
		}
  	
  		return $pdo;
  	}
}

function database($query,$database='',$testing='N')
{
	if (strpos($query, '[DATABASE]')) {
		$query = str_replace('[DATABASE]', select_db($database,'name'), $query);
	}
	if ($testing == 'Y') {echo '<p>'.$query.'</p>';}
	$forcepdo = true;
	$connection = connect($forcepdo,$database);

  	$query_array = explode(' ', strtoupper($query));
	
	$result = array();

	if (function_exists('mysql_connect') && $forcepdo == false)
	{
 		$send_query = database($query);
  		//if (!$send_query) {exit('<p>Error: '. mysql_error().' ('.$query.')</p>');}

  		switch ($query_array[0]) {
  			case 'SELECT':
  				$query_number = mysql_num_rows($send_query);
  				while($results = mysql_fetch_array($send_query))
  				{
  					$row = array();
  					foreach ($results as $key => $value) 
  					{
  						if (!is_numeric($key)) 
  						{
	  						$row[$key] = $value;
  						}
  					}
  					$result[] = $row;
  				}
  				if ($query_number == 1 && 
  					(strpos(strtolower($query), 'count') OR 
  					strpos(strtolower($query), 'limit 1') OR 
  					strpos(strtolower($query), 'max') OR 
  					strpos(strtolower($query), 'min'))
  				) {$result = $result[0];}
  				break;
  			case 'INSERT':
  				$result = mysql_insert_id();
  				break;
  			case 'SHOW':
  				$result = mysql_fetch_array($send_query);
  				break;
  			/*case 'DELETE':
  				break;*/
  			default:
  				$result = '';
  				break;
  		}

  		mysql_close($connection);

  		return $result;
	}
	else
	{
		$pdo = $connection;
		$pdo_query = recode_query($query,$testing);
		foreach ($pdo_query as $key => $value) {${$key} = $value;}

		try {
			$prepared_query = $pdo->prepare($query_recoded);
			//print_r($query_array);
		} catch (PDOException $e) {
			/*$showAlert = array('type' => 'error','message' => '<p>Error: '. $e->getMessage().' ('.$query.')</p>');
			include SERVER.'bookings/includes/show_alert.php';*/
			echo '<p>Error: '. $e->getMessage().' ('.$query.')</p>';
			exit();
		}
		try {
			$prepared_query->execute($data_array);
			//print_r($data_array);
		} catch (PDOException $e) {
			/*$showAlert = array('type' => 'error','message' => '<p>Error: '. $e->getMessage().' ('.$query.')</p>');
			include SERVER.'bookings/includes/show_alert.php';*/
			echo '<p>Error: '. $e->getMessage().' ('.$query.')</p>';
			exit();
		}

		switch ($query_array[0]) {
			case 'SELECT':
				try {
	  				//print_r($prepared_query);
	  				$query_number = $prepared_query->rowCount();
  					while($results = $prepared_query->fetch())
  					{
  						//echo 'test';
  						$row = array();
  						foreach ($results as $key => $value) 
  						{
							if (!is_numeric($key)) 
							{
								$row[$key] = $value;
							}	  						
						}
						$result[] = $row;
  					}
	  				if ($query_number == 1 && 
	  					(strpos($query, 'COUNT') OR 
							(strpos($query, 'LIMIT 1') && $query_array[count($query_array)-1] == 1) OR 
							strpos($query, 'MAX') OR 
							strpos($query, 'MIN'))
	  				) {$result = $result[0];}
				} catch (PDOException $e) {
					$showAlert = array('type' => 'error','message' => '<p>Error: '. $e->getMessage().' ('.$query.')</p>');
					include SERVER.'bookings/includes/show_alert.php';
					exit();
				}
				break;
  			case 'INSERT':
  				$result = $pdo->lastInsertId();
  				break;
  			case 'SHOW':
  				$result = $prepared_query->fetch();
  				break;
  			/*case 'DELETE':
  				break;*/
  			default:
  				$result = '';
  				break;
		}

		$connection = $pdo = null;

		return $result;
	}
}

function recode_query($oldcode,$testing='N') 
{
	$newcode = array();

	$start = array("'");
	$end = array("'");
  
	$oldcode_original = $oldcode;

	$data_values = array();
	$query = $oldcode_original;
	$querylength = strlen($query);
	$char = 0; $rec = 0;
	
	while ($char <= $querylength)
	{
    	$substr = substr($query,$char,1);
    
    	if(in_array($substr,$start) && $rec == 0) {$rec = 1;$variable = '';}
    
    	if ($rec == 1)
    	{
        	$variable .= $substr; 
    	}
    
    	if(in_array($substr,$end) && $rec == 1 && !in_array($variable,$start)) 
    	{
        	$rec = 0;
        	if ($testing == 'Y') {echo '<p>'.$variable.'</p>';}
        	$data_values[] = trim($variable);
    	}

    	$char++;
	}

	// QUERY \\
	foreach($data_values as $key => $value)
	{
		$pin = "";  
		for ($pincount=0;$pincount<1;$pincount++) {$pin.=chr(mt_rand(65,90));}	
		for ($pincount=0;$pincount<2;$pincount++) {$pin.=chr(mt_rand(48,57));}
		for ($pincount=0;$pincount<2;$pincount++) {$pin.=chr(mt_rand(97,122));}	
		
		$value = str_replace("'","",$value);
		if (ctype_alnum($value)) {
    		$query = preg_replace('~\''.$value.'\'~',':'.$pin,$query,1);
    	} else {
    		$query = str_replace("'".$value."'",':'.$pin,$query);
    	}
    	$data_values[':'.$pin] = $value;
    	unset($data_values[$key]);
	}
	if ($testing == 'Y') {print_r($data_values);}

	$queryline = '';
	$oldcode_lower =  strtolower($oldcode);
	$queryline = $query;

	if ($testing == 'Y') {echo '<p>New Query: '.$queryline.'</p>';}

// DATA \\
/*
$data =  array();
foreach($data_values as $key => $value)
{
    $dataline .= '"'.str_replace(array("$","'"),array(":",""),$value).'" => ';
    if (strpos(' '.$value,"'")) {
        $dataline .= str_replace("'",'"',$value);   
    } else {
        $dataline .= '"'.$value.'"';
    }
    if ($key < (count($data_values)-1)) {$dataline .= ',';}
}
$dataline .= ')';
if ($testing == 'Y') {echo '<p>Data: '.$dataline.'</p>';}
*/

$newcode = array(
    'query_recoded' => $queryline,
    'data_array' => $data_values
);

return $newcode;
}
?>