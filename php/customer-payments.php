<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "classicmodels";
        
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $customerStatement = $conn->prepare("SELECT phone, creditLimit, salesRepEmployeeNumber FROM customers WHERE customerNumber = :customerNumber");
    $salesRepStatement = $conn->prepare("SELECT CONCAT_WS(' ',firstName,lastName) AS salesRepName FROM employees WHERE employeeNumber = :employeeNumber");
    $paymentsByCustomerStatement = $conn->prepare("SELECT checkNumber, paymentDate, amount, customerNumber FROM payments WHERE customerNumber = :customerNumber ORDER BY paymentDate, customerNumber DESC");

    $customerStatement->bindParam(':customerNumber', $_POST['customerNumber']);
    $customerStatement->execute();
    $customer = $customerStatement->fetch();
    $salesRepStatement->bindParam(':employeeNumber', $customer['salesRepEmployeeNumber']);
    $salesRepStatement->execute();
    $salesRep = $salesRepStatement->fetch();

    // echo "<tr id='dropdownTable'><th></th><th>Phone Number</th><th>Sales Rep</th><th>Credit Limit</th></tr>";
    echo '<tr class="rows">';
    echo '<td></td>'."\n";
    echo '<td>'.$customer['phone'].'</td>'."\n";
    echo '<td>'.$salesRep['salesRepName'].'</td>'."\n";
    echo '<td>'.$customer['creditLimit'].'</td>'."\n";
    echo '</tr>';
    
    $paymentsByCustomerStatement->bindParam(':customerNumber', $_POST['customerNumber']);
    $paymentsByCustomerStatement->execute();
    $paymentsByCustomer = $paymentsByCustomerStatement->fetchAll();
    // echo "<tr id='dropdownTable'><th></th><th>Check Number</th><th>Payment Date</th><th>Amount</th></tr>";
    $total = 0;
    // foreach ($paymentsByCustomer as $row) {
    //     echo '<tr class="rows">';
    //     echo '<td id="blank"></td>'."\n";
    //     echo '<td>'.$row['checkNumber'].'</td>'."\n";
    //     echo '<td>'.$row['paymentDate'].'</td>'."\n";
    //     echo '<td>'.$row['amount'].'</td>'."\n";
    //     echo '</tr>';

    //     $total = $total + $row['amount'];
    // }
    // echo '<tr class="total">';
    // echo '<td id="blank"></td>'."\n";
    // echo '<td id="blank"></td>'."\n";
    // echo '<td class="total">Total paid to date:</td>'."\n";
    // echo '<td class="total">'.$total.'</td>'."\n";
    // echo '</tr>';
?>