<div id="photo-tag-modal-form" class="modal" tabindex="-1">
    <div class="modal-crop-form">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger">Photo tag</h4>
            </div>

            <div class="modal-body overflow-visible">
                <div id="photo-tag-container">

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
                                            <div>
                                                <ul class="select-tag-color">
                                                    <input type="radio" name="tag-color" value="" style="display: none" checked>
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#4986e7">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#9fc6e7">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#ac725e">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#d06b64">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#fad165">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#16a765">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#42d692">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#f83a22">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#ff7537">
                                                    <input class="color-pick-radio" type="radio" name="tag-color" value="#555">
                                                </ul>
                                                <input name="tag-size" id="tag-size" type="hidden">
                                            </div>

                                            <div class="control-group">
                                                <input id="tag-title" placeholder="Title" name="tag-title" type="text" class="input-xlarge">
                                            </div>

                                            <div class="control-group">
                                                <input id="tag-url" placeholder="Url" name="tag-url" type="text" class="input-xlarge">
                                            </div>

                                            <div class="control-group">
                                                <textarea id="tag-description" name="tag-description" class="form-control limited" maxlength="250" placeholder="Description"></textarea>
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