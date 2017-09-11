<?php
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
    </body>
</html>