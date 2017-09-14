<?php
    // Checks whether button is clicked
    if ($cmd = filter_input(INPUT_POST, 'cmd')) {
        // Creates voice actor
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

        // Deletes voice actor
        } elseif ($cmd == 'delete_voiceactor') {
            $vid = filter_input(INPUT_POST, 'voiceactorid', FILTER_VALIDATE_INT)
                or die('nope');

            require_once('db_con.php'); 
            $sql = 'DELETE FROM VoiceActor WHERE idVoiceActor=?';
            $stmt = $con->prepare($sql);
            $stmt-> bind_param('i', $vid);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Deleted Voice Actor";
            } else {
                echo "Can't delete Voice Actor. You must delete all characters from Voice Actor first";
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
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <?php include_once('nav.php') ?>
        <h1>Voice Actors</h1>
        <ul>
          
        <?php
            // Display list of voice actors
            require_once('db_con.php'); 
            $sql = 'SELECT idVoiceActor, Name 
                    FROM VoiceActor
                    ORDER BY Name ASC';
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $stmt->bind_result($idv, $name);

            while ($stmt->fetch()) { ?>
            <li>
                <a href="voiceactordetails.php?voiceactorid=<?=$idv?>"><?=$name?></a>
                <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
                    <input type="hidden" name="voiceactorid" value="<?=$idv?>">
                    <button type="submit" name="cmd" value="delete_voiceactor">Delete</button>
                </form>
            </li> 
        <?php } ?> 

        </ul>

        <hr>

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