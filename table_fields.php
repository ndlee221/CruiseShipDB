<?php
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

function printTable($result, $table)
{
    echo '<center>';
    echo '<table>';
    $attrs = getTableFields($table);
    echo "<tr>";
    $string = "";
    foreach ($attrs as $attr) {
        $string = $string . "<th>$attr</th>";
    }
    ;
    echo $string;
    echo "</tr>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr>";
        $string = "";
        for ($i = 0; $i < count($attrs); $i++) {
            $string = $string . "<td>" . $row[$i] . "</td>";
        }
        ;
        echo $string;
        echo "</tr>";
    }

    echo "</table>";
    echo '</center>';
}
?>