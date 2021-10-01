<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .col, .row {
            display: flex;
        }
        .col {
            flex-direction: column;
            height: 100%;
        }
        .row {
            flex-direction: row;
            align-items: center;
        }
        .row div {
            margin: 0 0.7em;
            width: 8em;
        }
        .status {
            text-align: right;
        }
        .availabled, .not-availabled {
            width: 1em;
            height: 1em;
        }
        .availabled {
            color: green;
        }
        .not-availabled {
            color: red;
        }
    </style>
</head>
<body>
    <?php 
        function parse_mysqli_version($version) {
            if(isset($version)){
                $main_version = round($version / 10000);
                $version %= 10000;                
                
                $minor_version = round($version / 100);
                $sub_version = $version % 100;
                echo "mysql-$main_version.$minor_version.$sub_version";
            } 
        }

        $phpversion = phpversion();

        $servername = 'db';
        $username = 'laravel_user';
        $password = 'P@ssw0rd';
        $database = 'laravel';
        $port = 3306;

        $mysqli_conn = null;
        $mysql_version = null;
        try {
            if(phpversion("mysqli")) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $mysqli_conn = mysqli_connect($servername, $username, $password, $database, $port);
                $mysql_version = mysqli_get_server_version($mysqli_conn);
            }
        } 
        catch(Exception $e) {}
        finally {
            if(isset($mysqli_conn)) mysqli_close($mysqli_conn);
        }
        $pdo_mysql = null;
        $pdo_mysql_version = null;
        try {
            $pdo_mysql = new PDO("mysql:host=$servername;dbname=$database;port=$port", $username, $password);
            $pdo_mysql_version = $pdo_mysql->getAttribute(PDO::ATTR_SERVER_VERSION);
        } 
        catch(PDOException $ex){}
        $redis_version = null;
        try {
            $redis = new Redis();
            $redis->connect('redis', 6379);
            $redis_version = $redis->info()['redis_version'];
        } 
        catch(RedisException $ex){}
    ?>
    <h1> DOCKER APPLICATION TEST RESULT </h1>
    <div class="col">
    <div class="row">
            <div class="status <?= isset($phpversion)?"availabled":"not-availabled" ?>"> &#11044; </div>
            <div><?php echo "php-$phpversion" ?></div>
        </div>
        <div class="row">
            <div class="status <?= isset($mysqli_conn)?"availabled":"not-availabled" ?>"> &#11044; </div>
            <div>mysqli-<?= phpversion('mysqli'); ?> </div>
            <div>
                <?php 
                    if($mysql_version) 
                        parse_mysqli_version($mysql_version);
                    else 
                        echo "No server"; 
                ?>
            </div>
        </div>
        <div class="row">
            <div class="status <?= isset($pdo_mysql)?"availabled":"not-availabled" ?>"> &#11044; </div>
            <div> pdo-mysql-<?= phpversion('pdo_mysql'); ?></div>
            <div><?php if(isset($pdo_mysql_version)) echo "mysql-$pdo_mysql_version"; else echo "No server"; ?></div>
        </div>
        <div class="row">
            <div class="status <?= phpversion('redis')?"availabled":"not-availabled" ?>"> &#11044; </div>
            <div> redis-<?= phpversion('redis'); ?></div>
            <div><?php if(isset($redis_version)) echo "redis-$redis_version"; else echo "No server"; ?></div>
        </div>
    </div>
</body>
</html>