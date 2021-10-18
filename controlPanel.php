<?php
require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}

$con = getDb();
if($_SESSION['role'] === 1) {
    $sql = 'SELECT server_user.username, server_roles.name as role_name, server_team.name as team_name, server_league.name as league_name 
                        FROM server_user
                        LEFT JOIN server_roles ON server_user.role = server_roles.id
                        LEFT JOIN server_team ON server_user.team = server_team.id
                        LEFT JOIN server_league ON server_user.league = server_league.id';

}elseif ($_SESSION['role'] === 2){
    $sql = 'SELECT server_user.username, server_roles.name as role_name, server_team.name as team_name, server_league.name as league_name 
                        FROM server_user
                        LEFT JOIN server_roles ON server_user.role = server_roles.id
                        LEFT JOIN server_team ON server_user.team = server_team.id
                        LEFT JOIN server_league ON server_user.league = server_league.id
                        WHERE server_user.role > 2 AND server_user.role < 5';
}elseif ($_SESSION['role'] === 3 || $_SESSION['role'] === 4){
    $sql = 'SELECT server_user.username, server_roles.name as role_name, server_team.name as team_name, server_league.name as league_name 
                        FROM server_user
                        LEFT JOIN server_roles ON server_user.role = server_roles.id
                        LEFT JOIN server_team ON server_user.team = server_team.id
                        LEFT JOIN server_league ON server_user.league = server_league.id
                        WHERE server_user.role > 2 AND server_user.team = '.$_SESSION['team'];
}
if(isset($sql)) {
    $res = $con->query($sql);
    if ($res->num_rows > 0) {
        $arr_users = $res->fetch_all(MYSQLI_ASSOC);
    }
}
$res = $con->query("SELECT * FROM server_position");
if ($res->num_rows > 0) {
    $arr_positions = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT * FROM server_sport");
if ($res->num_rows > 0) {
    $arr_sports = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT * FROM server_league");
if ($res->num_rows > 0) {
    $arr_leagues = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT id, year, description FROM server_season");
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
if ($_SESSION['role'] === 2){
    $sql = "SELECT server_team.id, server_team.name, server_team.mascot, server_sport.name as sport, server_league.name as league, server_season.year as season, server_team.picture, server_team.homecolor, server_team.awaycolor, server_team.maxplayers 
                        FROM server_team
                        LEFT JOIN server_sport ON server_team.sport = server_sport.id
                        LEFT JOIN server_league ON server_team.league = server_league.id
                        LEFT JOIN server_season ON server_team.season = server_season.id
                        WHERE server_team.league = ". $_SESSION['league'];
}else{
    $sql = 'SELECT server_team.id, server_team.name, server_team.mascot, server_sport.name as sport, server_league.name as league, server_season.year as season, server_team.picture, server_team.homecolor, server_team.awaycolor, server_team.maxplayers 
                        FROM server_team
                        LEFT JOIN server_sport ON server_team.sport = server_sport.id
                        LEFT JOIN server_league ON server_team.league = server_league.id
                        LEFT JOIN server_season ON server_team.season = server_season.id';
}
$res = $con->query($sql);
if ($res->num_rows > 0) {
    $arr_teams = $res->fetch_all(MYSQLI_ASSOC);
}
if($_SESSION['role'] === 3 || $_SESSION['role'] === 4){
    $sql = "SELECT server_player.id, firstname, lastname, dateofbirth, jerseynumber, server_team.name as team, p.name as position 
                        FROM server_player
                        LEFT JOIN server_team on server_player.team = server_team.id
                        LEFT OUTER JOIN server_playerpos pp ON server_player.id = pp.player  
                        LEFT OUTER JOIN server_position p ON pp.position = p.id
                        WHERE team = ".$_SESSION['team'];
}else{
    $sql = "SELECT server_player.id, firstname, lastname, dateofbirth, jerseynumber, server_team.name as team, p.name as position 
                        FROM server_player
                        LEFT JOIN server_team on server_player.team = server_team.id
                        LEFT OUTER JOIN server_playerpos pp ON server_player.id = pp.player  
                        LEFT OUTER JOIN server_position p ON pp.position = p.id";
}
$res = $con->query($sql);
if ($res->num_rows > 0) {
    $arr_players = $res->fetch_all(MYSQLI_ASSOC);
}

?><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/panel.css">

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
            <?php if($_SESSION['role'] != 5) {?>
            <a href="controlPanel.php?type=admin">Admin</a>
            <?php }?>
            <?php if($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {?>
            <a href="teamPanel.php">Team</a>
            <?php }?>
            <a href="schedulePanel.php">Schedule</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
<!--    <div class="adminTable">-->
    <?php if(isset($arr_users)){?>
        <table id="userTable" class="display" style="width:100%">
            <button class="addButton" onclick="window.location.href = 'addView.php?table=server_user'">Add new User</button>
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
                        <td><a href="editView.php?table=server_user&id=<?=$user['username']?>"><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_user', 'username',  '". $user['username']."', 'userTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <hr>
    <?php }?>
<!--    </div>-->


    <?php if($_SESSION['role'] === 1){?>
    <table id="sportTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_sport'">Add new Sport</button>
        <thead>
        <th>Sport</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_sports)) { ?>
            <?php foreach($arr_sports as $sport) { ?>
                <tr>
                    <td><?php echo $sport['name']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_sport', 'id',  '". $sport['id']."', 'sportTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
        <hr>
    <?php }?>

    <?php if($_SESSION['role'] === 1){?>
    <table id="leagueTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_league'">Add new League</button>
        <thead>
        <th>League</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_leagues)) { ?>
            <?php foreach($arr_leagues as $league) { ?>
                <tr>
                    <td><?php echo $league['name']; ?></td>
                    <td><a href=""><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_league', 'id',  '". $league['id']."', 'leagueTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>
    <?php }
    if($_SESSION['role'] <= 2){
    ?>
    <table id="seasonTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_season'">Add new Season</button>
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
                    <td><a href=""><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_season', 'id',  '". $season['id']."', 'seasonTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>

    <hr>
    <?php }
    if($_SESSION['role'] <= 2){
    ?>
    <table id="slsTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_slseason'">Add new Sport/League/Season</button>
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
    <?php }?>
    <table id="teamTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_team'">Add new Team</button>
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
                    <td><a href=""><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_team', 'id',  '". $sls['id']."', 'teamTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <?php if($_SESSION['role'] === 1 || $_SESSION['role'] === 3 || $_SESSION['role'] === 4){?>
    <table id="playerTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_player'">Add new Player</button>
        <thead>
        <th>First name</th>
        <th>Last name</th>
        <th>DoB</th>
        <th>Jersy Number</th>
        <th>Team</th>
        <th>Position</th>
        <th>Actions</th>

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

                    <td><a href=""><i class="fas fa-edit"></i></a> <button class="btn" onclick="removeEvent(<?php echo("'server_player', 'id',  '". $sls['id']."', 'playerTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <?php }?>

    <?php if($_SESSION['role'] === 3 || $_SESSION['role'] === 4 || $_SESSION['role'] === 1){?>
    <table id="positionTable" class="display" style="width:100%">
        <button class="addButton" onclick="window.location.href = 'addView.php?table=server_position'">Add new Position</button>
        <thead>
        <th>Sport</th>
        <th>Actions</th>
        </thead>
        <tbody>
        <?php if(!empty($arr_positions)) { ?>
            <?php foreach($arr_positions as $position) { ?>
                <tr>
                    <td><?php echo $position['name']; ?></td>
                    <td><button class="btn" onclick="removeEvent(<?php echo("'server_position', 'id',  '". $position['id']."', 'positionTable'")?>)"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <hr>

    <?php }?>
    <form action="removeEntry.php" method="post" id="removeForm">
        <input type="text" name="tableName" hidden>
        <input type="text" name="key" hidden>
        <input type="text" name="id" hidden>
        <button type="submit" hidden></button>
    </form>
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
            $('#playerTable').DataTable();
            $('#positionTable').DataTable();
        });

        function removeEvent(table, key, id, formName){
            console.log(formName);
            document.getElementsByName('tableName')[0].value = table;
            document.getElementsByName('key')[0].value = key;
            document.getElementsByName('id')[0].value = id;
            document.getElementById('removeForm').submit();
        }
    </script>

</body>
</html>