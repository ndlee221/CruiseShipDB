<html>

<body>
    <center>
        <h2>Update data from Table</h2>
        <form method="POST">
            <select name="tables" id="tables">
                <option value="ticket">Ticket</option>
                <option value="passengerlocation">PassengerLocation</option>
                <option value="passengers">Passengers</option>
                <option value="pets">Pets</option>
                <option value="cruiseship">CruiseShip</option>
                <option value="hospitality">Hospitality</option>
                <option value="passengersstayat">PassengersStayAt</option>
                <option value="activities">Activities</option>
                <option value="passengersparticipatein">PassengersParticipateIn</option>
                <option value="restaurants">Restaurants</option>
                <option value="passengerseatat">PassengersEatAt</option>
                <option value="captain">Captain</option>
                <option value="pilots">Pilots</option>
                <option value="generalstaffsalary">GeneralStaffSalary</option>
                <option value="generalstaff">GeneralStaff</option>
                <option value="managehospitalities">ManageHospitalities</option>
                <option value="manageactivities">ManageActivities</option>
                <option value="managerestaurants">ManageRestaurants</option>
            </select>
            <input type="submit" value="Choose" name="changeTableSubmit"></p>
        </form>
    </center>
    <?php

    include 'table_fields.php';
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

    function executePlainSQL($cmdstr)
    {
        global $db_conn, $success;

        $statement = oci_parse($db_conn, $cmdstr);

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = oci_error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = oci_execute($statement, OCI_NO_AUTO_COMMIT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }
        return $statement;
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

    function handleUpdateDataSubmit()
    {
        if (connectToDB()) {
            global $db_conn, $success;
            $length = count($_POST);
            $cond_vars = floor($length / 2);
            $table = substr($_POST['updateDataSubmit'], 12);
            $attrs = getTableFields($table);
            $currString = "UPDATE $table ";
            $conditionString = " WHERE ";
            $numConditions = 0;
            for ($i = 0; $i < $cond_vars; $i++) {
                if ($_POST[$i] != '') {
                    $numConditions++;
                };
            };
            $numSet = 0;
            for ($i = 0; $i < $cond_vars; $i++) {
                if ($_POST[$i + $cond_vars] != '') {
                    $numSet++;
                };
            };
            for ($i = 0; $i < $cond_vars; $i++) {
                $value = $_POST[$i];
                if ($value == '') {
                    continue;
                };
                $and = "";
                if ($numConditions > 1) {
                    $and = " AND ";
                    $numConditions--;
                }
                $conditionString = $conditionString . $attrs[$i] . "=" . "'$value'" . $and;
            };
            $setString = "SET ";
            for ($i = 0; $i < $cond_vars; $i++) {
                $value = $_POST[$i + $cond_vars];
                if ($value == "") {
                    continue;
                };
                $comma = "";
                if ($numSet > 1) {
                    $comma = ",";
                    $numSet--;
                }
                $setString = $setString . $attrs[$i] . "=" . "'$value'" . $comma;
            }
            $cmdstr = $currString . $setString . $conditionString;
            if (executePlainSQL($cmdstr)) {
                oci_commit($db_conn);
                echo "<center><br>Successfully modified row(s)!</br>";
                echo "Table after modification: ";
                $after_table_command = "SELECT * FROM $table";
                $after_table = executePlainSQL($after_table_command);
                printTable($after_table, $table);
                echo "</center>";
            };
            oci_commit($db_conn);
            disconnectFromDB();
        }
    }
    function handleTableChangeRequest()
    {
        if (!empty($_POST['tables'])) {
            $selected = $_POST['tables'];
            echo "<center>";
            echo "<h2 id='table'>Modifying ";
            echo $selected;
            echo "</h2>";
            echo "<form method='POST'>";
            $table_fields = getTableFields($selected);
            $i = 0;
            echo "CONDITIONS: <br/>";
            foreach ($table_fields as $field) {
                echo "$field: <input type='text' name='$i'> <br /><br />";
                $i++;
            }
            echo "NEW VALUES: <br/>";
            foreach ($table_fields as $field) {
                echo "$field: <input type='text' name='$i'> <br /><br />";
                $i++;
            }
            echo "<input value='Update from {$selected}' name='updateDataSubmit' type='submit'></input></form>";
            if (connectToDB()) {
                echo "</br> Table before modification: ";
                $before_table_command = "SELECT * FROM $selected";
                $before_table = executePlainSQL($before_table_command);
                printTable($before_table, $selected);
                disconnectFromDB();
            }
            echo "</center>";
        }
    }

    if (isset($_POST['changeTableSubmit'])) {
        handleTableChangeRequest();
    } else if (isset($_POST['updateDataSubmit'])) {
        handleUpdateDataSubmit();
    }
    ?>
</body>

</html>