<?php
include('db.php');
if(!isset($_SESSION['id']))
{
    header("location: index.php");
}

$msg = '';
if(isset($_POST))
{
    if($_POST['action']== 'cancel')
    {
        // sql to delete a record
        $sql = "DELETE FROM ap_appointment WHERE id=".$_POST['ap_cancel_id'];

        if ($conn->query($sql) === TRUE) {
            $msg =  "Appointment canceled successfully";
        }
    }
}
$query = "select ap_appointment.*,ap_time.time from ap_appointment JOIN ap_time ON ap_appointment.time_id = ap_time.id where user_id=".$_SESSION['id'].' order by ap_date asc, time_id asc';
$result = $conn->query($query);
?>
<html>
    <head>
        <style>
            table, td, th {
                font-size: 18px;
                border: 1px solid blueviolet;
            }

            th {
                background-color: blueviolet;
                color: white;
            }
            td,th {
                text-align: center;
            }
            a.btn {
                background-color: blueviolet;
                color: white;
                font-size: 18px;
                font-weight: bold;
                border-radius: 7px;
                padding: 5px;
            }
            a.btn::choices{
                color: blueviolet;
                font-size: 18px;
                font-weight: bold;
                border-radius: 7px;
                padding: 5px;
                border: 1px solid;
                border-color: blueviolet;
            }
            #cl_btn
            {
                background: red;
                border-color: red;
                padding: 4px;
                border-radius: 8px;
                color: #fff;
                font-size: 16px;
            }
            #reschedule_btn {
                background: blue;
                border-color: blue;
                padding: 4px;
                border-radius: 8px;
                color: #fff;
                font-size: 16px;
            }
        </style>
    </head>
    <body style="margin: 5%">
    <div>
        <a id="cl_btn" class="btn"  style="text-decoration: none;margin-bottom:  5px;margin-right: 5px;" href="logout.php">Logout</a>
        <?php if($_SESSION['id'] == 2 ){ ?>
        <a id="reschedule_btn" class="btn"  style="text-decoration: none;margin-bottom:  5px;margin-right: 5px;" href="report.php">Today's appointment</a>
        <?php
        }

        if($msg != '')
        {
            echo $msg;
        }
        ?>
        <a class="btn" style="text-decoration: none;float: right;margin-bottom:  5px;" href="createappointment.php">Take a appointment</a>
    </div>
    <div>
      <table style="width: 100%">
          <thead>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Date</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if($result->num_rows >0)
          {
              while($row = $result->fetch_assoc())
              {
                  echo '<tr>';
                      echo '<td>'.$row["first_name"].'</td>';
                      echo '<td>'.$row["last_name"].'</td>';
                      echo '<td>'. date('d-m-Y',strtotime($row["ap_date"])).'</td>';
                      echo '<td>'.$row["time"].'</td>';
                      echo '<td> <button id="cl_btn" onclick="cancel_ap('.$row['id'].');">cancel</button> <button id="reschedule_btn" onclick="reschedule_ap('.$row['id'].');">reschedule</button> </td>';
                  echo '</tr>';
              }
          }
          ?>
          </tbody>
      </table>
    </div>
    <form id="editform" method="post" action="createappointment.php">
        <input type="hidden" value="" name="ap_id" id="ap_id" >
        <input type="hidden" value="edit" name="action" >
    </form>
    <form id="cancelform" method="post" action="#">
        <input type="hidden" value="" name="ap_cancel_id" id="ap_cancel_id" >
        <input type="hidden" value="cancel" name="action" >
    </form>
    <script>
        function cancel_ap(id) {

            var r = confirm("Are you sure you want to cancel this appointment ");
            if (r == true) {
                var elem = document.getElementById("ap_cancel_id");
                elem.value = id;
                document.getElementById("cancelform").submit();
            } else {

            }

        }
        function reschedule_ap(id)
        {
            var elem = document.getElementById("ap_id");
            elem.value = id;
            document.getElementById("editform").submit();
        }
    </script>
    </body>
</html>
