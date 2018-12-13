<!DOCTYPE html>
<html>
    <head>
        <title>Index Page</title>
    </head>
    <body>
  
            <?=$input2;?> <br>
            <?=$_SESSION["sera"];?><br>
            <?=$key;?>

        <form method="POST" action="http://localhost/musika/main/main/checkhash">
            
            <input type="text" name="key" value="<?=$key;?>">
            <button type="submit">Verify</button>
        </form>
    </body>
</html>


