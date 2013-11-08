<div id="photo-edit-modal-form" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="{{ URL::to('photo/edit') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="blue bigger">Photo edit</h4>
                </div>

                <div class="modal-body overflow-visible">
                    <div class="row">

                        <div class="col-sm-10 col-sm-offset-1">

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label for="form-field-username">Photo information</label>
                                <div>
                                    <input id="photo-edit-form-album-id" name="photo_id" value="" type="hidden" />

                                    <textarea id="photo-edit-form-description" name="description" class="form-control limited" maxlength="250" placeholder="Description"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-sm" data-dismiss="modal">
                        <i class="icon-remove"></i>
                        Cancel
                    </button>

                    <button class="btn btn-sm btn-primary">
                        <i class="icon-ok"></i>
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>