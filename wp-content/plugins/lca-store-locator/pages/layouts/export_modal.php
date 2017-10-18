<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exportModalLabel">Export CSV</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="csv-filter col-sm-4">
                            <input data-toggle='toggle' type='checkbox' value='name' checked='true'>
                            <span class='column-name'>name</span>
                        </div>
                        <div class="csv-filter col-sm-4">
                            <input data-toggle='toggle' type='checkbox' value='street' checked='true'>
                            <span class='column-name'>street</span>
                        </div>
                        <div class="csv-filter col-sm-4">
                            <input data-toggle='toggle' type='checkbox' value='bookable' checked='true'>
                            <span class='column-name'>bookable</span>
                        </div>

                        <?php
                        $username = DB_USER;
                        $password = DB_PASSWORD;
                        $database = DB_NAME;
                        $host = DB_HOST;
                        $charset = 'utf8';
                        $optionWhitelist = ["sl_store", "sl_address" . "sl_address2", "sl_book_location"];
                        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
                        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
                        $pdo = new PDO($dsn, $username, $password, $opt);

                        $sth = $pdo->prepare('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = "' . $database . '" and table_name = "wp_store_locator"');
                        try {
                            $sth->execute();
                            $locations = $sth->fetchAll();
                            foreach ($locations as $location) {
                                $colName = $location["COLUMN_NAME"];
                                $trimedName = substr($colName, 3);
                                $adjustedName = str_replace('_', ' ', $trimedName);
                                if (!in_array($colName, $optionWhitelist)) {
                                    echo "<div class='csv-filter col-sm-4'><input data-toggle='toggle' type='checkbox' value='" . $trimedName . "' checked='true'><span class='column-name'>" . $adjustedName . "</span></div>";
                                }
                            }
                        } catch (Exception $e) {
                            $error = array("message" => $e);
                            echo $error;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="checkAll">Check All</button>
                <button type="button" class="btn btn-default" id="uncheckAll">Uncheck All</button>
                <button type="button" class="btn btn-primary" id="exportCsvBtn">Generate</button>
            </div>
        </div>
    </div>
</div>
