<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Character List</title>
    </head>
    <body>
    	<h1>Characters</h1>
    	<ul>
    	  
    	<?php
			require_once('db_con.php');	
			$sql = 'SELECT idCharacters, Name 
                    FROM CartoonCharacters.Character
                    ORDER BY Name ASC';
			$stmt = $con->prepare($sql);
			$stmt->execute();
			$stmt->bind_result($idch, $name);

			while ($stmt->fetch()) { ?>
				<li><a href="characterdetails.php?characterid=<?=$idch?>"><?=$name?></a></li>	
		<?php } ?>    

    </body>
</html>