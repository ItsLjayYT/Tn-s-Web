<?php
 session_start();
 
 if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
 }
    require "element/_nav.php";
    require "cred/_dbconnect.php";
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/panel.css" rel="stylesheet">
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
    <title>TN Admin Panel</title>
  </head>
  
<body>
  <br>
<div class="container-sm" style="color: white;">
  <div class="card bg-transparent navbar-glass">
   <h5 class="card-header" style="color: #e0ffcd;"><b>Key Management</b></h5>
   <div class="card-body bg-transparent">
    <h5 class="card-title">Genrate Your Keys Here</h5>
    <p class="card-text">With this panel you can easily manage your keys !</p>
    <?php 
        if ($dbconnect) {
          echo'<p class="card-text"><b>Databse Status</b> : Connected</p>';
        }
    ?>
    <form id="lgform" method="post">
  <div class="mb-3">
    <label for="newkey" class="form-label">Key</label>
    <input type="newkey" class="form-control" id="keyInput" name="newkey">
  </div>
  <div class="mb-3">
   <label for="dateInput">Select Expire Date:</label>
   <input type="text" id="date" name="expdate" class="form-control datepicker" required>
  </div>
    <button type="Register" name="register" type="reset" class="btn btn-primary">Submit</button>
    <button type="button" onclick="displayKey()"  class="btn btn-success">Random Key</button>
    
    <?php
    if(isset($_POST['register'])){
    $err = "";
    $NewKey = $_POST['newkey'];
    $Expiration = $_POST['expdate'];
    $date      = date("Y-m-d");
    $sql = "SELECT * FROM `userkeys` WHERE `CreatedKeys` = '$NewKey'";
    $result = mysqli_query($dbconnect, $sql);
    if ($result->num_rows > 0) {
      echo '<script type="text/javascript">alert("key alrady exist");</script>';
    } else {
      $exist = false;
      $sql = "INSERT INTO `userkeys` (`CreatedKeys`, `StartDate`, `EndDate`, `Time`) VALUES ('$NewKey', '$date', '$Expiration', 'current_timestamp()');";
      $result = mysqli_query($dbconnect, $sql);
      unset($_SESSION['register']);
      header("Location: panel.php");
      exit;
    }
  }
    ?>
</div></div>
<br>
<div class="card bg-transparent navbar-glass card">
  <h5 class="card-header" style="color: #e0ffcd;">Keys</h5>
  <div class="card-body bg-transparent">
  <p>These are all the keys registered in our database:</p>            
  <table class="table table-hover">
    <thead style="color: #e0ffcd;">
      <tr class="info">
        <th>Keys</th>
        <th>Created On</th>
		    <th>Expaire Date</th>
		    <th>Status</th>
        <th>Delete Key</th>
      </tr>
    </thead>
    <tbody>

<?php
$fetchqry = "SELECT * FROM `userkeys`";
$result=mysqli_query($dbconnect,$fetchqry);
$num=mysqli_num_rows($result);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { ?>
	   <tr style="color: white;">
        <td><?php echo $row['CreatedKeys'];?></td>
        <td><?php echo $date1 = $row['StartDate'];?></td>
		<td><?php echo $date2 = $row['EndDate'];?></td>
		<td><?php if(strtotime(date("Y-m-d")) <= strtotime($date2)) echo "Active"; else { echo "Expired";} ?></td>
		<?php
      {
         echo "<td> <a href='deleter.php?no=".$row['CreatedKeys']."'><button type='button' class='btn btn-danger'>Remove</button></a> </td>";
      }
      ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
    </div>
</div>
<script>
function myFunction($lol) {
<?php
$delete = "SELECT * FROM `userkeys`";
?>
    
}
</script>
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>

     <script>
        function generateKey(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

        function displayKey() {
            const key = generateKey(20); // Generate a 20-character key
            document.getElementById("keyInput").value = key; // Set it in the input field
        }
    </script>
</body>
</html>