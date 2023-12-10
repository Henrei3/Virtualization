<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><? echo $Pagetitle; ?></title>
</head>
<body>
    <?php
    if(isset($cheminVueBody)){
        require __DIR__."/{$cheminVueBody}";
    }
    echo "This file exists";
    ?>
</body>
</html>