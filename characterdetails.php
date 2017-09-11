<?php
    if($cmd = filter_input(INPUT_POST, 'cmd')){
         if($cmd == 'rename_character'){
            $chid = filter_input(INPUT_POST, 'characterid', FILTER_VALIDATE_INT)
                or die('Missing/illegal character parameter');
            $chname = filter_input(INPUT_POST, 'charactername')
                or die('Missing/illegal character parameter');
            $chage = filter_input(INPUT_POST, 'characterage')
                or die('Missing/illegal character parameter');    
            $chdescription = filter_input(INPUT_POST, 'characterdescription')
                or die('Missing/illegal character parameter');        
            
            require_once('db_con.php');
            $sql = 'UPDATE CartoonCharacters.Character
                    SET Name = ?, Age = ?, Description = ?
                    WHERE idCharacters = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('sisi', $chname, $chage, $chdescription, $chid);
            $stmt->execute();

            if($stmt->affected_rows >0){
                echo 'Character updated';
            }
            else {
                echo 'Could not change character';
            }

        }
        else {
            die('Unknow cmd parameter: '.$cmd);
        }

    }

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
        <hr>
        <h1>Edit character</h1>

        <?php
            if(empty($chid)){
            $chid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                or die('Could not get cartoon id');   
            }

            require_once('db_con.php'); 
            $sql = 'SELECT name, age, description FROM CartoonCharacters.character WHERE idCharacters = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $chid);
            $stmt->execute();
            $stmt->bind_result($chname, $chage, $chdescription);

            while ($stmt->fetch()) {}
        ?>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="characterid" value="<?=$chid?>">
            <input type="text" name="charactername" value="<?=$chname?>" placeholder="Charactername" required >
            <input type="text" name="characterage" value="<?=$chage?>" placeholder="Characterage" required >
            <input type="text" name="characterdescription" value="<?=$chdescription?>" placeholder="Characterdescription" required >
            <button name="cmd" value="rename_character" type="submit">Edit</button>
        </form>
    </body>
</html>