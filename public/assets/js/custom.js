
(function ($){

    $(".ajax").click(function(event)
    {
        event.preventDefault();

        var obj = this;
        var url = $(this).attr("href");
        var id = $(this).attr("data-id");
        var after = $(this).attr("data-after");

        var data = {
            _token: token, //global token, that has been set in the head of HTML
            id : id
        };

        $.ajax({
            url: url,
            type: "post",
            data: data,
            success: function(result,status,xhr){
                $.grit(result.type, result.title, result.message);
                actionAfter();
            },
            error:function(xhr,status,error){
                $.grit("error", "Error", "There was an error. The the request was denied. Please try again.");
            }
        });

        function actionAfter()
        {
            if(after == "reload"){
                location.reload();
            }
            if(after == "delete-photo"){
                $(obj).parent().parent().fadeOut("slow").remove();
            }
            if(after == "change-visibility")
            {
                if($(obj).children().attr('class') == "icon-eye-open"){
                    $(obj).children().switchClass("icon-eye-open", "icon-eye-close");
                }else{
                    $(obj).children().switchClass("icon-eye-close", "icon-eye-open");
                }
            }
            if(after == "rotate-right"){
                var rotate = parseInt($(obj).parent().parent().find("img").attr("data-rotate-current")) + 90;
                $(obj).parent().parent().find("img").attr("data-rotate-current", rotate).rotate(rotate);
            }
            if(after == "rotate-left"){
                var rotate = parseInt($(obj).parent().parent().find("img").attr("data-rotate-current")) - 90;
                $(obj).parent().parent().find("img").attr("data-rotate-current", rotate).rotate(rotate);
            }

        }
    });
})(jQuery);
