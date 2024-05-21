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
            margin:10px;
           
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
        form {
    margin-bottom: 20px;
}
        label {
    font-size: 24px;
    margin-right: 10px;
}

select {
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

select:focus {
    outline: none;
    border-color: #007bff;
}
    </style>
</head>
<body>

<?php
function sortByTotalSales($a, $b)
{
    return $b["sales"] - $a["sales"];
}

if (isset($_GET["brandid"])) {
    include "navbar.php";
    // Sanitize the input
    $brandid = htmlspecialchars($_GET["brandid"]);

    ?>
        <div class='header'>
        <a href='index.php'><button><i class="fas fa-angle-double-left fa-4x" aria-hidden="true"></i></button></a>
        <h3>Most popular cars by: <?php echo htmlspecialchars($brandid); ?></h3>
    </div>
    <?php
    // Construct the API URL with brandid parameter
    $api_url = "https://wwwlab.webug.se/examples/XML/carservice/carsales/?brandid=" . urlencode($brandid) . "&mode=json";
    // Fetch data from the API
    $jsonData = file_get_contents($api_url);
    // Check for errors in fetching JSON data
    if ($jsonData === false) {
        die("Failed to fetch JSON data: " . error_get_last()["message"]);
    }
    $selectedModel = isset($_GET["model"]) ? htmlspecialchars($_GET["model"]) : "";
    // Decode JSON data
    $data = json_decode($jsonData, true);
    // Check for errors in decoding JSON data
    if ($data === null) {
        die("Failed to decode JSON data: " . json_last_error_msg());
    }
    usort($data, "sortByTotalSales");
    // Output the data in an HTML table

    ?>
    <!-- Generate the dropdown menu -->
      
    <?php if (!empty($data)):
        // Create an array to store unique models
        $uniqueModels = [];

        // Filter out duplicate models
        foreach ($data as $item) {
            $model = htmlspecialchars($item['model']);
            if (!in_array($model, $uniqueModels)) {

                $uniqueModels[] = $model;


            }
        }
        ?>
          <!--  dropdown menu -->
          <form method="GET">
              <input type="hidden" name="brandid" value="<?php echo htmlspecialchars($brandid); ?>">
              <label for="modelSelect">Select a model:</label>
              <select id="modelSelect" name="model" onchange="this.form.submit()">
                  <option value="">All models</option>
                  <?php foreach ($uniqueModels as $model):
                      ?>
            <option value="<?php echo $model; ?>" <?php echo $selectedModel === $model ? 'selected' : ''; ?>>
                <?php echo $model; ?>
            </option>
                  <?php endforeach; ?>
              </select>
          </form>

                    <table>
                        <tr>
                            <th>Brand ID</th>
                            <th>Model</th>
                            <th>Period</th>
                            <th>Class</th>
                            <th>Sales</th>
                        </tr>
                        <?php foreach ($data as $item): ?>
            <?php if ($selectedModel === "" || $selectedModel === htmlspecialchars($item["model"])): ?>
                      <tr>
                          <td><?php echo htmlspecialchars($item["brandid"]); ?></td>
                          <td><?php echo htmlspecialchars($item["model"]); ?></td>
                          <td><?php echo htmlspecialchars($item["period"]); ?></td>
                          <td><?php echo htmlspecialchars($item["class"]); ?></td>
                          <td><?php echo htmlspecialchars($item["sales"]); ?></td>
                      </tr>
                  <?php
            endif; ?>
                              <?php
                        endforeach; ?>
                    </table>
                <?php
    else: ?>
                    <p>No results found for Brand ID: <?php echo htmlspecialchars($brandid); ?></p>
                <?php
    endif;
    ?>
 

          <?php
} else {
    echo "<p>No Brand ID provided</p>";
}
?>


</body>
</html>
