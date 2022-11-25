<html>
    <head>
        <title>Project and select data</title>
        <style>
            button {
                display: block;
            }

            input[type=text] {
                width: 20%;
            }
        </style>
    </head>
    <body>
        <h2>Page to project and select data</h2>
        <form method="POST" action="select-project.php">
            <label for="table">Select table to query: </label>
            <select name="table" id="table">
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
            <input type="submit" value="Select" name="selectTableSubmit">
        </form>
        <?php
        $success = True;
        $db_conn = NULL;
        $show_debug_alert_messages = False;
        $num_conditions = ($_GET["numCondition"]) ? $_GET["numCondition"] : 1;

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

        function generateAttributesOptions($num_columns, $selected_table) {
            for ($i = 1; $i <= $num_columns; $i++) {
                $column_name = oci_field_name($selected_table, $i);
                echo '<option value="' . $column_name . '">' . $column_name . '</option>';
            };
        }

        function printSelections($selected_table) {
            global $num_conditions;
            $num_columns = oci_num_fields($selected_table);
            echo '<h3>Select columns from that table</h3>';
            echo '<form method="POST" action="print-select-project.php" id="queryform">';
            echo '<label for="attributes">Choose attributes</label>';
            echo '<br>';
            echo "<select id='attributes' name='projectedAttributes[]' multiple>";
            generateAttributesOptions($num_columns, $selected_table);
            echo '</select>';
            echo '<br>';
            echo '<br>';
            echo '<label for="conditions">Input conditions: </label>';
            echo '<input type="text" id="conditions" name="conditions" placeholder="e.g. age > 20 and passengerName = \'Jason\'">';
            echo '<br>';
            echo "USE SINGLE QUOTES FOR STRINGS!";
            echo '<br>';
            echo '<input type="hidden" name="table" value="' . $_POST['table'] . '">';
            echo '<input type="submit" name="querySubmit" form="queryform">';
            echo '</form>';
        }

        function getTable() {
            $table = $_POST['table'];
            $selected_table = executePlainSQL("SELECT * FROM {$table}");
            printSelections($selected_table);
        }

        if (isset($_POST["table"]) && connectToDB()) {
            getTable();
            disconnectFromDB();
        }
        ?>
    </body>
</html>