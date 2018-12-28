<?php
require_once 'lib/account.php';
$error = '';
if(!empty(@$_POST['submit']))
{
	if(!account_create($_POST))
	{
		$error = '<error>' . account_error() . '</error>';
	}
	else
	{
		$error = '<success>Success!</success>';
	}
}
?>
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
            <header>
                <h1>Add User</h1>
            </header>
        
                <form method="post" action="" style="margin: 0;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%); display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 1em;">
	<?=$error?>
                    <label class="inputLabel">First Name</label>
                                        
                    <label class="inputLabel">Last Name</label>
                    
                    <input type="text" name="firstname" value placeholder="John" maxlength="25" size="25" style="margin-bottom: 10px;">
                                        
                    <input type="text" name="lastname" value placeholder="Jingleheimerschmidt" size="25" style="margin-bottom: 10px;">
                    
                    <label class="inputLabel">User Name</label>
                    
                    <label class="inputLabel">Temp Password</label>
                    
                    <label class="inputLabel">Employee ID</label>
                    
                    <input type="text" name="username" value placeholder="JJingle"  size="25" style="margin-bottom: 10px;">
                    
                    <input type="password" name="password" value placeholder=""  size="25" style="margin-bottom: 10px;">
                    
                    <input type="text" name="employeeid" value placeholder=""  size="25" style="margin-bottom: 10px;">
                    
                    <label class="inputLabel">Email</label>
                    
                    <label class="inputLabel">Start Date</label>
                                        
                    <input type="text" name="email" value placeholder="Jsmih@example.com"  size="25" style="margin-bottom: 10px;">
                    
                    <input type="date" name="startdate" value placeholder="Start Date" maxlength="10" size="10" style="margin-bottom: 10px;">
                    
                    <button>Activate</button>
                    
                    <input type="submit" name="submit" value="Save" style="">
                    
                    <input type="reset" name="reset" value="Cancel">
                </form>
    </body>
</html>
