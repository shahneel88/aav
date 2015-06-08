<?php
include('db.php');
if(isset($_SESSION['id']))
{
    header("location: appointment.php");
}
$$message = '';
if(isset($_POST['action']))
{
    if($_POST['action']=="login")
    {
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $password = mysqli_real_escape_string($conn,$_POST['pass']);
        $query = "select * from ap_users where email='".$email."' and password='".md5($password)."'";
        $result = $conn->query($query);
        if($result->num_rows == 1)
        {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            header("location: appointment.php");

        }
        else
        {
            $message = "Invalid email or password!!";
        }
    }

}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Log-In</title>
    <style>
	#Sign-Up 
	{
    	margin-top: 10%;
		margin-left: 30%;
		width: 40%;
    }
	input[type="text"] {
		width: 100%;
	}
	input[type="password"] {
		width: 100%;
}
	</style>
  </head>
  
     <body id="body-color"> 
     <div id="Sign-Up"> 
     	<fieldset>
        <legend>Login Form</legend>
        <table border="0" style="width:100%">
            <form method="POST" action="">
                <tr>
                    <td colspan="2">
                        <?php
                        if($message != '')
                        {
                            echo $message;
                        }
                        ?>
                    </td>
                </tr>
        <tr> 
        	<td>Email</td>
            <td> <input type="text"size="28" name="email"></td> 
        </tr> 
        <tr> 
        	<td>Password</td>
            <td> <input type="password" size="28" name="pass"></td> 
        </tr> 
        <tr>
         <td><a style="text-decoration:none;" href="signup.php">For sign-up click here </a></td>
         <td><input style="float: right" id="button" type="submit" name="action" value="login"></td>
         </tr>
            </form>
         </table> </fieldset> 
      </div> 
      </body> 
</html>

