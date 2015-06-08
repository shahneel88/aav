<?php
include('db.php');
if(!isset($_SESSION['id']))
{
    header("location: index.php");
}
$fname = '';
$lname = '';
$date ='';
$time = '';
$message = '';
$id='';

if(isset($_POST))
{
    if($_POST['action']== 'save appointment')
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $date = $_POST['ap_date'];
        $time = $_POST['time'];
        $query = "SELECT * FROM ap_appointment where ap_date='".date('Y-m-d',strtotime($date))."' and time_id=".$time;
        $result = $conn->query($query);
        if($fname == '')
        {
            $message = 'First name must required';
        }
        elseif($lname == '')
        {
            $message = 'Last name must required';
        }
        elseif($date == '')
        {
            $message = 'Date must required';
        }
        elseif(date(date('d-m-Y',strtotime($date))< date('d-m-Y')))
        {
            $message = 'Date not valid';
        }
        elseif($time == '' || $time == 0 )
        {
            $message = 'Time must required';
        }
        elseif($result->num_rows > 0)
        {
            $message = 'This time block is already taken please select different time';

        }
        else
        {
            $sql = "INSERT INTO ap_appointment (first_name, last_name, ap_date,time_id,user_id) VALUES ('".$fname."', '".$lname."', '".date('Y-m-d',strtotime($date))."',".$time.",".$_SESSION['id'].")";

            if ($conn->query($sql) === TRUE) {
                header("location: appointment.php");
            } else {
                $message =  "Error: " . $sql . "<br>" . $conn->error;
            }
        }

    }
    elseif($_POST['action']== 'edit')
    {
        $query = "SELECT * FROM ap_appointment where id=".$_POST['ap_id'];
        $result = $conn->query($query);
        if($result->num_rows >0)
        {
            $row = $result->fetch_assoc();
            $fname = $row['first_name'];
            $lname = $row['last_name'];
            $date = date('m/d/Y',strtotime($row['ap_date']));
            $time = $row['time_id'];
            $id = $row['id'];
        }
    }
    elseif($_POST['action']== 'reschedule appointment')
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $date = $_POST['ap_date'];
        $time = $_POST['time'];
        $id = $_POST['id'];
        $query = "SELECT * FROM ap_appointment where ap_date='".date('Y-m-d',strtotime($date))."' and time_id=".$time ." and id != ".$id;
        $result = $conn->query($query);
        if($fname == '')
        {
            $message = 'First name must required';
        }
        elseif($lname == '')
        {
            $message = 'Last name must required';
        }
        elseif($date == '')
        {
            $message = 'Date must required';
        }
        elseif($time == '' || $time == 0 )
        {
            $message = 'Time must required';
        }
        elseif($result->num_rows > 0)
        {
            $message = 'This time block is already taken please select different time';

        }
        else
        {
            $sql = "UPDATE ap_appointment SET first_name='".$fname."',last_name = '".$lname."',ap_date = '".date('Y-m-d',strtotime($date))."',time_id=".$time." WHERE id=".$id;
            if (mysqli_query($conn, $sql)) {
                header("location: appointment.php");
            } else
            {
                $message =  "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


}
$query = "select * from ap_time";
$result = $conn->query($query);
?>
<html>
    <head>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <style>
        body{
            margin-top: 5%;
            margin-left: 30%;
            margin-right: 30%;
        }
        select {
            width: 100%;
        }
        input[type="date"] {
            width: 100%;
        }
        input[type="text"] {
            width: 100%;
        }
        .datepickr-wrapper.open,.datepickr-wrapper {
            width: 100% !important;
        }
    </style>
        <script>
            $(function() {
                $( "#datepicker" ).datepicker();
            });
        </script>
    </head>
    <body>
    <fieldset>
        <legend>Appointment Form</legend>
        <table border="0" style="width: 100%">
            <tr style="color: red">
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
                    <td>First name</td>
                    <td> <input type="text" value="<?php echo($fname==''?'':$fname);?>" name="fname"></td>
            </tr>
            <tr>
                <td>Last name</td>
                <td> <input type="text" value="<?php echo($lname==''?'':$lname);?>" name="lname"></td>
            </tr>
            <tr>
                <td>Date</td>
                <td> <input type="text" value="<?php echo($date==''?'':$date);?>" id="datepicker" name="ap_date"></td>
            </tr>
            <tr>
                <td>Time</td>
                <td>
                    <select name="time">
                        <option value="0">Select time</option>
                        <?php
                        if($result->num_rows >0)
                        {
                            while($row = $result->fetch_assoc())
                            {
                                echo '<option '.($row['id'] == $time?'selected':'').' value="'.$row['id'].'">'.$row['time'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </td>

            </tr>
            <tr>
                <?php
                    if(!$id == '')
                    {
                        echo '<input type="hidden" name="id" value="'.$id.'">';
                    }
                ?>

                <td colspan="2"><input style="float: right" id="button" type="submit" name="action" value="<?php echo ($id == ''?'save appointment':'reschedule appointment'); ?>"></td>
            </tr> </form>
        </table> </fieldset>
    </body>

</html>