<?php

// Step 1: Establish a connection
$servername = "localhost";
$username = "root";
$password = "";
$con = mysqli_connect($servername, $username, $password);

if (mysqli_connect_errno()) 
{
    die("Connection Failed: ");
}

// Step 2: Create database and table
$sql1 = "CREATE DATABASE IF NOT EXISTS endsem";
mysqli_query($con, $sql1);

// select the database
mysqli_select_db($con, "endsem");

$sql2 = "CREATE TABLE IF NOT EXISTS details(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dtravel DATE NOT NULL,
    fromlocation VARCHAR(50) NOT NULL,
    tolocation VARCHAR(50) NOT NULL,
    class VARCHAR(20) NOT NULL,
    extra_luggage BOOLEAN DEFAULT 0,
    requests TEXT
)";
mysqli_query($con, $sql2);

// Step 3: Insert, Update, Delete, and Search Operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['insert'])) 
    {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
    $dtravel = htmlspecialchars($_POST['dtravel']);
    $fromlocation = htmlspecialchars($_POST['fromlocation']);
    $tolocation = htmlspecialchars($_POST['tolocation']);
    $class = htmlspecialchars($_POST['class']);
    $extra_luggage = isset($_POST['extra_luggage']) ? 1 : 0;
    $requests = htmlspecialchars($_POST['requests']);

    $sql_insert = "INSERT INTO details (name, email, password, dtravel, fromlocation, tolocation, class, extra_luggage, requests) 
                   VALUES ('$name', '$email', '$password', '$dtravel', '$fromlocation', '$tolocation', '$class', '$extra_luggage', '$requests')";
   mysqli_query($con, $sql_insert);
}

    // Update Operation
    if (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $dtravel = htmlspecialchars($_POST['dtravel']);
        $fromlocation = htmlspecialchars($_POST['fromlocation']);
        $tolocation = htmlspecialchars($_POST['tolocation']);
        $class = htmlspecialchars($_POST['class']);
        $extra_luggage = isset($_POST['extra_luggage']) ? 1 : 0;
        $requests = htmlspecialchars($_POST['requests']);

        $sql_update = "UPDATE details SET name='$name', email='$email', dtravel='$dtravel', fromlocation='$fromlocation', 
                       tolocation='$tolocation', class='$class', extra_luggage='$extra_luggage', requests='$requests' WHERE id='$id'";
        mysqli_query($con, $sql_update);
        echo "Data updated successfully<br>";
    }

    // Delete Operation
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $sql_delete = "DELETE FROM details WHERE id='$id'";
        mysqli_query($con, $sql_delete);
        echo "Data deleted successfully<br>";
    }

    // Search Operation
    if (isset($_POST['search'])) 
    {
        $searchName = htmlspecialchars($_POST['name']);
        $sql_search = "SELECT * FROM details WHERE name='$searchName'";
        $result = mysqli_query($con, $sql_search);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h3>Search Results for '$searchName'</h3>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";
                echo "Date of Travel: " . $row["dtravel"] . " - From: " . $row["fromlocation"] . " - To: " . $row["tolocation"] . "<br>";
                echo "Class: " . $row["class"] . " - Extra Luggage: " . ($row["extra_luggage"] ? "Yes" : "No") . "<br>";
                echo "Special Requests: " . $row["requests"] . "<br><br>";
            }
        } 
else
       {
            echo "No records found for the name '$searchName'.";
        }
    }
}

// Step 4: Retrieve and display all data
$sql_select = "SELECT * FROM details";
$result = mysqli_query($con, $sql_select);

echo "<h3>Customer Details</h3>";
if ($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date of Travel</th>
                <th>From Location</th>
                <th>To Location</th>
                <th>Class</th>
                <th>Extra Luggage</th>
                <th>Special Requests</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["dtravel"] . "</td>
                <td>" . $row["fromlocation"] . "</td>
                <td>" . $row["tolocation"] . "</td>
                <td>" . $row["class"] . "</td>
                <td>" . ($row["extra_luggage"] ? "Yes" : "No") . "</td>
                <td>" . $row["requests"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No records found.";
}

?>

<!-- Single Form for Search, Update, and Delete -->
<h3>Search, Update, or Delete Record</h3>
<form method="POST">
    ID (required for Update/Delete): <input type="number" name="id"><br>
    Name (required for Search): <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Date of Travel: <input type="date" name="dtravel"><br>
    From Location: <input type="text" name="fromlocation"><br>
    To Location: <input type="text" name="tolocation"><br>
    Class: <input type="text" name="class"><br>
    Extra Luggage: <input type="checkbox" name="extra_luggage"><br>
    Special Requests: <textarea name="requests"></textarea><br>
    <button type="submit" name="search">Search by Name</button>
    <button type="submit" name="update">Update Record</button>
    <button type="submit" name="delete">Delete Record</button>
</form>

<?php
// Step 5: Close the connection
mysqli_close($con);
?>
