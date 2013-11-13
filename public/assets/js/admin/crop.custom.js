jQuery(function ($) {

    var jcrop_api;
    var cropContainer = "#photo-crop-container";
    var cropObject = "#photo-crop";

    function setCoords(c)
    {
        $('#x').val(c.x);
        $('#y').val(c.y);
        $('#w').val(c.w);
        $('#h').val(c.h);
    }

    function flush_jcrop() {

        if(jcrop_api){
            $(cropContainer).empty();
            jcrop_api.destroy();
        }

    }

    $(".photo-crop-form").click(function(event){
        event.preventDefault();
        flush_jcrop();

        var cropSrc = $(this).attr('href');
        var cropid = $(this).attr('data-photo-id');

        $("#photo-id").attr("value", cropid);
        $(cropContainer).append("<img id=\"photo-crop\" class=\"crop-photo\" src=" + cropSrc + " />");

        $("#photo-crop").Jcrop({
            onChange:   setCoords,
            onSelect:   setCoords
        },function(){
            jcrop_api = this;
        });

        $('#photo-crop-modal-form').modal('show');

    });
});