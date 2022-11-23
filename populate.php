<?php
$db_conn = OCILogon("ora_ngynjsn", "a11170081", "dbhost.students.cs.ubc.ca:1522/stu");

if ($db_conn) {
    echo "database is connected";
    $sql = "CREATE TABLE Ticket (
        ticketID INT PRIMARY KEY,
        ticketClass CHAR(20),
        purchaseDate CHAR(6),
        hullID INT NOT NULL,
        passengerID INT NOT NULL,
        FOREIGN KEY (hullID) REFERENCES CruiseShip
            ON UPDATE CASCADE,
        FOREIGN KEY (passengerID) REFERENCES Passengers
            ON UPDATE CASCADE
        )";

    $statement = OCIParse($db_conn, $sql);
    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    } else {
        $r = OCIExecute($statement, OCI_DEFAULT);
        OCICommit($db_conn);
    }

}
?>