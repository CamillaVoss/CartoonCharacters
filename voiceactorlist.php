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

    </body>
</html>