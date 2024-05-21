<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groups Page with Search</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navbar.php';
$url = 'http://wwwlab.webug.se/examples/XML/carservice/group?mode=json';
$jsonData = file_get_contents($url);
$companies = json_decode($jsonData, true);
usort($companies, function ($a, $b) { //usort 
    return empty ($a['logo']) - empty ($b['logo']);
});
// Get the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$filteredCompanies = [];

// Filter the companies based on the search term
if ($searchTerm) {
    foreach ($companies as $company) {
        $filteredMembers = array_filter($company['members'], function ($member) use ($searchTerm) {
            return stripos($member['name'], $searchTerm) !== false;
        });

        if (!empty($filteredMembers)) {
            $company['members'] = $filteredMembers;
            $filteredCompanies[] = $company;
        }
    }
} else {
    $filteredCompanies = $companies;
}


?>
<div class="header">
    <h3>Groups</h3>
</div>
<!-- Search form -->
<form method="GET">
    <input type="text" name="search" placeholder="Search for brands...">
    <button type="submit">Search</button>
</form>

<div class="flex-container">
    <?php foreach ($filteredCompanies as $company): ?>
                    <div class="brand">
                        <h2><?php echo htmlspecialchars($company['groupid']); ?></h2>
                        <?php foreach ($company['members'] as $member): ?>
                                        <strong>
                                            <p><?php echo htmlspecialchars($member['name']); ?></p>
                                        </strong>
                                        <p>From: <?php echo htmlspecialchars($member['from']); ?></p>
                                        <?php if (isset($member['to'])): ?>
                                                        <p>To: <?php echo htmlspecialchars($member['to']); ?></p>
                                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!empty($company['logo'])): ?>
                                        <img src="<?php echo htmlspecialchars($company['logo']); ?>" alt="Logo">
                        <?php endif; ?>
                    </div>
    <?php endforeach; ?>
</div>




</body>
</html>
