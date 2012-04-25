<?php
	// Opens a connection to a MySQL server
				$username="root";
				$password="root";
				$database="thinksns_2_5";
				$connection=mysql_connect (localhost, $username, $password);
				if (!$connection) {
				  die('Not connected : ' . mysql_error());
				}

				// Set the active MySQL database
				$db_selected = mysql_select_db($database, $connection);
				if (!$db_selected) {
				  die ('Can\'t use db : ' . mysql_error());
				}

				// Select all the rows in the markers table
				$query = "SELECT * FROM markers WHERE 1";
				$result = mysql_query($query);
				if (!$result) {
				  die('Invalid query: ' . mysql_error());
				}

				//
				$doc = new DOMDocument("1.0");
				
				$node = $doc->createElement("markers");
				$parnode = $doc->appendChild($node);
				header("Content-type: text/xml");
				while ($row = @mysql_fetch_assoc($result)){
				  // ADD TO XML DOCUMENT NODE
				  $node = $doc->createElement("marker");
				  $newnode = $parnode->appendChild($node);

				  $newnode->setAttribute("name", $row['name']);
				  $newnode->setAttribute("address", $row['address']);
				  $newnode->setAttribute("lat", $row['lat']);
				  $newnode->setAttribute("lng", $row['lng']);
				  $newnode->setAttribute("type", $row['type']);
				}

	             echo $doc->saveXML();

?>