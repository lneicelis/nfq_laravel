/**
 * Created by Luko on 13.11.1.
 */

function gritter(key, title, message)
{
    var gritterClass = 'gritter-' + key + ' gritter-light'
    $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: title,
        // (string | mandatory) the text inside the notification
        text: message,
        class_name: gritterClass
    });
}

$(document).ready(function (){

    $(".ajax").click(function(event)
    {
        event.preventDefault();

        var url = $(this).attr("href");
        var id = $(this).attr('data-id');
        var action = $(this).attr('data-action');
        var deleteItem = $(this).parent().parent();

        var data = {
            _token: token, //global token, that has been set in the head of HTML
            id : id
        };

        $.ajax({
            url: url,
            type: "post",
            data: data,
            success: function(result,status,xhr){
                gritter(result.type, result.title, result.message);
                deletePhoto();
            },
            error:function(xhr,status,error){
                gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
            }
        });

        function deletePhoto()
        {
            if(action == "delete-photo"){
                deleteItem.fadeOut("slow");
                deleteItem.remove();
            }
        }

    });
})

jQuery(function ($) {


    $("#transfer").click(function(event){
        event.preventDefault();
        $(".transfer-div").toggle("fast");
        $(".chosen-select").chosen();
    })

    var colorbox_params = {
        reposition: true,
        scalePhotos: true,
        scrolling: false,
        previous: '<i class="icon-arrow-left"></i>',
        next: '<i class="icon-arrow-right"></i>',
        close: '&times;',
        current: '{current} of {total}',
        maxWidth: '100%',
        maxHeight: '100%',
        onOpen: function () {
            document.body.style.overflow = 'hidden';
        },
        onClosed: function () {
            document.body.style.overflow = 'auto';
        },
        onComplete: function () {
            $.colorbox.resize();
        }
    };

    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

});

$('.photo-edit-form').on('click', function () {
    var photoId = $(this).attr('data-photo-id');
    var description = $(this).attr('data-photo-description');

    $("#photo-edit-form-album-id").attr('value', photoId);

    if(description !== false){
        $("#photo-edit-form-description").val(description);
    }else{
        $("#photo-edit-form-description").val('');
    }

    $('#photo-edit-modal-form').modal('show')
});