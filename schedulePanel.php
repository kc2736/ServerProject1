<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}

$con = getDb();
$extraSQL = "";
if(isset($_SESSION['team'])){
    $extraSQL .= ' WHERE server_schedule.hometeam = '. $_SESSION['team'] .' OR server_schedule.awayteam = '.$_SESSION['team'];
}
if(isset($_SESSION['league'])){
    if($extraSQL != ''){
        $extraSQL.= " AND server_schedule.league = " . $_SESSION['league'];
    }else{
        $extraSQL.= " WHERE server_schedule.league = " . $_SESSION['league'];
    }
}
$extraSQL .= ';';
$res = $con->query('SELECT server_sport.name as sport, server_league.name as league, server_season.year as season, t1.name as hometeam, t2.name as awayteam, server_schedule.homescore, server_schedule.awayscore, server_schedule.scheduled, server_schedule.completed
                        FROM server_schedule
                        LEFT JOIN server_sport ON server_schedule.sport = server_sport.id
                        LEFT JOIN server_league ON server_schedule.league = server_league.id
                        LEFT JOIN server_season ON server_schedule.season = server_season.id
                        LEFT JOIN server_team t1 ON server_schedule.hometeam = t1.id
                        LEFT JOIN server_team t2 ON server_schedule.awayteam = t2.id' . $extraSQL);
if ($res->num_rows > 0) {
    $arr_users = $res->fetch_all(MYSQLI_ASSOC);
}
//var_dump($extraSQL);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/panel.css">
    <!--    BOOTSTRAP-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!--DataTable-->
    <link rel="stylesheet" href="libraries/DataTables-1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <title>Control Panel</title>
</head>
<body>
<nav class="navtop">
    <div>
        <h1>Sports manager</h1>
        <?php if($_SESSION['role'] !== 5) {?>
            <a href="controlPanel.php?type=admin">Admin</a>
        <?php }?>
        <a href="teamPanel.php">Team</a>
        <a href="schedulePanel.php">Schedule</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
    </div>
</nav>
<!--    <div class="adminTable">-->
<table id="scheduleTable" class="display" style="width:100%">
    <thead>
    <th>Sport</th>
    <th>League</th>
    <th>Season</th>
    <th>Home Team</th>
    <th>Away Team</th>
    <th>Home score</th>
    <th>Away score</th>
    <th>Scheduel</th>
    <th>Completed</th>
    <th>Actions</th>
    </thead>
    <tbody>
    <?php if(!empty($arr_users)) { ?>
        <?php foreach($arr_users as $user) { ?>
            <tr>
                <td><?php echo $user['sport']; ?></td>
                <td><?php echo $user['league']; ?></td>
                <td><?php echo $user['season']; ?></td>
                <td><?php echo $user['hometeam']; ?></td>
                <td><?php echo $user['awayteam']; ?></td>
                <td><?php echo $user['homescore']; ?></td>
                <td><?php echo $user['awayscore']; ?></td>
                <td><?php echo $user['scheduled']; ?></td>
                <td><?php if($user['completed'] === "1"){echo "Finished";}else{echo "Scheduled";}?></td>
                <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="libraries/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#scheduleTable').DataTable();
    });
</script>

</body>
</html>
