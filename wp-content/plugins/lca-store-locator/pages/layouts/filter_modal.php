<div id="myFilterModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myFilterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myFilterModalLabel">Filter Country</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <?php
                        $username = DB_USER;
                        $password = DB_PASSWORD;
                        $database = DB_NAME;
                        $host = DB_HOST;
                        $charset = 'utf8';

                        $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
                        $opt = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
                        $pdo = new PDO($dsn, $username, $password, $opt);

                        $sth = $pdo->prepare('SELECT DISTINCT  sl_country FROM `wp_store_locator` ORDER BY sl_country;');
                        try {
                            $sth->execute();
                            $countries = $sth->fetchAll();
                            foreach ($countries as $country) {
                                $country = strtolower($country["sl_country"]);
                                if ($country != "") {
                                    $checkbox = "<div class='country-filter col-sm-4'><input data-toggle='toggle' type='checkbox' value='" . $country . "' checked='true'><span class='country-name'>" . strtoupper($country) . "</span></div>";
                                    echo $checkbox;
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
                <button type="button" class="btn btn-default" id="usOnly">US Only</button>
                <button type="button" class="btn btn-default" id="allCountriesOn">All On</button>
                <button type="button" class="btn btn-default" id="allCountriesOff">All Off</button>
            </div>
        </div>
    </div>
</div>