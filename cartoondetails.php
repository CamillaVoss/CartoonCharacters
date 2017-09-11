<?php
    $cid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                    or die('could not get cartoon id for list');
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

    if($cmd = filter_input(INPUT_POST, 'cmd')){
    
        if($cmd == 'rename_cartoon'){
            $cid = filter_input(INPUT_POST, 'cartoonid', FILTER_VALIDATE_INT)
                or die('Missing/illegal cartoon parameter');
            $ctitle = filter_input(INPUT_POST, 'cartoontitle')
                or die('Missing/illegal cartoon parameter');
            
            require_once('db_con.php');
            $sql = 'UPDATE cartoon SET title = ? WHERE idCartoon=?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('si', $ctitle, $cid);
            $stmt->execute();
            
            if($stmt->affected_rows >0){
                echo 'Cartoon title updated to '.$ctitle;
            }
            else {
                echo 'Could not change title of cartoon '.$cid;
            }

            $param = $_SERVER['QUERY_STRING'];

        }
        else {
            die('Unknow cmd parameter: '.$cmd);
        }
    } 
    header("Location: cartoondetails.php?cartoonid=$cid");           
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

        <hr>

        <h1>Edit cartoon</h1>

        <?php
            if(empty($cid)){
            $cid = filter_input(INPUT_GET, 'cartoonid', FILTER_VALIDATE_INT)
                or die('Could not get cartoon id');   
            }

            require_once('db_con.php'); 
            $sql = 'SELECT title FROM cartoon WHERE idCartoon = ?';
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $cid);
            $stmt->execute();
            $stmt->bind_result($ctitle);

            while ($stmt->fetch()) {}
        ?>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="hidden" name="cartoonid" value="<?=$cid?>">
            <input type="text" name="cartoontitle" value="<?=$ctitle?>" placeholder="Cartoontitle" required >
            <button name="cmd" value="rename_cartoon" type="submit">Edit</button>
        </form>

    </body>
</html>