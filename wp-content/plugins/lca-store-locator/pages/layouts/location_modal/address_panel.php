<div class="panel-heading" role="tab" id="locationAddressHeading">
    <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#locationAccordion" href="#locationAddress" aria-controls="locationAddress" aria-expanded="true">
            Address
        </a>
    </h4>
</div>
<div id="locationAddress" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="locationAddressHeading" aria-expanded="true">
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 form-horizontal">
                    <div class="form-group">
                        <label for="locationStreet1" class="col-sm-3 control-label">Street</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationStreet1" placeholder="Line 1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationStreet2" class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationStreet2" placeholder="Line 2">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationCity" class="col-sm-3 control-label">City</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationCity" placeholder="City">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationState" class="col-sm-3 control-label">State</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationState" placeholder="State">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationZip" class="col-sm-3 control-label">Zip Code</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationZip" placeholder="Zip Code">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locationCountry" class="col-sm-3 control-label">Country</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="locationCountry" placeholder="Country">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div id="locationModalMap" style="height:279px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>