<html>
    <head>
        <title>Passengers that have done every activities</title>
        <style>
            h2 {
                text-align: center;
                margin-top: 1.66rem;
            }

            table {
                margin-left: auto;
                margin-right: auto;
            }

            th {
                font-size: 22px;
            }
        </style>
    </head>

    <body>
        <h2>Passengers that have done every activities</h2>

        <?php
        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;

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

        function printResult($result) { //prints results from a select statement
            echo '<table>';
            echo "<tr><th>passengerID</th><th>PassengerName</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["PASSENGERID"] . "</td><td>" . $row["PASSENGERNAME"] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function handleRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT p.passengerID, p.passengerName
                                    FROM Passengers p
                                    WHERE NOT EXISTS (SELECT a.stall
                                                        FROM Activities a
                                                        MINUS
                                                        (SELECT pp.stall
                                                        FROM PassengersParticipateIn pp
                                                        WHERE pp.passengerID = p.passengerID))
                                    ");

            printResult($result);
            oci_commit($db_conn);
        }

        if (connectToDB()) {
            handleRequest();
            disconnectFromDB();
        }
        ?>
    </body>
</html>