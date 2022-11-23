<html>
	<head>
		<title>Add Data</title>
	</head>

	<body>
		<center>
			<h2>Add data from Table</h2>
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



		<h2>Insert Values into Passenger table</h2>
		<form method="POST" action="add-data.php"> <!--refresh page when submitted-->
			<intput type="hidden" id="insertQueryRequest" name="insertQueryRequest">
			PassengerID: <input type="text" name="insPID"> <br /><br />
			Name: <input type="text" name="insName"> <br /><br />
			Age: <input type="text" name="insAge"> <br /><br />
			Postal: <input type="text" name="insPostal"> <br /><br />
			Address: <input type="text" name="insAddy"> <br /><br />
			City: <input type="text" name="insCity"> <br /><br />
			
			<input type="submit" value="Insert" name="insertSubmit"></p>
		</form>
		
		<hr />

	<?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }
		
	function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
                foreach ($tuple as $bind => $val) {
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }


	function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_ndlee221", "a13349634", "dbhost.students.cs.ubc.ca:1522/stu");

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


	 function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insNo'],
                ":bind2" => $_POST['insName']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into demoTable values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        }


	// HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                }

                disconnectFromDB();
            }
        }

	
	   function handleTableChangeRequest() {
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
	

	if (isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_POST['changeTableSubmit'])) {
		handleTableChangeRequest();
	  }
		?>
	</body>
</html>