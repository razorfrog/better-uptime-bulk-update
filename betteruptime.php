<?php
// set page number to 1 unless a positive integer is already specified	
$page = ( is_int((int)$_GET['page']) && (int)$_GET['page'] > 0 ) ? (int)$_GET['page'] : 1;

$api = $_GET['api'];
$key = htmlentities($_GET['key']);
$value = htmlentities($_GET['value']);
$debug = $_GET['debug'];

?>

<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Better Uptime Bulk Updates</title>
    
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">

	<style type="text/css">
		body {
			font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            background-color: teal;
            color: white;
		}

		.wrap {
			width: 100%;
			margin: 0 auto;
		}
		
		.center {
			margin: auto;
			width: 80%;
			padding: 10px;
			padding-top: 1vh;
		}

		h1 {
			color: white;
			font-size: 100px;
			line-height: 110px;
			display:table-caption;
			margin-bottom: 40px;
		}

		p {
			font-size: medium;
			color: white;
			font-weight: 400;
			line-height: 24px;
			margin-bottom: 30px;
		}

		.footer {
			margin: 0 auto;
			bottom: 12px;
			font-size: 12px;
			color: white;
			margin-left: auto;
			margin-right: auto;
			text-align: center;
		}
		
		label {display: inline-block; width: 190px;}

		input[type="text"] {width: 250px;}

		.foo {color:#ffb9f6;}

		.bar {color:#81ffff;}

		.tg  {border-collapse:collapse;border-spacing:0;}
		.tg td{overflow:hidden;padding: 5px 15px 5px 0px;word-break:normal;}
		.tg th{overflow:hidden;padding:5px 0px;word-break:normal; border-bottom: 1px solid;}
		.tg .tg-0lax{text-align:left;vertical-align:top}

		.destroy, .error {color:#ff6d6d;}
        
    </style>
    
</head>

<body>
	<div class="wrap">
		<div class="center">
			<div class="text-left">

			<h1>Better Uptime Bulk Updates</h1>

			<table class="tg">
				<thead>
					<tr>
						<th class="tg-0lax">EXAMPLE QUERIES</th>
						<th class="tg-0lax">KEY</th>
						<th class="tg-0lax">VALUE</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="tg-0lax">Return list of monitors only</td>
						<td class="tg-0lax"><em>leave empty</em></td>
						<td class="tg-0lax"><em>leave empty</em></td>
					</tr>
					<tr>
						<td class="tg-0lax">Disable domain renewal monitoring</td>
						<td class="tg-0lax">domain_expiration</td>
						<td class="tg-0lax">null</td>
					</tr>
					<tr>
						<td class="tg-0lax">Change to keyword monitoring</td>
						<td class="tg-0lax">monitor_type</td>
						<td class="tg-0lax">"keyword"</td>
					</tr>
					<tr>
						<td class="tg-0lax">Set monitor keyword</td>
						<td class="tg-0lax">required_keyword</td>
						<td class="tg-0lax">"razorfrog"</td>
					</tr>
					<tr>
						<td class="tg-0lax"></td>
						<td class="tg-0lax"></td>
						<td class="tg-0lax"></td>
					</tr>
				</tbody>
			</table>
			
			<p>Form will default to page #1 (50 entries). You will need to manually increment the page number to loop through all monitors.</p>
			
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="GET">
				<label for="api">API KEY</label>
				<input type="text" name="api" id="api" value="<?php echo $_GET['api']; ?>" /> 
				<br/>
				
				<label for="page">PAGE</label>
				<input type="text" name="page" id="page" value="<?php echo $page; ?>" />
				<br/>
				
				<?php if ($_GET['delete'] != true){ ?>
				
					<label for="key">DATA KEY</label>
					<input type="text" name="key" id="key" value="<?php echo $key; ?>" />
					<br>
					
					<label for="value">DATA VALUE</label>
					<input type="text" name="value" id="valye" value="<?php echo $value; ?>" />
					<br>
					
				<?php } ?>
				
				<label for="debug">SHOW JSON</label>
				<input type="checkbox" id="debug" name="debug" value="true" <?php if($_GET['debug']==true){echo 'checked';} ?> >
				<br>
				
				<label for="delete">DELETE ALL MONITORS</label>
				<input type="checkbox" id="delete" name="delete" value="true" <?php if($_GET['delete']==true){echo 'checked';} ?> >
				<br>
				
				<?php if ($_GET['delete'] == true){ ?>
					
					<label for="destroy" class="destroy">CONFIRM DELETE</label>
					<input type="checkbox" id="destroy" name="destroy" value="true">
					<br>
				
				<?php } ?>
				
				<input type="submit" name="submit" id="submit" />
			</form>	
                   
<?php
	
if ($_GET['api'] !=""){
	
///////////////////////////////////////////////////////////////////////////
// get pages and monitor IDS
///////////////////////////////////////////////////////////////////////////
	
	$url = "https://betteruptime.com/api/v2/monitors?page=$page";
	
	$headers = array(
		"Authorization: Bearer $api",
		"Content-Type: application/json",
	);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	
	$response = curl_exec($curl);
	curl_close($curl);
			
	if ($debug){	
		// DISPLAY JSON RESULT
		echo pretty_print($response);
	}
	
	// json to php array
	$phparray = json_decode($response, true);
	
	// error check
	$errors = ($phparray['errors']);
	
	if ($errors){
		echo "<p class='error'>";
		print_r( $errors);
		echo "</p>";		
	} else {
	
	// pagination data only 
	$pages = ($phparray['pagination']);
	$nextpage = $pages['next'];	
	$lastpage = $pages['last'];
	
	$totalpages = substr($pages['last'], -1);
		
	// get rid of pagination data
	$data = ($phparray['data']);
	
	// get monitor ids from array
	$ids = array_map(function ($value) {
		return $value['id'];
	}, $data);
	
	// get attributes 
	$attributes = ($phparray['data']);	
	
	// get monitor urls from array
	$urls = array_map(function ($urls) {
		return $urls['attributes']['url'];
	}, $attributes);
	
	// DISPLAY FULL ARRAY
	//echo "<pre>";
	//print_r($phparray); // FULL RESULT
	//print_r($data); 	  // DATA ONLY, NO PAGINATION 
	//echo "</pre>";
	
	if ($page <= $totalpages){
		echo "<h2>Monitors (Page $page of $totalpages)</h2><pre>";
		//print_r($ids);
		//print_r($urls);
		echo "</pre>";
		
		foreach($urls as $url) {
			echo $url, '<br>';
		}
		
	} else {
		echo "<p>error: page number out of bounds ($page < $totalpages)</p>";
	}

///////////////////////////////////////////////////////////////////////////
// loop through monitors and apply action
///////////////////////////////////////////////////////////////////////////

if (($_GET['key'] != "") && ($_GET['delete'] != true)) {
	
	$key = $_GET['key'];
	$value = $_GET['value'];

	foreach ($ids as $siteid) {
	    	  
		$data = '{"'.$key.'": '.$value.'}';
		$url = "https://betteruptime.com/api/v2/monitors/$siteid";
		$headers = array(
			"Authorization: Bearer $api",
			"Content-Type: application/json",
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($curl);
		curl_close($curl);
		
		echo "<h3>result for ID $siteid</h3>";
		echo pretty_print($response);    
		   
	} // end for loop

} // end key check

///////////////////////////////////////////////////////////////////////////
// loop through monitors and delete them all 
///////////////////////////////////////////////////////////////////////////

if (($_GET['key'] == "") && ($_GET['destroy'] == true)) {
	
	echo "<h2 class='destroy'>DELETED PAGE $page</h2>";
	
	foreach ($ids as $siteid) {
	    	  
		$url = "https://betteruptime.com/api/v2/monitors/$siteid";
		$headers = array(
			"Authorization: Bearer $api",
			"Content-Type: application/json",
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		
		$response = curl_exec($curl);
		curl_close($curl);
		
		echo "<h3>deleted ID $siteid</h3>";
		   
	} // end for loop
	
} // end key check

} // end error check 

} // end api check 

?>
           
			</div>
		</div>
	</div>
	<div class="footer">Razorfrog Web Design 2022</div>
</body>

</html>

<?php
	
////////////////////////////////////////////
// https://linuxhint.com/pretty_json_php/
// pretty print JSON 
////////////////////////////////////////////

function pretty_print($json_data){

	//Initialize variable for adding space
	$space = 0;
	$flag = false;
	
	//Using <pre> tag to format alignment and font
	echo "<pre>";
	
	//loop for iterating the full json data
	for($counter=0; $counter<strlen($json_data); $counter++){
	
		//Checking ending second and third brackets
		if ( $json_data[$counter] == '}' || $json_data[$counter] == ']' ){
			$space--;
			echo "\n";
			echo str_repeat(' ', ($space*2));
		}
		 
		//Checking for double quote(“) and comma (,)
		if ( $json_data[$counter] == '"' && ($json_data[$counter-1] == ',' || $json_data[$counter-2] == ',') ){
			echo "\n";
			echo str_repeat(' ', ($space*2));
		}
		
		if ( $json_data[$counter] == '"' && !$flag ){
			if ( $json_data[$counter-1] == ':' || $json_data[$counter-2] == ':' )
			
			//Add formatting
			echo '<span class="foo">';
			else
			
			//Add formatting
			echo '<span class="bar">';
		}
		
		echo $json_data[$counter];
		//Checking conditions for adding closing span tag
		if ( $json_data[$counter] == '"' && $flag )
			echo '</span>';
		if ( $json_data[$counter] == '"' )
			$flag = !$flag;
		
		//Checking starting second and third brackets
		if ( $json_data[$counter] == '{' || $json_data[$counter] == '[' ){
			$space++;
			echo "\n";
			echo str_repeat(' ', ($space*2));
		}
	}
	echo "</pre>";
}
?>
