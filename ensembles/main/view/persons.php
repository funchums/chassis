<html>
    <head><title><?php echo $title; ?></title></head>
    <body>
        
        <?php
        echo '<table>';            
        for ($row = 0; $row < count($result); $row++)
        {
         
                echo '<tr>';
                echo '<td>'.$result[$row]["ID"].'</td>';
                echo '<td>'.$result[$row]["NAME"].'</td>';
                echo '</tr>';
    
        }
        echo '</table>';
        ?>
    </body>
</html>
