<?php
$projected_attributes = $_POST['projectedAttributes'];
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

function printResult($result, $num_projected_attributes) {
    global $projected_attributes;
    echo '<table>';
    echo "<tr>";
    for ($i = 0; $i < $num_projected_attributes; $i++) {
        echo "<th>";
        echo $projected_attributes[$i];
        echo "</th>";
    }
    echo "</tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo '<tr>';
        for ($i = 0; $i < $num_projected_attributes; $i++) {
            echo "<td>" . $row[$i] . "</td>";
        }
        echo '</tr>';
    }

    echo "</table>";
}

function selectQuery() {
    global $projected_attributes;
    $select_clause = "SELECT ";
    $num_projected_attributes = count($projected_attributes);
    for($i = 0; $i < $num_projected_attributes; $i++) {
        if ($i === ($num_projected_attributes - 1)) {
            $select_clause = $select_clause . $projected_attributes[$i];
        } else {
            $select_clause = $select_clause . $projected_attributes[$i] . ', ';
        }
    };
    $from_clause = 'FROM ' . $_POST["table"];
    $where_clause = NULL;
    if (empty($_POST["conditions"])) {
        $where_clause = '';
    } else {
        $where_clause = 'WHERE ' . $_POST["conditions"];
    }
    $result = executePlainSQL($select_clause . ' ' . $from_clause . ' ' . $where_clause);
    printResult($result, $num_projected_attributes);
}

if (connectToDB()) {
    selectQuery();
    disconnectFromDB();
}
?>