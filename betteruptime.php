<?php
	
if ($_GET['page']){$page = $_GET['page'];} else{ $page = 1;}

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

        }

        p {
            font-size: medium;
            color: white;
            font-weight: 400;
            line-height: 12px;
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
        
        label {display: inline-block; width: 120px;}
        
        input[type="text"] {width: 250px;}
        
        .foo {color:#ffb9f6;}
        
        .bar {color:#81ffff;}
        
    </style>
    
</head>

<body>
    <div class="wrap">
        <div class="center">
            <div class="text-left">
			
			<h1>Better Uptime Bulk Updates</h1>
			
			<form action="https://razorfrog.com/betteruptime.php" method="GET">
			    <label for="name">API KEY</label>
			    <input type="text" name="api" id="api" value="<?php echo $_GET['api'] ?>" /> 
			    <br/>
			    <label for="name">PAGE</label>
			    <input type="text" name="page" id="page" value="<?php echo $page ?>" />
			    <br/>
			    <label for="name">DATA KEY</label>
			    <input type="text" name="key" id="key" value="<?php echo $_GET['key'] ?>" /> (domain_expiration)
			    <br>
			    <label for="name">DATA VALUE</label>
			    <input type="text" name="value" id="valye" value="<?php echo $_GET['value'] ?>" /> (null)
			    <br>
			    <input type="submit" name="submit" id="submit" />
			</form>	
                   
<?php
	
if ($_GET['api'] !=""){
	
///////////////////////////////////////////////////////////////////////////
// get pages and monitor IDS
///////////////////////////////////////////////////////////////////////////
	
	$url = "https://betteruptime.com/api/v2/monitors?page=$page";
	$api = $_GET['api'];
	
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
		
	// DISPLAY JSON RESULT
	//echo pretty_print($response);
	
	// json to php array
	$phparray = json_decode($response, true);
	
	// pagination data only 
	$pages = ($phparray[pagination]);
	$nextpage = $pages[next];	
	$lastpage = $pages[last];
	
	$totalpages = substr($pages[last], -1);
		
	// get rid of pagination data
	$data = ($phparray[data]);
	
	// get monitor ids from array
	$ids = array_map(function ($value) {
	    return $value['id'];
	}, $data);
	
	
	// DISPLAY FULL ARRAY
	//echo "<pre>";
	//print_r($phparray); // FULL RESULT
	//print_r($data); 	  // DATA ONLY, NO PAGINATION 
	//echo "</pre>";
	
	if ($page <= $totalpages){
		echo "<h2>Monitor IDS (Page $page of $totalpages)</h2><pre>";
		print_r($ids);
		echo "</pre>";
	} else {
		echo "<p>error: page number out of bounds</p>";
	}

///////////////////////////////////////////////////////////////////////////
// loop through monitors and apply action
///////////////////////////////////////////////////////////////////////////

if ($_GET['key'] !="") {
	
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