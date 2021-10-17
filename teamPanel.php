<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] === 1) {
    header('Location: /index.php');
    exit;
}

$con = getDb();
$sql = $sql = "SELECT server_team.name, server_team.mascot, server_sport.name as sport, server_league.name as league, server_season.year as season, server_team.picture, server_team.homecolor, server_team.awaycolor, server_team.maxplayers 
                        FROM server_team
                        LEFT JOIN server_sport ON server_team.sport = server_sport.id
                        LEFT JOIN server_league ON server_team.league = server_league.id
                        LEFT JOIN server_season ON server_team.season = server_season.id
                        WHERE server_team.id = ". $_SESSION['team'];
//var_dump($_SESSION);
$res = $con->query($sql);
if ($res->num_rows > 0) {
    $arr_teams = $res->fetch_all(MYSQLI_ASSOC);
}
$sql = "SELECT firstname, lastname, dateofbirth, jerseynumber, server_team.name as team, p.name as position 
                        FROM server_player
                        LEFT JOIN server_team on server_player.team = server_team.id
                        LEFT OUTER JOIN server_playerpos pp ON server_player.id = pp.player  
                        LEFT OUTER JOIN server_position p ON pp.position = p.id
                        WHERE team = ".$_SESSION['team'];
$res = $con->query($sql);
if ($res->num_rows > 0) {
    $arr_players = $res->fetch_all(MYSQLI_ASSOC);
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
<table id="teamTable" class="display" style="width:100%">
    <thead>
    <th>Name</th>
    <th>Mascot</th>
    <th>Sport</th>
    <th>League</th>
    <th>Season</th>
    <th>Picture</th>
    <th>Home color</th>
    <th>Away color</th>
    <th>Max Players</th>
    </thead>
    <tbody>
    <?php if(!empty($arr_teams)) { ?>
        <?php foreach($arr_teams as $sls) { ?>
            <tr>
                <td><?php echo $sls['name']; ?></td>
                <td><?php echo $sls['mascot']; ?></td>
                <td><?php echo $sls['sport']; ?></td>
                <td><?php echo $sls['league']; ?></td>
                <td><?php echo $sls['season']; ?></td>
                <td><?php echo $sls['picture']; ?></td>
                <td><?php echo $sls['homecolor']; ?></td>
                <td><?php echo $sls['awaycolor']; ?></td>
                <td><?php echo $sls['maxplayers']; ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>

<hr>

<table id="playerTable" class="display" style="width:100%">
    <thead>
    <th>First name</th>
    <th>Last name</th>
    <th>DoB</th>
    <th>Jersy Number</th>
    <th>Team</th>
    <th>Position</th>

    </thead>
    <tbody>
    <?php if(!empty($arr_players)) { ?>
        <?php foreach($arr_players as $sls) { ?>
            <tr>
                <td><?php echo $sls['firstname']; ?></td>
                <td><?php echo $sls['lastname']; ?></td>
                <td><?php echo $sls['dateofbirth']; ?></td>
                <td><?php echo $sls['jerseynumber']; ?></td>
                <td><?php echo $sls['team']; ?></td>
                <td><?php echo $sls['position']; ?></td>

            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="libraries/DataTables-1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#teamTable').DataTable();
        $('#playerTable').DataTable();
    });
</script>

</body>
</html>
