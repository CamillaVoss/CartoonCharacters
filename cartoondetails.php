<?php
    // Checks whether button 'cmd' is clicked
    if($cmd = filter_input(INPUT_POST, 'cmd')){
        // renames cartoon
         if($cmd == 'rename_cartoon'){
            $cid = filter_input(INPUT_POST, 'cartoonid', FILTER_VALIDATE_INT)
                or die('Missing/illegal cartoon parameter');
            $ctitle = filter_input(INPUT_POST, 'cartoontitle')
                or die('Missing/illegal cartoon parameter');
            
            require_once('db_con.php');
            $sql = 'UPDATE Cartoon 
                    SET Title = ? 
                    WHERE idCartoon = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('si', $ctitle, $cid);
            $stmt->execute();

            if($stmt->affected_rows >0){
                echo 'Cartoon title updated to '.$ctitle;
            }
            else {
                echo 'Could not change title of cartoon '.$cid;
            }

        }
        else {
            die('Unknow cmd parameter: '.$cmd);
        }

    }
    // selects all characters whom appears in the cartoon, and displays them as a list if links
    $cid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                    or die('could not get cartoon id for list');
            require_once('db_con.php');    
            $sql = 'SELECT cc.Character_idCharacters, ch.Name
                    FROM Cartoon c, Character_Cartoon cc, mul_b.Character ch
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
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <?php include_once('nav.php') ?>

        <h3>Characters from cartoon</h3>

        <ul>
        <?php
            while ($stmt->fetch()) { ?>
                <li><a href="characterdetails.php?characterid=<?=$chid?>"><?=$chname?></a></li>
          <?php  } ?>

        </ul>

        <hr>

        <h1>Edit cartoon</h1>

        <?php
            // selects title from given cartoon, to use on the form as value
            if(empty($cid)){
            $cid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                or die('Could not get cartoon id');   
            }

            require_once('db_con.php'); 
            $sql = 'SELECT Title FROM Cartoon WHERE idCartoon = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $cid);
            $stmt->execute();
            $stmt->bind_result($ctitle);

            while ($stmt->fetch()) {}

             
        ?>

        <form action="cartoondetails.php?<?=$_SERVER['QUERY_STRING']?>" method="post">
            <input type="hidden" name="cartoonid" value="<?=$cid?>">
            <input type="text" name="cartoontitle" value="<?=$ctitle?>" placeholder="Cartoontitle" required >
            <button name="cmd" value="rename_cartoon" type="submit">Edit</button>
        </form>

    </body>
</html>