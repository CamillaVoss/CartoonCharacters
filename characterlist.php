<?php
    $cmd = filter_input(INPUT_POST, 'cmd');
    if ($cmd) {
        if ($cmd == 'create_character') {
            $chname = filter_input(INPUT_POST, 'charactername')
                or die('nope');
            $voiceactorid = filter_input(INPUT_POST, 'voiceactorid')
                or die('nope');
            $cartoonid = filter_input(INPUT_POST, 'cartoonid')
                or die('nope');

            require_once('db_con.php');
            $con->autocommit(FALSE);
            $con->begin_transaction();

            $sql = 'INSERT INTO CartoonCharacters.Character(name)
                    VALUES (?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('s', $chname);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };
            $characterid = $con->insert_id;

            $sql = 'INSERT INTO Character_VoiceActor(VoiceActor_idVoiceActor, Character_idCharacters) 
                    VALUES (?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $voiceactorid, $characterid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $sql = 'INSERT INTO Character_Cartoon(Cartoon_idCartoon, Character_idCharacters) 
                    VALUES (?, ?)';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('ii', $cartoonid, $characterid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $con->commit();
            $create_succes = true;

        } elseif ($cmd == 'delete_character') {
            $chid = filter_input(INPUT_POST, 'characterid', FILTER_VALIDATE_INT)
                or die('nope');

            require_once('db_con.php');
            $con->autocommit(FALSE);
            $con->begin_transaction();

            $sql = 'DELETE FROM Character_VoiceActor 
                    WHERE Character_idCharacters=?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $chid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $sql = 'DELETE FROM Character_Cartoon 
                    WHERE Character_idCharacters=?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $chid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $sql = 'DELETE FROM CartoonCharacters.Character
                    WHERE idCharacters=?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $chid);

            if (!$stmt->execute()) {
                $con->rollback();
                die($con->error);
            };

            $con->commit();
            echo "Deleted Character";

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
                <li>
                    <a href="characterdetails.php?characterid=<?=$idch?>"><?=$name?></a>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
                        <input type="hidden" name="characterid" value="<?=$idch?>">
                        <button type="submit" name="cmd" value="delete_character">Delete</button>
                    </form>
                </li> 
		<?php } ?>  
        </ul>

        <hr>

        <h1>Create Character</h1>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="text" name="charactername" required >
            <select name="cartoonid">
                <option value="none">Choose cartoon</option> 
            <?php
                $sql = 'SELECT idCartoon, Title 
                        FROM Cartoon
                        ORDER BY Title ASC';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($idc, $ctitle);
                
                 while ($stmt->fetch()) { ?>
                    <option value="<?=$idc?>"><?=$ctitle?></option> 
            <?php } ?>  
            </select>
            <select name="voiceactorid">
                <option value="none">Choose voice actor</option> 
            <?php
                $sql = 'SELECT idVoiceActor, Name 
                        FROM VoiceActor
                        ORDER BY Name ASC';
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $stmt->bind_result($idv, $vname);
                
                 while ($stmt->fetch()) { ?>
                    <option value="<?=$idv?>"><?=$vname?></option> 
            <?php } ?>  
            </select>       
            <button name="cmd" value="create_character" type="submit">Create</button>
        </form>

        </br>   
        <?php
            if ($create_succes) {
                echo 'Created cartoon ' . $ctitle;
            }
        ?>  

    </body>
</html>