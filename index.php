
<?php echo'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>';
  
?>
<body>
	
<?php createDropDown();?>	
	<form action = "?tableFunc" method = "get">
		Enter Movie Title:&nbsp<input type = "text" name = "video" placeholder = "Movie Title"><br><br>
		Enter Category:&nbsp<input type = "text" name = "category" placeholder = "Category"><br><br>
		Enter Length:&nbsp <input type = "number" name = "length" placeholder = "Length of Movie"><br><br>
		<input type = "submit" value = "Submit">
	</form>
	<?php
	function tableFunc(){
		  //do something
		require('mysqls.php');
		echo "<table border = '1'>
		<tr><th>Title</th><th>Category</th><th>Length</th><th>Rented</th>";
		while($row = mysqli_fetch_array($result)){ 				//Loop fetches data, echoes and creates html table
			$vidname = $row['Video'];
			$category = $row['Category'];
			$length = $row['Length'];
			$rented = $row['Rented'];
			$id = $row['id'];
			echo "<tr><td style='width: 400px;'><form action='' method='get'><input type='hidden' name='delKey' value='$id'><input type='submit' name='submit' value='Delete'> &nbsp&nbsp " .$vidname."</td>";
			echo "<td style='width: 100px;'>".$category."</td><td style='width: 100px;'>".$length."</td>";
			echo "<td style='width: 180px;'>".$rented."  <input type='hidden' name='changeStatus' value='$id'><input type='submit' name = 'Status' value='Change Status'></td></form><br><br>";
		}
		echo "<tr><td><form action='' method='get'><input type='submit' name='submitall' value='Delete ALL'></td></tr></form></table>";	
	}?>
	<?php
	if (!isset($_GET['video']) && !isset($_GET['category']) && !isset($_GET['length'])){
		if (!isset($_GET['submit']) && !isset($_GET['submitall']) && !isset($_GET['filter'])){
			if (!isset($_GET['Status'])){tableFunc();}
		}
	}
	if (isset($_GET['video'])) {
        //do something
     	tableFunc();
    }  
	if (isset($_GET['submit'])){
		delFunc();
	}
	if (isset($_GET['submitall'])){
		delFunc();
	}
	if (isset($_GET['filter'])){
			if ($_GET['dropdown'] == ""){
				tableFunc();
			}else{filterTable();}
	}
	if (isset($_GET['Status'])){
		changeVidStatus();
		if (isset($_GET['filter'])){filterTable();}
		else{tableFunc();}
	}
	function delFunc(){
		$mysqli = mysqli_connect("oniddb.cws.oregonstate.edu", "grahamb2-db", "UFrfDyJHu2qOuuKD", "grahamb2-db");
		$id = $_GET['delKey'];
		if (isset($_GET['submitall'])){
			$sqly="DELETE FROM videos";
		}
		else{$sqly="DELETE FROM videos WHERE id = $id";}							// Need to use unique ids to delete
		if(mysqli_query($mysqli, $sqly) === TRUE){
			echo'Delete Was Successful.';
		}  
		else{echo 'fail';}	
		tableFunc();
	}
	function changeVidStatus(){
		$mysqli = mysqli_connect("oniddb.cws.oregonstate.edu", "grahamb2-db", "UFrfDyJHu2qOuuKD", "grahamb2-db");
		$id = $_GET['changeStatus'];
		$checkCurrent = "SELECT Rented FROM videos WHERE id = $id";
		if ($result = $mysqli->query($checkCurrent)){
			$obj = $result->fetch_object();
			if ($obj->Rented == 'Available'){$sqlUpdate="UPDATE videos SET Rented='Checked Out' WHERE id = $id";}
			else{$sqlUpdate="UPDATE videos SET Rented='Available' WHERE id = $id";}
		}
		if(mysqli_query($mysqli, $sqlUpdate) === TRUE){}  
	}
	function createDropDown(){						//THIS FUNCTION creates dropdown from mysql connection
													//SEARCHES for categories that exist
		$mysqlis = mysqli_connect("oniddb.cws.oregonstate.edu", "grahamb2-db", "UFrfDyJHu2qOuuKD", "grahamb2-db");
		$selects = "SELECT DISTINCT Category FROM videos";
		$results = mysqli_query($mysqlis, $selects);
		echo '<form action="" method = "get">							
		<select name="dropdown">
			<option value = "">All Movies</option>';
		while($row = mysqli_fetch_array($results)){
			$category = $row['Category'];
			if ($category != ""){echo '<option value="'.$category. '">'.$category.'</option>';}	
		}
		echo '</select>&nbsp&nbsp
		<input type = "submit" name = "filter" value = "Filter Movies">
		</form><br><br>';
	}
	function filterTable(){
		require('mysqls.php');
		echo "<table border = '1'>
		<tr><th>Title</th><th>Category</th><th>Length</th><th>Rented</th>";
		while($row = mysqli_fetch_array($result)){ 				//Loop fetches data, echoes and creates html table
			$vidname = $row['Video'];
			$category = $row['Category'];
			$length = $row['Length'];
			$rented = $row['Rented'];
			$id = $row['id'];
			if ($category == $_GET['dropdown']){
				echo "<tr><td style='width: 400px;'><form action='' method='get'><input type='hidden' name='delKey' value='$id'><input type='submit' name='submit' value='Delete'> &nbsp&nbsp " .$vidname."</td>";
				echo "<td style='width: 100px;'>".$category."</td><td style='width: 100px;'>".$length."</td>";
				echo "<td style='width: 180px;'>".$rented."  <input type='hidden' name='changeStatus' value='$id'><input type='submit' name='Status' value='Change Status'></td></form><br><br>";
			}
		}
		echo "<tr><td><form action='' method='get'><input type='submit' name='submitall' value='Delete ALL'></td></tr></form></table> ";
		
	}
	?>
</body>
</html>