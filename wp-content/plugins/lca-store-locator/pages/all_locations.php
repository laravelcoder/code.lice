<div id="locations" class="hidden">
    <?php include_once('layouts/navbar.php'); ?>
    <table class="table table-striped table-condensed">
        <thead>
        <th>
            <button class="sort" data-sort="id">ID</button>
        </th>
        <th>
            <button class="sort" data-sort="name">Name</button>
        </th>
        <th>
            <button class="sort" data-sort="address">Address</button>
        </th>
        <th>
            <button data-sort="zip">Zip Code</button>
        </th>
        <th>
            <button id="filterCountries">Country</button>
        </th>
        <th></th>
        </thead>

        <tbody class="list">
        <?php
        $username = DB_USER;
        $password = DB_PASSWORD;
        $database = DB_NAME;
        $host = DB_HOST;
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
        $pdo = new PDO($dsn, $username, $password, $opt);

        $sth = $pdo->prepare('SELECT sl_id, sl_store, sl_address, sl_zip, sl_formatted_address, sl_country FROM wp_store_locator WHERE (sl_duplicate_id = 0 OR sl_duplicate_id = "" OR sl_duplicate_id IS NULL) AND sl_latitude IS NOT NULL AND sl_latitude != "" AND sl_longitude IS NOT NULL AND sl_longitude != "" AND sl_formatted_address IS NOT NULL ORDER BY sl_lastupdated DESC');
        try {
            $sth->execute();
            $locations = $sth->fetchAll();
            foreach ($locations as $location) {
                $idColumn = "<td class='id'>" . $location["sl_id"] . "</td>";
                $nameColumn = "<td class='name'>" . $location["sl_store"] . "</td>";
                $addressColumn = "<td class='address'>" . $location["sl_address"] . "</td>";
	            $zipColumn = "<td class='zip'>" . $location["sl_zip"] . "</td>";
                $countryColumn = "<td class='country'>" . $location["sl_country"] . "</td>";

                $editButton = "<button title='Edit'  type='button' class='edit-location btn btn-primary' data-toggle='modal' data-target='#locationModal' data-id='" . $location["sl_id"] . "'><span class='glyphicon glyphicon-edit'></span></button>";
                $deleteButton = "<button title='Delete'  type='button' class='delete-location btn btn-danger' data-id='" . $location["sl_id"] . "'><span class='glyphicon glyphicon-remove'></span></button>";
                $btnGroup = "<td><div class='btn-group btn-group-sm pull-right'>" . $editButton . $deleteButton . "</div></td>";


                $row = "<tr class='location-row'>";
                $row .= $idColumn;
                $row .= $nameColumn;
                $row .= $addressColumn;
	            $row .= $zipColumn;
                $row .= $countryColumn;
                $row .= $btnGroup;
                $row .= "</tr>";
                echo $row;
            }
        } catch (Exception $e) {
            $error = array("message" => $e);
            echo $error;
        }
        ?>
        </tbody>
    </table>
    <div class="bottom-nav">
        <ul class="pagination pagination-bottom"></ul>
        <div class="dropup pull-right results-per-page">
            <button class="btn btn-default dropdown-toggle" type="button" id="resultsPerPageAll" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Results Per Page
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="resultsPerPageAll">
                <li class="disabled"><a href="10">10</a></li>
                <li><a href="25">25</a></li>
                <li><a href="50">50</a></li>
                <li><a href="100">100</a></li>
            </ul>
        </div>
    </div>
</div>
