<?php
// Database connection parameters
$host = 'localhost';
$db = 'incident_db';
$user = 'postgres';
$pass = 'ZHOUwenBOda3';
$port = 5432; // default port


// Initialize the data array with headers
// $data = [
//     ["name", "private", "apps", "accept"]
// ];

try {
    // Create a connection string
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

    // Create a new PDO instance
    $pdo = new PDO($dsn);

    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch data from the specified table
    $query = "SELECT name, private, apps FROM College";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch all rows as an associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Populate the data array with fetched data
    $index = 1;
    foreach ($result as $row) {
        $data[] = [
            $index++,
            $row['name'],
            $row['private'],
            $row['apps'],
            $row['accept']
        ];
    }
} catch (PDOException $e) {
    // Handle connection error
    echo 'Connection failed: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table UI with PHP</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <?php foreach ($data[0] as $header) : ?>
                    <th><?php echo $header; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 1; $i < count($data); $i++) : ?>
                <tr>
                    <?php foreach ($data[$i] as $cell) : ?>
                        <td><?php echo $cell; ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>
</body>
</html>
