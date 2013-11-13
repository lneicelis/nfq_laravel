jQuery(function ($) {

    $(".photo-tag-form").click(function(event){
        event.preventDefault();

        var tagSrc = $(this).attr('href');
        var cropId = $(this).attr('data-photo-id');

        $(".photo-tag-container").append("<img id=\"photo-crop\" class=\"crop-photo\" src=" + tagSrc + " />");

        $('#photo-tag-modal-form').modal('show');

    });

    $(".photo-tag-container").click(function(e){
        var obj = $(this);
        var coordinates = getCoordinates(obj);
        var tag = "<i class=\"icon-paperclip bigger-200\"></i>";
        var style = "style=\"top: "+ coordinates.y + "; left:" + coordinates.x + " \"";
        var appendObj = "<div class=\"photo-tag\" " + style + ">" + tag + "</div>";


        $(this).append(appendObj);
        $("#photo-tag-form").css({top: coordinates.y, left: coordinates.x }).show();
        //alert("Left: " + coordinates.x + "\nTop: " + coordinates.y);

        function getCoordinates(obj)
        {
            var relX = e.pageX - obj.offset().left;
            var relY = e.pageY - obj.offset().top;
            var percentOffsetX = relX / obj.width() * 100;
            var percentOffsetY = relY / obj.height() * 100;
            var x = Math.round( percentOffsetX * 100 ) / 100;
            var y = Math.round( percentOffsetY * 100 ) / 100;

            var coordinates = {x: x + "%", y: y + "%"};
            return coordinates;
        }
    });


});

/*
(function($){
    $.fn.myAjax = function(settings){
        var config = {
            'foo': 'bar'
        };
        if (settings){$.extend(config, settings);}

        return this.each(function(){
            $(this).bind("click",function(){
                alert('nana');
            });
        });
    };
})(jQuery);
*/