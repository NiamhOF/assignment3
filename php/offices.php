<!DOCTYPE HTML>
<html lang="en">
    
<head>
    <meta charset="utf-8"> 
    <meta name="assignment 3" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/a3styles.css">
    <title>Assignment 3</title>

</head>
  <body>
  <div class='background'>

    <?php
    include "navbar.php";
    echo "<div class='contents'>";
    //https://stackoverflow.com/questions/40591456/how-to-print-table-data-index-in-recursivearrayiterator - PHP Select Data From MySQL as way used on index not suitable
    echo "<table style='border: solid 1px black;'>";
    echo "<tr><th>City</th><th>Address</th><th>Phone Number</th><th>Employee Information</th></tr>";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "classicmodels";

    try {
      
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $officeStatement = $conn->prepare("SELECT city, CONCAT_WS(', ', addressLine1, addressLine2, state, country, postalCode) as address, phone, officeCode FROM offices");
        $employeeStatement = $conn->prepare("SELECT CONCAT_WS(' ', firstName, lastName) as fullName, jobTitle, employeeNumber, email FROM employees WHERE officeCode = :officeCode ORDER BY jobTitle");
      
        $officeStatement->execute();
        $offices = $officeStatement->fetchAll();
      
      foreach ($offices as $row) {
        echo '<tr>';
        echo '<td>'.$row['city'].'</td>' . "\n";
        echo '<td>'.$row['address'].'</td>' . "\n";
        echo '<td>'.$row['phone'].'</td>' . "\n";
        echo '<td><a class="link" href="./offices.php?office_code='.$row['officeCode'].'">&#x25BE Click for Employee information</a></td>'."</tr>" . "\n";
        echo '</tr>';
        if (!empty($_GET['office_code']) && $_GET['office_code'] == $row['officeCode']) {
        $employeeStatement->bindParam(':officeCode', $_GET['office_code']);
        $employeeStatement->execute();
        $employees = $employeeStatement->fetchAll();
        echo "<tr id='dropdownTable'><th>Name</th><th>Job Title</th><th>Employee Number</th><th>Email Address</th></tr>";
        foreach ($employees as $employee) {
            echo '<tr id="rows">';
            echo '<td>'.$employee['fullName'].'</td>'."\n";
            echo '<td>'.$employee['jobTitle'].'</td>'."\n";
            echo '<td>'.$employee['employeeNumber'].'</td>'."\n";
            echo '<td>'.$employee['email'].'</td>';
            echo '</tr>';
          }
        }
      }
    }
    catch(PDOException $e) {
      echo "<div id='errorMessage'><p>Error retrieving classic models information. Please refresh the page to try again. If the issue persists email niamh@classicmodelcars.com</p></div>";;
    }
    $conn = null;
    echo "</table>";

    include "footer.php";

    echo "</div>";
    ?>
    </div>
  </body>
</html>