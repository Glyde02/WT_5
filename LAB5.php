<!DOCTYPE html>
<html lang="en">
<head>
    <meta CHARSET="UTF-8">
    <title>Lab_5</title>
</head>
<body>
<form action="LAB5.php" method="post">
    <div style="width: 500px; height: 50px">
        <textarea style="width: 100%; height: 100%" name="inputTxt" type="text" ></textarea>
    </div>

    <input style="margin-top: 20px" type="submit" value="Отправить запрос"></br>
</form>
</body>
</html>



<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memory = memory_get_usage();
    $input = htmlspecialchars($_POST['inputTxt']);

    if ( empty($input))
    {
        echo "</br>EMPTY!";
    }
    else {
        echo "</br><span style='font-size: large; font-weight: bold'>Запрос:</span> $input</br></br>";


        $host = 'localhost'; // имя хоста (уточняется у провайдера)
        $database = 'lab'; // имя базы данных, которую вы должны создать
        $user = 'root'; // заданное вами имя пользователя, либо определенное провайдером
        $pswd = '20012002Anton'; // заданный вами пароль


        $dbh = mysqli_connect($host, $user, $pswd);

        if ($dbh == false) {
            echo "Error connect";
        } else {
            if (mysqli_select_db($dbh, $database) == false) {
                echo "Error select bd";
            } else {


                mysqli_query($dbh, "set profiling=1");


                $res = mysqli_query($dbh, $input);


                $time_info = mysqli_query($dbh, "show profiles");
                mysqli_query($dbh, "set profiling=0");

                $arr_time_info = mysqli_fetch_all($time_info, 1);
                $total_time = (float)0;
                foreach ($arr_time_info as $elem) {
                    $total_time += (float)$elem["Duration"];
                }

                if ($res == false) {
                    echo "Bad request!";
                } else {
                    $arr_res = mysqli_fetch_all($res, 1);
//            var_dump($arr_res);
                    $arr_keys = array_keys($arr_res[0]);
//            var_dump($arr_keys);


                    $column_size = count($arr_keys) * 200;

                    echo "<table style='color: black; border: 1px solid grey'>";
                    echo "<tr style='font-size: 20px'>";
                    foreach ($arr_keys as $key) {
                        echo "<th style='width: 80px'>$key</th>";
                    }
                    echo "</tr>";

                    foreach ($arr_res as $arr_element) {
                        echo "<tr>";
                        foreach ($arr_element as $element) {
                            echo "<th style='font-weight: lighter'>$element</th>";
                        }
                        echo "</tr>";
                    }

                    echo "</table>";
                }


                echo "</br>total time = $total_time ms</br>";
                mysqli_close($dbh);
            }

        }


        echo 'memory = ' . (memory_get_usage() - $memory) . ' byte';
    }
}







