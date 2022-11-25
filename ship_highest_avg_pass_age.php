<html>
<center>
    <h2>Ship with highest average passenger age</h2>
</center>

<body>

    <?php
    $success = True;
    $db_conn = NULL;
    $show_debug_alert_messages = False;

    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function connectToDB()
    {
        global $db_conn;

        $db_conn = oci_connect("ora_ngynjsn", "a11170081", "dbhost.students.cs.ubc.ca:1522/stu");

        if ($db_conn) {
            debugAlertMessage("Database is Connected");
            return true;
        } else {
            debugAlertMessage("Cannot connect to Database");
            $e = oci_error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
            return false;
        }
    }

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        oci_close($db_conn);
    }

    function executePlainSQL($cmdstr)
    { //takes a plain (no bound variables) SQL command and executes it
        global $db_conn, $success;

        $statement = oci_parse($db_conn, $cmdstr);

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = oci_execute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function printResult($result)
    { //prints results from a select statement
        echo '<center>';
        echo '<table>';
        echo "<tr><th>hullID</th><th>Average Age</th></tr>";
        
        while ($row = oci_fetch_array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
        
        echo "</table>";
        echo '</center>';
    }

    function handleQuery()
    {
        global $db_conn;

        if (connectToDB()) {
            $command = "SELECT c.hullID, avg(age) 
                        FROM cruiseship c, passengers p, ticket t 
                        WHERE c.hullID = t.hullID AND 
                        t.passengerID = p.passengerID 
                        GROUP BY c.hullID 
                        HAVING avg(age) >= all (SELECT avg(age) 
                                                FROM cruiseship cr, passengers pa, ticket ti 
                                                WHERE cr.hullID = ti.hullID AND 
                                                pa.passengerID = ti.passengerID 
                                                GROUP BY cr.hullID)";
            $result = executePlainSQL($command);
            printResult($result);
            oci_commit($db_conn);
            disconnectFromDB();
        }

    }

    handleQuery();
    ?>
</body>

</html>