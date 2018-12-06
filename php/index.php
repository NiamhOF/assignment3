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
        //https://www.w3schools.com/php/php_mysql_select.asp - PHP Select Data From MySQL
        echo "<table>";
        echo "<tr><th>Products</th><th>Description</th></tr>";

        class TableRows extends RecursiveIteratorIterator { 
            function __construct($it) { 
                parent::__construct($it, self::LEAVES_ONLY); 
            }

            function current() {
                return "<td>" . parent::current(). "</td>";
            }

            function beginChildren() { 
                echo "<tr>"; 
            } 

            function endChildren() { 
                echo "</tr>" . "\n";
            } 
        } 

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "classicmodels";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT productLine, textDescription FROM productlines"); 
            $stmt->execute();

            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
                echo $v;
            }
        }
        catch(PDOException $e) {
            echo "<div id='errorMessage'><p>Error retrieving classic models information. Please refresh the page to try again. If the issue persists email niamh@classicmodelcars.com</p></div>";
        }
        $conn = null;
        echo "</table>";
        
        include "footer.php";
        echo '</div>';
        
        ?>
        </div>
        </div>
    </body>

</html>