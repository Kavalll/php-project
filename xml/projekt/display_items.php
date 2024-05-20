<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/1b8dbb92e5.js" crossorigin="anonymous"></script>
    <title>Display Items</title>
    <style>
        a {
            width:20%;
            padding:10px;
           
        }
        Button{
            border-radius: 35px;
            width:100%
        }
        .header{
            display:inline-flex;
            width:100%;
            
        }
        .header h3{
            width:100%;
        }
        table {
            width: 95%;
            border-collapse: collapse;
            margin:2.5%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php



function sortByTotalSales($a, $b)
{
    return $b['sales'] - $a['sales'];
}


if (isset($_GET['brandid'])) {
    include 'navbar.php';
    // Sanitize the input
    $brandid = htmlspecialchars($_GET['brandid']);

    // Construct the API URL with brandid parameter
    $api_url = "https://wwwlab.webug.se/examples/XML/carservice/carsales/?brandid=" . urlencode($brandid) . "&mode=json";

    // Fetch data from the API
    $jsonData = file_get_contents($api_url);

    // Check for errors in fetching JSON data
    if ($jsonData === false) {
        die("Failed to fetch JSON data: " . error_get_last()['message']);
    }

    // Decode JSON data
    $data = json_decode($jsonData, true);

    // Check for errors in decoding JSON data
    if ($data === null) {
        die("Failed to decode JSON data: " . json_last_error_msg());
    }
    usort($data, 'sortByTotalSales');
    // Output the data in an HTML table
    if (!empty($data)) {
        echo "<div class='header'>";
        echo "<a href='index.php'><Button><i class='fas fa-angle-double-left fa-4x'></i></Button></a>";
        echo "<h3>Most popular cars by: " . htmlspecialchars($brandid) . "</h3>";

        echo "</div>";

        echo "<table>";
        echo "<tr><th>Brand ID</th><th>Model</th><th>Period</th><th>Class</th><th>Sales</th></tr>";
        foreach ($data as $item) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['brandid']) . "</td>";
            echo "<td>" . htmlspecialchars($item['model']) . "</td>";
            echo "<td>" . htmlspecialchars($item['period']) . "</td>";
            echo "<td>" . htmlspecialchars($item['class']) . "</td>";
            echo "<td>" . htmlspecialchars($item['sales']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No items found for Brand ID: " . htmlspecialchars($brandid) . "</p>";
    }
} else {
    // If brandid is not provided in the URL, redirect to an error page or homepage
    echo "<p>Error: Brand ID not provided.</p>";
}

?>


</body>
</html>
