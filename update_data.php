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

    $success = True;
    $db_conn = NULL;
    $show_debug_alert_messages = False;
    $numFields = 0;

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

        echo "<center><br>Successfully modified row!</br></center>";
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
            $currString = "UPDATE $table  ";
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
            $command = $currString . $setString . $conditionString;
            executePlainSQL($command);
            oci_commit($db_conn);
            disconnectFromDB();
        }
    }

    function getTableFields($table)
    {
        switch ($table) {
            case 'ticket':
                return ["ticketID", "ticketClass", "ticketDate", "hullID", "passengerID"];
            case 'passengerlocation':
                return ["postalCode", "city"];
            case "passengers":
                return ["passengerID", "passengerName", "age", "postalCode", "passengerAddress"];
            case "pets":
                return ["passengerID", "petName", "breed"];
            case "cruiseship":
                return ["hullID", "cruiseName", "fromLocation", "toLocation"];
            case "hospitality":
                return ["roomNo", "maxCapacity", "roomType", "hullID"];
            case "passengersstayat":
                return ["passengerID", "roomNo"];
            case "activities":
                return ["stall", "actStart", "actEnd", "activityName", "hullID"];
            case "passengersparticipatein":
                return ["passengerID", "stall"];
            case "restaurants":
                return ["stall", "restName", "restStart", "restEnd", "hullID"];
            case "passengerseatat":
                return ["passengerID", "stall"];
            case "captain":
                return ["crewID", "captainName", "salary", "licenseNum"];
            case "pilots":
                return ["crewID", "hullID"];
            case "generalstaffsalary":
                return ["role", "salary"];
            case "generalstaff":
                return ["crewID", "staffName", "staffRole"];
            case "managehospitalities":
                return ["crewID", "roomNum"];
            case "manageactivities":
                return ["crewID", "stall"];
            case "managerestaurants":
                return ["crewID", "stall"];
        }
    }


    function handleTableChangeRequest()
    {
        global $numFields;
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