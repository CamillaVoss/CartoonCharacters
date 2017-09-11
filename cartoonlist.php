<?php
	if ($cmd = filter_input(INPUT_POST, 'cmd')) {
		if ($cmd == 'create_cartoon') {
			$ctitle = filter_input(INPUT_POST, 'cartoontitle')
				or die('nope');

			require_once('db_con.php');	
			$sql = 'INSERT INTO Cartoon(title) 
					VALUES (?)';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('s', $ctitle);
			$stmt->execute();

			if ($stmt->affected_rows > 0) {
				$create_succes = true;
			};

		} elseif ($cmd == 'delete_cartoon') {
			$cid = filter_input(INPUT_POST, 'cartoonid', FILTER_VALIDATE_INT)
				or die('nope');

			require_once('db_con.php');	
			$sql = 'DELETE FROM Cartoon WHERE idCartoon=?';
			$stmt = $con->prepare($sql);
			$stmt->	bind_param('i', $cid);
			$stmt->execute();

			if ($stmt->affected_rows > 0) {
				echo "Deleted cartoon";
			} else {
				echo "Can't delete cartoon. You must delete all characters from cartoon first";
			 };		

		} else {
			die('Unknown cmd parameter ' . $cmd);
		}
	}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cartoon List</title>
    </head>
    <body>
    	<h1>Cartoons</h1>
    	<ul>
    	  
    	<?php
			require_once('db_con.php');	
			$sql = 'SELECT idCartoon, Title 
					FROM Cartoon
					ORDER BY Title ASC';
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($idc, $title);

			while ($stmt->fetch()) { ?>
				<li>
					<a href="cartoondetails.php?cartoonid=<?=$idc?>"><?=$title?></a>
					<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
						<input type="hidden" name="cartoonid" value="<?=$idc?>">
						<button type="submit" name="cmd" value="delete_cartoon">Delete</button>
					</form>
				</li>	
		<?php } ?> 
		</ul>

		<hr>

		<h1>Create cartoon</h1>

    	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
    		<input type="text" name="cartoontitle" required >
    		<button name="cmd" value="create_cartoon" type="submit">Create</button>
    	</form>

    	</br>   
    	<?php
    		if ($create_succes) {
    			echo 'Created cartoon <strong>' . $ctitle . '</strong>';
    		};
    	?>

    </body>
</html>