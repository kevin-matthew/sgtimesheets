<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="CSS/tags.css">
        <link rel="stylesheet" type="text/css" href="CSS/classes.css">
        <link rel="stylesheet" type="text/css" href="CSS/main.css">
        <link rel="stylesheet" type="text/css" href="CSS/forms.css">
        <link rel="stylesheet" type="text/css" href="CSS/mobile.css"> 
    </head>
    <body>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" style="margin: 0;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">
                    
                    <input type="text" name="user" value placeholder="Username" maxlength="255" size="25" style="margin-bottom: 10px;">
                    <br>
                    
                    <input type="password" name="pass" value placeholder="Password" maxlength="255" size="25" style="margin-bottom: 10px;">
                    <br>
                    
                    <input type="submit" name="submit" value="Login" style="position: relative; left: 50%; margin-left: -27px; text-align: center;">
                </form>
                <div><?php echo $error;?></div>
    </body>
</html>