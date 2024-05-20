<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Projekt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Fetch the JSON data for brands
$url = "https://wwwlab.webug.se/examples/XML/carservice/brand/?mode=json&sort=totalsales";
$jsonData = file_get_contents($url);
$brands = json_decode($jsonData, true);

// Check if the data was fetched and decoded properly
if ($brands === NULL) {
    die("Failed to decode JSON data for brands.");
}

// Function to sort the brands by totalsales
function sortByTotalSales($a, $b)
{
    return $b['totalsales'] - $a['totalsales'];
}

// Sort the brands
usort($brands, 'sortByTotalSales');

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if ($searchTerm) {
    $brands = array_filter($brands, function ($brand) use ($searchTerm) {
        return stripos($brand['country'], $searchTerm) !== false;
    });
}

?>

<?php include 'navbar.php'; ?>

<div class="header">
    <h3>Brand Top Sellers</h3>
</div>

<form method="GET">
    <input type="text" name="search" placeholder="Search by country..." value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
</form>

<div class="flex-container">
    <?php foreach ($brands as $brand): ?>
                        <div class="brand">
                            <h2><a href="display_items.php?brandid=<?php echo urlencode($brand['brandid']); ?>"><?php echo htmlspecialchars($brand['brandid']); ?></a></h2>
                            <p>Founded: <?php echo htmlspecialchars($brand['founded']); ?></p>
                            <p>Country: <?php echo htmlspecialchars($brand['country']); ?></p>
                            <p>City: <?php echo htmlspecialchars($brand['city']); ?></p>
                            <p>Total Sales: <?php echo htmlspecialchars($brand['totalsales']); ?></p>
                            <img src="<?php echo htmlspecialchars($brand['logo']); ?>" alt="Logo">
                        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
