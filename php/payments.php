<!DOCTYPE HTML>
<html lang="en">
    
<head>
    <meta charset="utf-8"> 
    <meta name="assignment 3" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/a3styles.css">
    <script src="../js/payments.js"> </script>
    <title>Assignment 3</title>

</head>
    <body>
    <div class='background'>
        <?php
        include "navbar.php";
        echo "<div class='contents'>";
        //https://stackoverflow.com/questions/40591456/how-to-print-table-data-index-in-recursivearrayiterator - PHP Select Data From MySQL as way used on index not suitable
        echo "<table id='payments' style='border: solid 1px black;'>";
        echo "<tr><th>Check Number</th><th>Payment Date</th><th>Amount</th><th>Customer Number</th></tr>";

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "classicmodels";

        try {
        
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $paymentsStatement = $conn->prepare("SELECT checkNumber, paymentDate, amount, customerNumber  FROM payments ORDER BY paymentDate DESC, customerNumber LIMIT :lim");
            $customerStatement = $conn->prepare("SELECT phone, creditLimit, salesRepEmployeeNumber FROM customers WHERE customerNumber = :customerNumber");
            $salesRepStatement = $conn->prepare("SELECT CONCAT_WS(' ',firstName,lastName) AS salesRepName FROM employees WHERE employeeNumber = :employeeNumber");
            $paymentsByCustomerStatement = $conn->prepare("SELECT checkNumber, paymentDate, amount, customerNumber FROM payments WHERE customerNumber = :customerNumber ORDER BY paymentDate, customerNumber DESC");
        
            if(empty($_POST['limit'])){
                $limit = 20;
            } else {
                $limit = $_POST['limit'];
            }
            $paymentsStatement->bindParam(':lim', $limit, PDO::PARAM_INT);
            $paymentsStatement->execute();
            $payments = $paymentsStatement->fetchAll();


            $select20 = "";
            $select40 = "";
            $select60 = "";


            if ($limit == 20) {
                $select20 = "selected";
            }
            if ($limit == 40) {
                $select40 = "selected";
            }
            if ($limit == 60) {
                $select60 = "selected";
            }

            echo "<div>
                    <p><b>SELECT NUMBER OF ROWS TO DISPLAY: </b></p>
                    <select id='limit' name='selectLimit' onchange='retrieveLimits()' >
                        <option value='20' $select20>20</option>
                        <option value='40' $select40>40</option>
                        <option value='60' $select60>60</option>
                    </select>
                </div>";
        
            $rowId = 1;
            foreach ($payments as $row) {
                echo '<tr id="'.$rowId.'">';
                echo '<td>'.$row['checkNumber'].'</td>' . "\n";
                echo '<td>'.$row['paymentDate'].'</td>' . "\n";
                echo '<td>'.$row['amount'].'</td>' . "\n";
                echo '<td><div class="link" onClick="retrieveCustomerInformation(\''.$rowId.'\',\''.$row['customerNumber'].'\')" >&#x25BE '.$row['customerNumber'].'</div></td>'."</tr>" . "\n";
                echo '</tr>';

                if (!empty($_POST['customerNumber']) && $_POST['rowId'] == $rowId) {
                    $customerStatement->bindParam(':customerNumber', $_POST['customerNumber']);
                    $customerStatement->execute();
                    $customer = $customerStatement->fetch();
                    $salesRepStatement->bindParam(':employeeNumber', $customer['salesRepEmployeeNumber']);
                    $salesRepStatement->execute();
                    $salesRep = $salesRepStatement->fetch();
                    echo "<tr id='dropdownTable'><th><b>Customer Information:</b></th><th>Phone Number</th><th>Sales Rep</th><th>Credit Limit</th></tr>";
                    echo '<tr id="rows">';
                    echo '<td></td>'."\n";
                    echo '<td>'.$customer['phone'].'</td>'."\n";
                    echo '<td>'.$salesRep['salesRepName'].'</td>'."\n";
                    echo '<td>'.$customer['creditLimit'].'</td>'."\n";
                    echo '</tr>';
                    
                    $paymentsByCustomerStatement->bindParam(':customerNumber', $_POST['customerNumber']);
                    $paymentsByCustomerStatement->execute();
                    $paymentsByCustomer = $paymentsByCustomerStatement->fetchAll();
                    echo "<tr id='dropdownTable'><th><b>Payment Information:</b></th><th>Check Number</th><th>Payment Date</th><th>Amount</th></tr>";
                    $total = 0;
                    foreach ($paymentsByCustomer as $row) {
                        echo '<tr id="rows">';
                        echo '<td></td>'."\n";
                        echo '<td>'.$row['checkNumber'].'</td>'."\n";
                        echo '<td>'.$row['paymentDate'].'</td>'."\n";
                        echo '<td>'.$row['amount'].'</td>'."\n";
                        echo '</tr>';

                        $total = $total + $row['amount'];
                    }
                    echo '<tr id="total">';
                    echo '<td></td>'."\n";
                    echo '<td></td>'."\n";
                    echo '<td class="total">Total paid to date:</td>'."\n";
                    echo '<td class="total">'.$total.'</td>'."\n";
                    echo '</tr>';
                }
                $rowId = $rowId + 1;
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        echo "</table>";

        include "footer.php";
        echo "</div>";
        ?>
        </div>
    </body>
</html>