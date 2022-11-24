<html>
    <head>
        <title>Public data about the cruize ship</title>
        <style>
            h2 {
                text-align: center;
                margin-top: 1.66rem;
            }

            form {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h2>Page to view public data about the cruize ship</h2>
        <form method="POST" action="user-view.php">
            <input type="text" name="ticketID" placeholder="Enter your ticket ID" pattern="\d+" required>
            <input type="submit" name="dataSubmit">
        </form>

        <?php
        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        $ticketID = NULL;

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function connectToDB() {
            global $db_conn;

            $db_conn = OCILogon("ora_ngynjsn", "a11170081", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

            return $statement;
        }

        function printResult($passengerInfo, $activities, $restaurants) {
            $row = OCI_Fetch_Array($passengerInfo, OCI_BOTH);
            echo "<div>";
            echo "<p>Cruise Name: {$row["name"]}</p>";
            echo "<p>Ticket Class: {$row["ticketClass"]}</p>";
            echo "<p>Room Number: {$row["roomNo"]}</p>";
            
            echo "<h2>Cruise Activities</h2>";
            echo "<table>";
            echo "<tr><th>Activity Name</th><th>Start</th><th>End</th><tr>";
            while($activitiesRow = OCI_Fetch_Array($activities, OCI_BOTH)) {
                echo "<tr><td>" . $row["activityName"] . "</td><td>" . $row["start"] . "</td><td>" . $row["end"] . "</td></tr>";
            };
            echo "</table>";

            echo "<h2>Cruise Restaurants</h2>";
            echo "<table>";
            echo "<tr><th>Restaurant Name</th><th>Open</th><th>Close</th><tr>";
            while($activitiesRow = OCI_Fetch_Array($restaurants, OCI_BOTH)) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["start"] . "</td><td>" . $row["end"] . "</td></tr>";
            };
            echo "</table>";

            echo "</div>";
        }

        function handleRequest() {
            global $db_conn, $ticketID;
            $passengerInfo = executePlainSQL("SELECT c.name, t.ticketClass, p.roomNo
                                           FROM Ticket t, CruizeShip c, PassengersStayAt p
                                           WHERE t.ticketID = {$ticketID} AND 
                                           t.hullID = c.hullID AND 
                                           t.passengerID = p.passengerID");
            $activities = executePlainSQL("SELECT a
                                           FROM Ticket t, Activities a
                                           WHERE t.ticketID = {$ticketID} AND t.hullID = a.hullID");
            $restaurants = executePlainSQL("SELECT r
                                            FROM Ticket t, Restaurants r
                                            WHERE t.ticketID = {$ticketID} AND t.hullID = r.hullID");
            printResult($passengerInfo, $activities, $restaurants);
        }

        if (isset($_POST["ticketID"]) && connectToDB()) {
            $ticketID = $_POST["ticketID"];
            handleRequest();
            disconnectFromDB();
        }
        ?>
    </body>
</html>