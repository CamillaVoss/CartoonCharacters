<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}



?>

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
                echo 'Character updated' . '</br>';
            }
            else {
                echo 'No information was updated';
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
    <body style="margin: 50px auto; text-align: center;">
        <?php
            while ($stmt->fetch()) { ?>
                <img src="uploads/<?=$image?>" alt="Profile Picture">
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

        <form action="characterdetails.php?<?=$_SERVER['QUERY_STRING']?>" method="post">
            <input type="hidden" name="characterid" value="<?=$chid?>">
            <input type="text" name="charactername" value="<?=$chname?>" placeholder="Charactername" required >
            <input type="text" name="characterage" value="<?=$chage?>" placeholder="Characterage" required >
            <input type="text" name="characterdescription" value="<?=$chdescription?>" placeholder="Characterdescription" required >
            <button name="cmd" value="rename_character" type="submit">Edit</button>
        </form>

        <hr>
        <h1>Upload image</h1>

        <form action="characterdetails.php?<?=$_SERVER['QUERY_STRING']?>" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>

    </body>
</html>