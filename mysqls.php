<?php
// create connection  ---> use login creds to access

$mysqli = mysqli_connect("oniddb.cws.oregonstate.edu", "grahamb2-db", "UFrfDyJHu2qOuuKD", "grahamb2-db");
if (mysqli_connect_errno()){
	echo "Failed.";
}
else{
	//echo "Great, connected";
}
//variable to Create DB Table -->

$sqltable = "CREATE TABLE videos ( 		
id INT AUTO_INCREMENT PRIMARY KEY,
Video VARCHAR(255) UNIQUE NOT NULL,
Category VARCHAR(255) NOT NULL,
Length INT,
Rented VARCHAR(30) NOT NULL
)";

if ($mysqli->query($sqltable) === TRUE){		//----> Create table
	echo "First video added successfully.<br><br>";
} else{
	"Error creating table: " . $mysqli->error;
}

if (isset($_GET['video']) && empty($_GET['video'])){						// if table is created, make sure GET request for 'video' was not blank
	echo "You must enter a name.";
}
$lengthVar;
if (isset($_GET['length'])){
	$lengthVar = $_GET['length'];
	if ($lengthVar <= 0 && !empty($_GET['video'])){
		if (empty($_GET['length'])){}
		else {echo "<br>Length must be greater than 0 (in minutes)";}
	}
}
if (!empty($_GET['video'])){
	if (empty($_GET['length']) || $lengthVar > 0){
		insertTable($mysqli);
	}
	
} // run function insertTable (adds data to DB)

function insertTable($mysqli){
	$insertsql = "INSERT INTO videos (Video, Category, Length, Rented)		
	VALUES('$_GET[video]', '$_GET[category]', '$_GET[length]', 'Available')";	//Insert variables from Get to Table
	if (mysqli_query($mysqli, $insertsql)){
		echo "New Video added successfully<br><br>";
	} else{
		if (mysqli_errno($mysqli) == 1062){echo "Duplicate Value. Video already exists.<br>";}
		else{echo "Error: " . $insertsql . "<br>" . mysqli_error($mysqli);}
	}
}
$select = "SELECT * FROM videos";
$result = mysqli_query($mysqli, $select);						//Code to display table --> setup with xmlRequest? or?
mysql_close($mysqli);
?>
