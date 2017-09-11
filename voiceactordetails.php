<?php
    if($cmd = filter_input(INPUT_POST, 'cmd')){
         if($cmd == 'rename_voiceactor'){
            $vid = filter_input(INPUT_POST, 'voiceactorid', FILTER_VALIDATE_INT)
                or die('Missing/illegal voiceactor parameter');
            $vname = filter_input(INPUT_POST, 'voiceactorname')
                or die('Missing/illegal voiceactor parameter');
            
            require_once('db_con.php');
            $sql = 'UPDATE VoiceActor 
                    SET Name = ? 
                    WHERE idVoiceActor = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('si', $vname, $vid);
            $stmt->execute();

            if($stmt->affected_rows >0){
                echo 'Cartoon title updated to '.$vname;
            }
            else {
                echo 'Could not change title of cartoon '.$vid;
            }

        }
        else {
            die('Unknow cmd parameter: '.$cmd);
        }

    }

    $vid = filter_input(INPUT_GET, 'voiceactorid', FILTER_VALIDATE_INT)
                    or die('nope');
            require_once('db_con.php');    
            $sql = 'SELECT cv.Character_idCharacters, ch.name
                    FROM VoiceActor v, Character_VoiceActor cv, CartoonCharacters.Character ch
                    WHERE ch.idCharacters = cv.Character_idCharacters
                    AND v.idVoiceActor = cv.VoiceActor_idVoiceActor
                    AND v.idVoiceActor = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $vid);
            $stmt->execute();
            $stmt->bind_result($chid, $chname);
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Details</title>
    </head>
    <body>

        <h3>Actor voices these characters</h3>

        <ul>
        <?php
            while ($stmt->fetch()) { ?>
                <li><a href="characterlist.php?cartoonid=<?=$chid?>"><?=$chname?></a></li>
          <?php  } ?>

        </ul>

        <hr>
        <h1>Edit voice actor</h1>

        <?php
            if(empty($vid)){
            $vid = filter_input(INPUT_GET, 'voiceactorid', FILTER_VALIDATE_INT)
                or die('Could not get voiceactorid');   
            }

            require_once('db_con.php'); 
            $sql = 'SELECT name FROM VoiceActor WHERE idVoiceActor = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $vid);
            $stmt->execute();
            $stmt->bind_result($vname);

            while ($stmt->fetch()) {}
        ?>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="voiceactorid" value="<?=$vid?>">
            <input type="text" name="voiceactorname" value="<?=$vname?>" placeholder="VoiceActorName" required >
            <button name="cmd" value="rename_voiceactor" type="submit">Edit</button>
        </form>

    </body>
</html>