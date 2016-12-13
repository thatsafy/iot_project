<!doctype html>
<html class="no-js" lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IoT-project</title>
        <link rel="stylesheet" href="css/foundation.css">
        <link rel="stylesheet" href="css/app.css">
        <?php
            header("Refresh: 60");
            include '../../sql_var.php';
        ?>
    </head>

    <body>

    <div class="row">
        <div class="large-12 columns">
            <h1 align="center">ICT15-N Group 9. IoT-Project</h1>
        </div>
    </div>

    <div class="row">
    
    <div class="large-6 medium-6 columns callout">
        
            <h3 align="center">Temperatures</h3>
                    <p align="center">
                    
                    <?php
                        $conn = new mysqli($server_name, $username, $password, $db);

                        if ($conn->connect_error) { 
                            die("connection error " . $conn->connect_error);
                        }

                        $sql = "SELECT Date, Time, Temps FROM pi_data ORDER BY Date DESC, Time DESC LIMIT 10";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo "<table width='100%'><tr><th>Date</th><th>Time</th><th>Temp</th></tr>";

                            while($row = $result->fetch_assoc()){
                                echo "<tr><td align='center'>" . $row["Date"]. "</td><td align='center'>" . $row["Time"]. "</td><td align='center'>" . $row["Temps"]. "</td></tr>";
                                $date[] = $row['Date'];
                                $time[] = $row['Time'];
                                $temperature[] = $row['Temps'];
                            }
                            echo "</table>";
                        }
                        else {
                            echo "0 results!";
                        }

                        $conn->close();
                    ?>

                    </p>
        
    </div>

    <div class="large-6 medium-6 columns callout">
        
            <h3 align="center">Brightness</h3>

                        <p align="center">
              
                        <?php 
                            $conn = new mysqli($server_name, $username, $password, $db);
                            if ($conn->connect_error) {
                                die("connection error " . $conn->connect_error);
                                echo("fail");
                            }
                        
                            $sql = "SELECT Date, Time, Light FROM pi_data ORDER BY Date DESC, Time DESC LIMIT 10";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                echo "<table width='100%'><tr><th>Date</th><th>Time</th><th>Light</th></tr>";
                                while($row = $result->fetch_assoc()){
                                    echo "<tr><td align='center'>" . $row["Date"]. "</td><td align='center'>" . $row["Time"]. "</td><td align='center'>" . $row["Light"]. "</td></tr>";
                                    $date[] = $row['Date'];
                                    $time[] = $row['Time'];
                                    $light[] = $row['Light'];
                                }
                                echo "</table>";
                            }
                            else {
                                echo "0 results!";
                            }
                            $conn->close();
                        ?>
                        </p>

            
        </div>
    </div> 

    <hr />

    <div class="row">
        <div class="large-12 medium-12 columns callout">
            <h3 align="center">Graphs</h3>
            <h5 align="center">Temperature and brightness graphs</h5>

            <div class="large-6 medium-6 columns">
                <h5 align="center">Temperature</h5>
                <div id="temp_chart" style="width: 100%; height: 31.25em"></div>
            </div>

            <div class="large-6 medium-6 columns">
                <h5 align="center">Brightness</h5>
                <div id="light_chart" style="width: 100%; height: 31.25em"></div>
            </div>     
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="large-12 medium-12 callout columns">
            <h3 align="center">Info</h3>
            <p>
            The IoT-project of Group 9. consists of an Arduino microcontoller with a temperature and light sensors, a Raspberry Pi and a CentOS 7 -server running web server and MariaDB SQL database.<br />
            The Arduino microcontroller collects sensor data every 30 minutes, calculates the averages and then sends the data via serial line to the Raspberry Pi. Pi is reading serial data with a python script which is running as a service. The script reads the serial data, splits and formats the data into a form that is suitable to be sent to the SQL database. The Python script utilizes some built-in libraries but also PySerial for serial read and PyMySQL for the database connection.<br />
            The MariaDB SQL database where the sensor data is sent is running on a separate server running on CentOS 7. The presentation of the data is done with a php web page running on an apache httpd server. The php page queries the data from the database and then using the built-in html functions creates tables. Javascript is used along with Google Charts to create charts for the last eight values sent to the database. 
            </p>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="large-12 medium-12 callout columns">
            <div align="center" class="large-4 medium-4 columns">
                <h5>Project Specifications</h5>
                <p>
                <a href="https://metropoliafi-my.sharepoint.com/personal/wikst_metropolia_fi/_layouts/15/WopiFrame.aspx?guestaccesstoken=mWT9ahIKVRSbCnqEFF99kMJ3oMiB4d%2bCR%2bJVgvxNBgE%3d&docid=01573fc8433fb4287b18f11f663013064&expiration=2016-12-24T22%3a18%3a54.000Z&action=view" target="_blank" class="small button">IoT-project specs.</a>
                <br />
                The IoT-project is built upon the specifications given in link below.
                </p>
            </div>

            <div align="center" class="large-4 medium-4 columns">
                <h5>Project workbook</h5>
                <p>
                <a href="#" target="_blank" class="small button">Workbook</a>
                <br />
                The project workbook.
                </p>
            </div>

            <div align="center" class="large-4 medium-4 columns">
                <h5>Source code</h5>
                <p>
                <a href="https://github.com/thatsafy/iot_project/" target="_blank" class="small button">Source code</a>
                <br />
                The project source code available on GitHub!
                </p>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <?php
        $conn = new mysqli($servername,$username,$password,$db);

        if ($conn->connect_error) {
            die("Connection error " . $conn->connect_error); 
        }
        $sql = "SELECT DATE_FORMAT(Time, '%H:%i') AS Time, Temps, Light FROM pi_data ORDER BY Date DESC, Time DESC LIMIT 8";
        $result = $conn->query($sql);
        /*
        $tempdata = array(array("Time", "Temps"));
        $lightdata = array(array("Time", "Light"));    
        */
        
        foreach($result as $item) {
            $tempdata[] = array((string)$item['Time'],(int)$item['Temps']);
            $lightdata[] = array((string)$item['Time'],(int)$item['Light']);
        }        

        $tempdata2 = array_reverse($tempdata);
        $lightdata2 = array_reverse($lightdata);
 
        $tempdata2[0] = array("Time", "Temps");
        $lightdata2[0] = array("Time", "Light");
 
        $conn->close();
    ?>

    <script type="text/javascript">
        
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
       
        var options = {
            title: 'Temperatures',
            curveType: 'function',
            colors:['red','#004411'],
            legend: { position: 'bottom' }
        };
        
 
        var temp_chart = google.visualization.arrayToDataTable(<?php echo json_encode($tempdata2);?>);      

        var chart = new google.visualization.LineChart(document.getElementById('temp_chart'));
        chart.draw(temp_chart, options);
        }
    </script>

    <script type="text/javascript">

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
        var lights_data = google.visualization.arrayToDataTable(<?php echo json_encode($lightdata2);?>);

        var options = {
            title: 'Brightness',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('light_chart'));
        chart.draw(lights_data, options);
      
        }
    </script>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
    
    </body>
</html>
