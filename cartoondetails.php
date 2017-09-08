<?php
    $cid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                    or die('nope');
            require_once('db_con.php');    
            $sql = 'SELECT cc.Character_idCharacters, ch.name
                    FROM Cartoon c, Character_Cartoon cc, CartoonCharacters.Character ch
                    WHERE ch.idCharacters = cc.Character_idCharacters
                    AND c.idCartoon = cc.Cartoon_idCartoon
                    AND c.idCartoon = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $cid);
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

        <h3>Characters from cartoon</h3>

        <ul>
        <?php
            while ($stmt->fetch()) { ?>
                <li><a href="characterlist.php?cartoonid=<?=$chid?>"><?=$chname?></a></li>
          <?php  } ?>

        </ul>
    </body>
</html>