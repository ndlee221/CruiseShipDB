<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->
  <html>
    <center>
    <body>
        <h1 > Cruise Analyser </h1>
        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Add Data</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>View Data</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Update Data</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~ngynjsn/remove_data_page.php">
            <h2>Remove Data</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Average salary for each cruise ship</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Crew members doing at least X tasks</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Ship with highest average passenger age</h2>
        </a>

        <a href="https://www.students.cs.ubc.ca/~west2020/oracle-sample.php">
            <h2>Passenger that has done every activity</h2>
        </a>
    </center>
        <h2>Insert Values into DemoTable</h2>
        <form method="POST" action="modified-oracle-test.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Number: <input type="text" name="insNo"> <br /><br />
            Name: <input type="text" name="insName"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Name in DemoTable</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="modified-oracle-test.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in DemoTable</h2>
        <form method="GET" action="modified-oracle-test.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Print Rows of DemoTable</h2>
        <form method="GET" action="modified-oracle-test.php"> <!--refresh page when submitted-->
            <input type="hidden" id="fetchTableRequest" name="fetchTableRequest">
            <input type="submit" name="fetchTable"></p>
        </form>
	</body>
</html>
