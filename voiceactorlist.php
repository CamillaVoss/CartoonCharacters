<?php
    if ($cmd = filter_input(INPUT_POST, 'cmd')) {
        if ($cmd == 'create_voiceactor') {
            $vname = filter_input(INPUT_POST, 'voiceactorname')
                or die('nope');

            require_once('db_con.php'); 
            $sql = 'INSERT INTO VoiceActor(name) 
                    VALUES (?)';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('s', $vname);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $create_succes = true;
            };

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
        <title>Voice Actor List</title>
    </head>
    <body>
        <h1>Voice Actors</h1>
        <ul>
          
        <?php
            require_once('db_con.php'); 
            $sql = 'SELECT idVoiceActor, Name 
                    FROM VoiceActor
                    ORDER BY Name ASC';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idv, $name);

            while ($stmt->fetch()) { ?>
                <li><a href="voiceactordetails.php?voiceactorid=<?=$idv?>"><?=$name?></a></li>    
        <?php } ?> 

        </ul>

        <h1>Create Voice Actor</h1>

        <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
            <input type="text" name="voiceactorname" required >
            <button name="cmd" value="create_voiceactor" type="submit">Create</button>
        </form>

        </br>   
        <?php
            if ($create_succes) {
                echo 'Created voice actor ' . $vname;
            }
        ?>   

    </body>
</html>