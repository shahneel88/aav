<?php
include("db.php");
if(isset($_SESSION['id']))
{
    header("location: appointment.php");
}
$$message = '';
if($_POST['action']=="signup")
{
    $name       = mysqli_real_escape_string($conn,$_POST['name']);
    $email      = mysqli_real_escape_string($conn,$_POST['email']);
    $password   = mysqli_real_escape_string($conn,$_POST['pass']);
    $query = "SELECT email FROM ap_users where email='".$email."'";
    $result = $conn->query($query);
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $message =  "<p style='color:red'> Email ".$email." is not valid </p>";
    }
    elseif($name == '')
    {
        $message =  "<p style='color:red'> Name is required Field </p>";
    }
	elseif($_POST['pass'] == '')
    {
        $message =  "<p style='color:red'> Password is required Field </p>";
    }
	elseif(!($_POST['pass'] == $_POST['cpass'] ))
    {
        $message =  "<p style='color:red'> Password is not match</p>";

    }
    elseif ($result->num_rows > 0)
    {
        $message = $email." Email already exist!!";
    }
    else
    {
        $sql = "insert into ap_users(name,email,password) values('".$name."','".$email."','".md5($password)."')";
        if ($conn->query($sql) === TRUE) {
            $message =  "Signup Sucessfully!!";
        } else {
            $message =  "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();

    }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Sign-Up</title>
    <style>
	#Sign-Up 
	{
    	margin-top: 10%;
		margin-left: 30%;
        width: 40%;
    }
    input[type="password"] {
        width: 100%;
    }
    input[type="text"] {
        width: 100%;
    }
	</style>
  </head>
  
     <body id="body-color"> 
     <div id="Sign-Up">
     	<fieldset>
        <legend>Registration Form</legend> 
        <table border="0" style="width: 100%">
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
        	<form method="POST" action="">
        	<td>Name</td>
        	<td> <input type="text" size="28" name="name"></td> 
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
        	<td>Confirm Password </td>
        	<td><input type="password" size="28" name="cpass"></td> 
        </tr>
         <tr>
            <td><a style="text-decoration:none;" href="index.php">For Login click here </a></td>
         	<td><input style="float: right" id="button" type="submit" name="action" value="signup"></td>
         </tr> </form> 
         </table> </fieldset> 
      </div> 
      </body> 
</html>