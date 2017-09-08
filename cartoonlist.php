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
				<li><a href="cartoondetails.php?cartoonid=<?=$idc?>"><?=$title?></a></li>	
		<?php } ?>    

    </body>
</html>