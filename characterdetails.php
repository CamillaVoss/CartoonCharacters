<?php
    $chid = filter_input(INPUT_GET, 'characterid', FILTER_VALIDATE_INT)
                    or die('nope');
            require_once('db_con.php');    
            $sql = 'SELECT Name, Image, Age, Description
                    FROM CartoonCharacters.Character
                    WHERE idCharacters = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $chid);
            $stmt->execute();
            $stmt->bind_result($name, $image, $age, $description);
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Details</title>
    </head>
    <body>
        <?php
            while ($stmt->fetch()) { ?>
                <img src="img/<?=$image?>" alt="Profile Picture">
                <h2><?=$name?></h2>
                <h4><strong>Age:</strong> <?=$age?></h4>
                <p><?=$description?></p>


          <?php  } ?>

        </ul>
    </body>
</html>