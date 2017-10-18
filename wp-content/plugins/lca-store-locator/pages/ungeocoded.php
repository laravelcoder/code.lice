<div id="geocodeLocations" class="hidden" data-api-key="<?php echo $this->getOption('GoogleApiKey') ?>">
    <table class="table table-striped table-condensed">
        <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Street</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Country</th>
        <th style="width:110px;"></th>
        </thead>
        <tbody class="list">
        <?php
        $locations = LcaStoreLocator_Plugin::GetUngeocodedLocations();
        foreach ($locations as $location) {

            $address = $location["sl_address"];
            if (!empty($location["sl_address2"])) $address .= " " . $location["sl_address2"];
            if (!empty($location["sl_city"])) $address .= " " . $location["sl_city"];
            if (!empty($location["sl_state"])) $address .= " " . $location["sl_state"];
            if (!empty($location["sl_zip"])) $address .= " " . $location["sl_zip"];
            if (!empty($location["sl_country"])) $address .= ", " . $location["sl_country"];

            $idColumn = "<td class='id'>" . $location["sl_id"] . "</td>";
            $nameColumn = "<td class='name'>" . $location["sl_store"] . "</td>";
            $streetColumn = "<td class='street'>" . $location["sl_address"] . "</td>";
            $cityColumn = "<td class='city'>" . $location["sl_city"] . "</td>";
            $stateColumn = "<td class='state'>" . $location["sl_state"] . "</td>";
            $zipColumn = "<td class='zip'>" . $location["sl_zip"] . "</td>";
            $countryColumn = "<td class='country'>" . $location["sl_country"] . "</td>";

            $geocodeButton = "<button title='Geocode' type='button' class='geocode-location btn btn-success' data-address='" . $address . "'><span class='glyphicon glyphicon-globe'></span></button>";
            $editButton = "<button title='Edit'  type='button' class='edit-location btn btn-primary' data-toggle='modal' data-target='#locationModal' data-id='" . $loc->id . "'><span class='glyphicon glyphicon-edit'></span></button>";
            $deleteButton = "<button title='Delete'  type='button' class='delete-location btn btn-danger' data-id='" . $loc->id . "'><span class='glyphicon glyphicon-remove'></span></button>";
            $btnGroup = "<td><div class='btn-group btn-group-sm pull-right'>" . $geocodeButton . $editButton . $deleteButton . "</div></td>";

            $row = "<tr class='location-row'>";
            $row .= $idColumn;
            $row .= $nameColumn;
            $row .= $streetColumn;
            $row .= $cityColumn;
            $row .= $stateColumn;
            $row .= $zipColumn;
            $row .= $countryColumn;
            $row .= $btnGroup;
            $row .= "</tr>";
            echo $row;
        }
        ?>
        </tbody>
    </table>
    <ul class="pagination pagination-bottom"></ul>
    <div class="dropup pull-right results-per-page">
        <button class="btn btn-default dropdown-toggle" type="button" id="resultsPerPageUngeocoded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Results Per Page
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="resultsPerPageUngeocoded">
            <li class="disabled"><a href="10">10</a></li>
            <li><a href="25">25</a></li>
            <li><a href="50">50</a></li>
            <li><a href="100">100</a></li>
        </ul>
    </div>
</div>
