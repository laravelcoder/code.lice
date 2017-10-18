<div class="panel-heading" role="tab" id="locationTypeOfLocationHeading">
    <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#locationAccordion" href="#locationTypeOfLocation" aria-controls="locationTypeOfLocation" class="collapsed" aria-expanded="false">
            Type of Location
        </a>
    </h4>
</div>
<div id="locationTypeOfLocation" class="panel-collapse collapse" role="tabpanel" aria-labelledby="locationTypeOfLocationHeading" aria-expanded="false">
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Available Products</h4>
                    <div id="productListContainer">
                        <ul class="list list-group location-product-list"></ul>
                        <div class="input-group">
                            <input class="add-product-text form-control" id="addProductText" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="addProduct">Add Product</button>
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="checkbox">
                        <label>
                            <input id="treatmentCenterCheckbox" type="checkbox">
                            Is this location a Treatment Center?
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input id="bookableCheckbox" type="checkbox">
                            Can the user book this location online?
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
