<div id="photo-tag-modal-form" class="modal" tabindex="-1">
    <div class="modal-crop-form">
        <form method="post" action="{{ URL::action('PhotosController@postCrop') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Photo crop</h4>
                </div>

                <div class="modal-body overflow-visible">
                    <div id="photo-crop-container" class="photo-tag-container">

                        <div id="photo-tag-form" class="col-sm-4" style="display:none; position: absolute">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4>
                                        <i class="icon-tint"></i>
                                        Tagger
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row-fluid">
                                            <label for="colorpicker1">Color Picker</label>
                                        </div>

                                        <div class="control-group">
                                            <div class="bootstrap-colorpicker">
                                                <input id="tag" type="text" class="input-medium">
                                            </div>
                                            <div>
                                                <label for="description">Description</label>

                                                <textarea class="form-control limited" id="description" maxlength="250"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" size="4" id="photo-id" name="photo-id" />
                    <input type="hidden" size="4" id="x" name="x" />
                    <input type="hidden" size="4" id="y" name="y" />
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm crop-modal-hide" data-dismiss="modal">
                        <i class="icon-remove"></i>
                        Cancel
                    </button>

                    <button type="button" class="btn btn-sm btn-primary">
                        <i class="icon-ok"></i>
                        Crop
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>