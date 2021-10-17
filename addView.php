<?php

require "database.php";
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
    header('Location: /login.php');
    exit;
}
$con = getDb();
$res = $con->query("SELECT id, name FROM server_league");
if ($res->num_rows > 0) {
    $arr_leagues = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT id, name FROM server_sport");
if ($res->num_rows > 0) {
    $arr_sports = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT id, year FROM server_season");
if ($res->num_rows > 0) {
    $arr_seasons = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT * FROM server_roles");
if ($res->num_rows > 0) {
    $arr_roles = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT id, name FROM server_team");
if ($res->num_rows > 0) {
    $arr_teams = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT * FROM server_slseason");
if ($res->num_rows > 0) {
    $arr_sls = $res->fetch_all(MYSQLI_ASSOC);
}
$res = $con->query("SELECT * FROM server_position");
if ($res->num_rows > 0) {
    $arr_positions = $res->fetch_all(MYSQLI_ASSOC);
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
            <?php if($_SESSION['role'] === 1) {?>
                <a href="controlPanel.php?type=admin">Admin</a>
            <?php }?>
            <?php if($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {?>
                <a href="teamPanel.php">Team</a>
            <?php }?>
            <a href="schedulePanel.php">Schedule</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </nav>
<!--    USER ADD-->
        <?php
            if ($_GET['table'] === 'server_user' && $_SESSION['role'] != 5 ){
            ?>
                <form action="addEntry.php" method="post">
                    <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
                    <label for="username">Username:</label>
                    <input type="text" name="username" placeholder="ex. xX_Username_Xx"><br>

                    <label for="username">Password:</label>
                    <input type="password" name="password" placeholder="ex. p4ssW0rd"><br>

                    <label for="role">Role:</label>
                    <select name="role" id="roleSelect">
                        <?php
                        foreach($arr_roles as $league){
                            ?>
                            <option value="<?=$league['id']?>"><?=$league['name']?></option>
                            <?php
                        }
                        ?>
                    </select><br>

                    <label for="team">Team:</label>
                    <select name="team" id="teamSelect">
                        <option value="-1">NULL</option>
                        <?php
                        foreach($arr_teams as $league){
                            ?>
                            <option value="<?=$league['id']?>"><?=$league['name']?></option>
                            <?php
                        }
                        ?>
                    </select><br>

                    <label for="league">League:</label>
                    <select name="league" id="leagueSelect">
                        <option value="-1">NULL</option>
                        <?php
                        foreach($arr_leagues as $league){
                            ?>
                            <option value="<?=$league['id']?>"><?=$league['name']?></option>
                            <?php
                        }
                        ?>
                    </select><br>
                    <button type="submit">Add to database</button>
                </form>
        <?php
            }
            ?>
<!--    SPORT ADD-->
    <?php
    if ($_GET['table'] === 'server_sport' && $_SESSION['role'] === 1 ){
        ?>
        <form action="addEntry.php" method="post">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="name">Sport name:</label>
            <input type="text" name="name" placeholder="ex. Football"><br>
            <br>
            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
<!-- LEAGUE ADD-->
    <?php
    if ($_GET['table'] === 'server_league' && $_SESSION['role'] <= 2){
        ?>
        <form action="addEntry.php" method="post">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="name">League name:</label>
            <input type="text" name="name" placeholder="ex. Euro League"><br>
            <br>
            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
<!-- SEASON ADD-->
    <?php
    if ($_GET['table'] === 'server_season' && $_SESSION['role'] != 5){
        ?>
        <form action="addEntry.php" method="post" id="seasonForm">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="year">Season year: </label>
            <input type="number" name="year" placeholder="ex. 2021"><br>
            <br>
            <label for="description">Description: </label>
            <textarea name="description" form="seasonForm" placeholder="ex. Brand new season, brand new players"></textarea>
            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
<!-- SPORT/LEAGUE/SEASON ADD-->
    <?php
    if ($_GET['table'] === 'server_slseason' && $_SESSION['role'] != 5){
        ?>
        <form action="addEntry.php" method="post" id="slseasonForm">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="sport">Sport:</label>
            <select name="sport" id="sportSelect">
                <?php
                foreach($arr_sports as $sport){
                    ?>
                    <option value="<?=$sport['id']?>"><?=$sport['name']?></option>
                    <?php
                }
                ?>
            </select><br>

            <label for="league">League:</label>
            <select name="league" id="leagueSelect">
                <?php
                foreach($arr_leagues as $league){
                    ?>
                    <option value="<?=$league['id']?>"><?=$league['name']?></option>
                    <?php
                }
                ?>
            </select><br>

            <label for="season">Season:</label>
            <select name="season" id="seasonSelect">
                <?php
                foreach($arr_seasons as $season){
                    ?>
                    <option value="<?=$season['id']?>"><?=$season['year']?></option>
                    <?php
                }
                ?>
            </select><br>

            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
<!-- TEAM ADD-->
    <?php
    if ($_GET['table'] === 'server_team' && $_SESSION['role'] != 5){
        ?>
        <form action="addEntry.php" method="post" id="seasonForm">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="name">Team name: </label>
            <input type="text" name="name" placeholder="ex. Hajduk"><br>
            <br>

            <label for="mascot">Mascot: </label>
            <input type="text" name="mascot" placeholder="ex. Fox"><br>
            <br>

            <label for="slseasonInput">Sport/League/Season:</label>
            <input type="text" name="slseasonInput" hidden>
            <select name="slseason" id="slseasonSelect">
                <?php

                foreach($arr_sls as $sls){

                    ?>
                    <option value="<?php echo($sls['sport']. "/" .$sls['league'] ."/". $sls['season']);?>"><?php echo($arr_sports[$sls['sport']]['name'] ."/".$arr_leagues[$sls['league']]['name']."/".$arr_seasons[$sls['season']]['year'])?></option>
                    <?php
                }
                ?>
            </select>
            <br>

            <label for="picture">Picture url: </label>
            <input type="text" name="picture" placeholder="ex. https://cdn.vox-cdn.com/thumbor/9j-s_MPUfWM4bWdZfPqxBxGkvlw=/1400x1050/filters:format(jpeg)/cdn.vox-cdn.com/uploads/chorus_asset/file/22312759/rickroll_4k.jpg"><br>
            <br>

            <label for="homecolor">Home color: </label>
            <input type="text" name="homecolor" placeholder="ex. white/black"><br>
            <br>

            <label for="awaycolor">Away color: </label>
            <input type="text" name="awaycolor" placeholder="ex. white/black"><br>
            <br>

            <label for="maxplayers">Max players: </label>
            <input type="number" name="maxplayers" placeholder="ex. 11"><br>
            <br>

            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
<!-- PLAYER ADD-->
    <?php
    if ($_GET['table'] === 'server_player' && $_SESSION['role'] != 5){
        ?>
        <form action="addEntry.php" method="post" id="playerForm">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="firstname">First name: </label>
            <input type="text" name="firstname" placeholder="ex. John"><br>
            <br>

            <label for="lastname">Last name: </label>
            <input type="text" name="lastname" placeholder="ex. Doe"><br>
            <br>

            <label for="dateofbirth" value="2021-01-01">Date of birth: </label>
            <input type="date" name="dateofbirth" ><br>
            <br>

            <label for="jerseynumber">Jersey number: </label>
            <input type="number" name="jerseynumber" placeholder="ex. 66"><br>
            <br>

            <label for="teamInput">Team:</label>
            <select name="team" id="teamSelect">
                <?php
                foreach($arr_teams as $league){
                    ?>
                    <option value="<?=$league['id']?>"><?=$league['name']?></option>
                    <?php
                }
                ?>
            </select>
            <br>

            <label for="leagueInput">Position:</label>
            <select name="role" id="roleSelect">
                <?php
                foreach($arr_positions as $position){
                    ?>
                    <option value="<?=$position['id']?>"><?=$position['name']?></option>
                    <?php
                }
                ?>
            </select>
            <br>
            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>
    <?php
    if ($_GET['table'] === 'server_position' && $_SESSION['role'] != 5){
        ?>
        <form action="addEntry.php" method="post">
            <input type="text" value="<?=$_GET['table']?>" name="tableName" hidden>
            <label for="name">Position name:</label>
            <input type="text" name="name" placeholder="ex. Right wing"><br>
            <br>
            <button type="submit">Add to database</button>
        </form>
        <?php
    }
    ?>


</body>
</html>