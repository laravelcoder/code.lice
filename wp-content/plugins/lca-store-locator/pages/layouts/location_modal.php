<div id="locationModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-10 modal-title">
                            <div class="form-group" id="locationModalLabel">
                                <input class="form-control" type="text" id="storeName" />
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="panel-group" id="locationAccordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <?php include_once('location_modal/address_panel.php'); ?>
                    </div>
                    <div class="panel panel-default">
                        <?php include_once('location_modal/contact_panel.php'); ?>
                    </div>
                    <div class="panel panel-default">
                        <?php include_once('location_modal/type_of_location_panel.php'); ?>
                    </div>
                    <div class="panel panel-default">
                        <?php include_once('location_modal/social_panel.php'); ?>
                    </div>
                    <div class="panel panel-default">
                        <?php include_once('location_modal/tracking_panel.php'); ?>
                    </div>
                    <div class="panel panel-default">
                        <?php include_once('location_modal/other_panel.php'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveLocation">Save changes</button>
            </div>
        </div>
    </div>
</div>