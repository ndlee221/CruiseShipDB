<html>

<body>
    <center>
        <h2>Remove data from Table</h2>
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
            <input type="submit" value="Change" name="changeTableSubmit"></p>
        </form>
    </center>
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

    // function executePlainSQL($cmdstr)
    // {
    //     global $db_conn, $success;

    //     $statement = oci_parse($db_conn, $cmdstr);
    
    //     if (!$statement) {
    //         echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    //         $e = oci_error($db_conn); // For OCIParse errors pass the connection handle
    //         echo htmlentities($e['message']);
    //         $success = False;
    //     }

    //     $r = oci_execute($statement, OCI_NO_AUTO_COMMIT);
    //     if (!$r) {
    //         echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
    //         $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
    //         echo htmlentities($e['message']);
    //         $success = False;
    //     }

    //     return $statement;
    // }

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

    function handleRemoveDataRequest()
    {
        if (connectToDB()) {
            global $db_conn, $success;
            foreach ($_POST as $elem) {
                if ($elem !== $_POST['id1'] && $elem !== $_POST['id2']) {
                    $table = substr($elem, 12); // "Remove from <TABLENAME>"
                }
            }
            $id1 = $_POST['id1'];
            $id2 = $_POST['id2'];
            [$key1, $key2] = getTableKeys($table);
            if ($key1 && $key2) {
                $statement = oci_parse($db_conn, "DELETE FROM $table WHERE $key1 = '$id1' AND $key2 = '$id2'");
                
            } else {
                $statement = oci_parse($db_conn, "DELETE FROM $table WHERE $key1 = '$id1'");
            }
            $r = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
            if (!$r) {
                echo "<br>Cannot execute the command<br>";
                $e = oci_error($statement);
                echo htmlentities($e['message']);
                $success = False;
            } else {
                echo "successfully deleted row!";
            };
            disconnectFromDB();
        }
    }

    function getTableKeys($table)
    {
        switch ($table) {
            case 'ticket':
                return ["ticketID"];
            case 'passengerlocation':
                return ["postalCode"];
            case "passengers":
                return ["passengerID"];
            case "pets":
                return ["passengerID", "petName"];
            case "cruiseship":
                return ["hullID"];
            case "hospitality":
                return ["roomNo"];
            case "passengersstayat":
                return ["passengerID", "roomNo"];
            case "activities":
                return ["stall"];
            case "passengersparticipatein":
                return ["passengerID", "stall"];
            case "restaurants":
                return ["stall"];
            case "passengerseatat":
                return ["passengerID", "stall"];
            case "captain":
                return ["crewID"];
            case "pilots":
                return ["crewID", "hullID"];
            case "generalstaffsalary":
                return ["role"];
            case "generalstaff":
                return ["crewID"];
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
        if (!empty($_POST['tables'])) {
            $selected = $_POST['tables'];
            $GLOBALS['table'] = $selected;
            echo "<center>";
            echo "<h2 id='table'>";
            echo $selected;
            echo "</h2>";
            echo "<form method='POST'>";
            switch ($selected) {
                case 'ticket':
                    echo "ticketID: <input type='text' name='id1'> <br /><br />";
                    break;
                case 'passengerlocation':
                    echo "postalCode: <input type='text' name='id1'> <br /><br />";
                    break;
                case "passengers":
                    echo "passengerID: <input type='text' name='id1'> <br /><br />";
                    break;
                case "pets":
                    echo "passengerID: <input type='text' name='id1'> <br /><br />";
                    echo "petName: <input type='text' name='id2'> <br /><br />";
                    break;
                case "cruiseship":
                    echo "hullID: <input type='text' name='id1'> <br /><br />";
                    break;
                case "hospitality":
                    echo "roomNo: <input type='text' name='id1'> <br /><br />";
                    break;
                case "passengersstayat":
                    echo "passengerID: <input type='text' name='id1'> <br /><br />";
                    echo "roomNo: <input type='text' name='id2'> <br /><br />";
                    break;
                case "activities":
                    echo "stall: <input type='text' name='id1'> <br /><br />";
                    break;
                case "passengersparticipatein":
                    echo "passengerID: <input type='text' name='id1'> <br /><br />";
                    echo "stall: <input type='text' name='id2'> <br /><br />";
                    break;
                case "restaurants":
                    echo "stall: <input type='text' name='id1'> <br /><br />";
                    break;
                case "passengerseatat":
                    echo "passengerID: <input type='text' name='id1'> <br /><br />";
                    echo "stall: <input type='text' name='id2'> <br /><br />";
                    break;
                case "captain":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    break;
                case "pilots":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    echo "hullID: <input type='text' name='id2'> <br /><br />";
                    break;
                case "generalstaffsalary":
                    echo "role: <input type='text' name='id1'> <br /><br />";
                    break;
                case "generalstaff":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    break;
                case "managehospitalities":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    echo "roomNum: <input type='text' name='id2'> <br /><br />";
                    break;
                case "manageactivities":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    echo "stall: <input type='text' name='id2'> <br /><br />";
                    break;
                case "managerestaurants":
                    echo "crewID: <input type='text' name='id1'> <br /><br />";
                    echo "stall: <input type='text' name='id2'> <br /><br />";
                    break;
            }
        }
        echo "<input value='Remove from {$selected}' name='removeDataSubmit' type='submit'></input></form>";
        echo "</center>";
    }

    if (isset($_POST['changeTableSubmit'])) {
        handleTableChangeRequest();
    } else if (isset($_POST['removeDataSubmit'])) {
        handleRemoveDataRequest();
    }
    ?>
</body>

</html>