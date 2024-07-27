<?php
// Database connection parameters
$host = 'localhost';
$db = 'postgres';
$user = 'postgres';
$pass = 'ZHOUwenBOda3';
$port = 5432; // default port
$dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch available columns dynamically
$availableColumns = [];
$stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'College'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $availableColumns[] = $row['column_name'];
}

// Handle form submission
$selectedColumns = $_POST['columns'] ?? $availableColumns; // Use selected columns or all columns if none selected
$selectedColumns = array_reverse($selectedColumns);
$availableColumns = array_reverse($availableColumns);
// Fetch data based on selected columns
$columnsString = implode(", ", $selectedColumns);
$sql = 'SELECT ' . $columnsString . ' FROM public."College"';
$stmt = $pdo->query($sql);
$data = $stmt->fetchAll();

// Add headers to the data if there are any rows
if (!empty($data)) {
    array_unshift($data, array_keys($data[0]));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Statistics</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>College Statistics</h1>
    <form method="post" action="">
        <fieldset>
            <legend>Select Attributes to Display:</legend>
            <?php foreach ($availableColumns as $column): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="columns[]" value="<?= $column ?>" <?= in_array($column, $selectedColumns) ? 'checked' : '' ?>>
                    <label class="form-check-label">
                        <?= ucfirst(str_replace('_', ' ', $column)) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="thead-light">
            <tr>
                <?php foreach ($selectedColumns as $header) : ?>
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
