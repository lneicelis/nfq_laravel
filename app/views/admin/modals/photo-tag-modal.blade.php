<div id="photo-tag-modal-form" class="modal" tabindex="-1">
    <div class="modal-crop-form">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">Photo tag</h4>
            </div>

            <div class="modal-body overflow-visible">
                <div class="photo-tag-container">

                    <img id="photo-to-tag" src="" magger-photo-id="" />


                        <div class="magger-form-container">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4>
                                        <i class="icon-tag"></i>
                                        Tagger
                                    </h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <form id="magger-form" method="post">
                                            <div class="control-group">
                                                <div class="bootstrap-colorpicker">
                                                    <label for="tag-title">Title</label>
                                                    <input id="tag-title" name="tag-title" type="text" class="input-xlarge">
                                                </div>
                                                <div>
                                                    <label for="tag-description">Description</label>

                                                    <textarea id="tag-description" name="tag-description" class="form-control limited" maxlength="250"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-xs btn-danger magger-form-delete">
                                                    <i class="icon-trash"></i>
                                                    Delete
                                                </button>
                                                <button type="button" class="btn btn-xs magger-form-close">
                                                    <i class="icon-remove"></i>
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-xs btn-primary">
                                                    <i class="icon-ok"></i>
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

    </div>
</div>