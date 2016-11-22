<?php require('database.php');
        require('viewTickets.php');?>
<form method="POST">
Package ID: <input type="text" name="pid">
<input type="submit" value="DELETE">
</form>
<?php
$seats = json_decode(getSeats($_POST['pid']),true);
$pid = $_POST['pid'];
foreach($seats as $seat){
    $seatid = $seat['Seat_Id'];
    $sql = "DELETE FROM `seat2listing` WHERE `Seat_Id` = ".$seatid;
    run($sql);
    $sql = "DELETE FROM `seat` WHERE `Seat_Id` = ".$seatid;
    run($sql);
}
    $sql = "DELETE FROM `listing` WHERE `Package_Id` = ".$pid;
    run($sql);
    $sql = "DELETE FROM `package` WHERE `Package_Id` = ".$pid;
    run($sql);
?>