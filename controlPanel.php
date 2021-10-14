<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}

$con = getDb();

$res = $con->query('SELECT server_user.username, server_roles.name as role_name, server_team.name as team_name, server_league.name as league_name 
                        FROM server_user
                        LEFT JOIN server_roles ON server_user.role = server_roles.id
                        LEFT JOIN server_team ON server_user.team = server_team.id
                        LEFT JOIN server_league ON server_user.league = server_league.id');
if ($res->num_rows > 0) {
    $arr_users = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT name FROM server_sport");
if ($res->num_rows > 0) {
    $arr_sports = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT name FROM server_league");
if ($res->num_rows > 0) {
    $arr_leagues = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT year, description FROM server_season");
if ($res->num_rows > 0) {
    $arr_seasons = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT server_sport.name as sport, server_league.name as league, server_season.year as season 
                        FROM server_slseason
                        LEFT JOIN server_sport ON server_slseason.sport = server_sport.id
                        LEFT JOIN server_league ON server_slseason.league = server_league.id
                        LEFT JOIN server_season ON server_slseason.season = server_season.id");
if ($res->num_rows > 0) {
    $arr_sls = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT server_team.name, server_team.mascot, server_sport.name as sport, server_league.name as league, server_season.year as season, server_team.picture, server_team.homecolor, server_team.awaycolor, server_team.maxplayers 
                        FROM server_team
                        LEFT JOIN server_sport ON server_team.sport = server_sport.id
                        LEFT JOIN server_league ON server_team.league = server_league.id
                        LEFT JOIN server_season ON server_team.season = server_season.id");
if ($res->num_rows > 0) {
    $arr_teams = $res->fetch_all(MYSQLI_ASSOC);
}
//var_dump($arr_users);
?><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/panel.css">
    <!--DataTable-->
    <link rel="stylesheet" href="libraries/DataTables-1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <title>Control Panel</title>
</head>
<body>
    <nav class="navtop">
        <div>
            <h1>Sports manager</h1>
            <?php if($_SESSION['role'] === 1) {?>
            <a href="controlPanel.php?type=admin">Admin</a>
            <?php }?>
            <a href="controlPanel.php?type=team">Team</a>
            <a href="schedulePanel.php">Schedule</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
<!--    <div class="adminTable">-->
        <table id="userTable" class="display" style="width:100%">
            <thead>
            <th>Username</th>
            <th>Role</th>
            <th>Team</th>
            <th>League</th>
            <th>Actions</th>
            </thead>
            <tbody>
            <?php if(!empty($arr_users)) { ?>
                <?php foreach($arr_users as $user) { ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role_name']; ?></td>
                        <td><?php echo $user['team_name']; ?></td>
                        <td><?php echo $user['league_name']; ?></td>
                        <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
<!--    </div>-->

    <hr>

    <table id="sportTable" class="display" style="width:100%">
        <thead>
        <th>Sport</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_sports)) { ?>
            <?php foreach($arr_sports as $sport) { ?>
                <tr>
                    <td><?php echo $sport['name']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>

    <table id="leagueTable" class="display" style="width:100%">
        <thead>
        <th>League</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_leagues)) { ?>
            <?php foreach($arr_leagues as $league) { ?>
                <tr>
                    <td><?php echo $league['name']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>

    <table id="seasonTable" class="display" style="width:100%">
        <thead>
        <th>Season</th>
        <th>Description</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_seasons)) { ?>
            <?php foreach($arr_seasons as $season) { ?>
                <tr>
                    <td><?php echo $season['year']; ?></td>
                    <td><?php echo $season['description']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>

    <table id="slsTable" class="display" style="width:100%">
        <thead>
        <th>Sport</th>
        <th>League</th>
        <th>Season</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_sls)) { ?>
            <?php foreach($arr_sls as $sls) { ?>
                <tr>
                    <td><?php echo $sls['sport']; ?></td>
                    <td><?php echo $sls['league']; ?></td>
                    <td><?php echo $sls['season']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <a href=""><i class="fas fa-trash-alt"></i></a></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>

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
        <th>Actions</th>
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
            $('#userTable').DataTable();
            $('#sportTable').DataTable();
            $('#leagueTable').DataTable();
            $('#seasonTable').DataTable();
            $('#slsTable').DataTable();
            $('#teamTable').DataTable();
        });
    </script>
</body>
</html>