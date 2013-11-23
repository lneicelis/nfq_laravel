<div id="photo-crop-modal-form" class="modal" tabindex="-1">
    <div class="modal-crop-form">
        <form method="post" action="{{ URL::action('PhotosController@postCrop') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Photo crop</h4>
                </div>

                <div class="modal-body overflow-visible">
                    <div id="photo-crop-container" class="row">

                    </div>

                    <input type="hidden" size="4" id="photo-id" name="photo-id" />
                    <input type="hidden" size="4" id="x" name="x" />
                    <input type="hidden" size="4" id="y" name="y" />
                    <input type="hidden" size="4" id="w" name="w" />
                    <input type="hidden" size="4" id="h" name="h" />
                </div>



                <div class="modal-footer">
                    <button class="btn btn-sm crop-modal-hide" data-dismiss="modal">
                        <i class="icon-remove"></i>
                        Cancel
                    </button>

                    <button class="btn btn-sm btn-primary">
                        <i class="icon-ok"></i>
                        Crop
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>