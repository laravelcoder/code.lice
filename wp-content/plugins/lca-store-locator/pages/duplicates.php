<div id="duplicateLocations" class="hidden">
    <table class="table table-striped table-condensed">
        <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Address</th>
        <th></th>
        <th></th>
        </thead>
        <tbody class="list">
        <?php
        $locations = LcaStoreLocator_Plugin::GetDuplicateLocations();
        foreach ($locations as $location) {
            $idColumn = "<td class='id'>" . $location["sl_id"] . "</td>";
            $nameColumn = "<td class='name'>" . $location["sl_store"] . "</td>";
            $addressColumn = "<td class='address'>" . $location["sl_formatted_address"] . "</td>";
            $viewDuplicateColumn = "<td><button type='button' class='btn btn-default' data-toggle='modal' data-target='#locationModal' data-id='" . $location["sl_duplicate_id"] . "'>View Duplicate</button></td>";
            $deleteButton = "<button title='Delete' type='button' class='delete-location btn btn-danger' data-id='" . $location["sl_id"] . "'><span class='glyphicon glyphicon-remove'></span></button>";
            $keepButton = "<button title='Keep' type='button' class='keep-location btn btn-success' data-id='" . $location["sl_id"] . "'><span class='glyphicon glyphicon-ok'></span></button>";
            $btnGroup = "<td><div class='btn-group btn-group-sm pull-right'>" . $deleteButton . $keepButton . "</div></td>";
            $row = "<tr class='location-row'>";
            $row .= $idColumn;
            $row .= $nameColumn;
            $row .= $addressColumn;
            $row .= $viewDuplicateColumn;
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