/**
 * Created by Luko on 13.11.1.
 */

function gritter(key, title, message)
{
    var gritterClass = 'gritter-' + key + ' gritter-light';
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

$(function(){
    var $moveFrom = "#album-from";
    var $moveTo = "#album-to";
    var $albumToId = "data-album-to-id";
    var $photoId = "data-photo-id";

    function initDragAndDrop(){
        $( "li", "#album-from").draggable({
            revert: "invalid", // when not dropped, the item will revert back to its initial position
            containment: "document",
            helper: "clone",
            cursor: "move"
        });

        $("#album-to").droppable({
            accept: "#album-from > li",
            activeClass: "custom-state-active",
            drop: function( event, ui ) {
                var url = $postTransferUrl;
                var obj = ui.draggable;
                var data = {
                    album_id: $($moveTo).attr($albumToId),
                    photo_id: $(obj).attr($photoId)
                }

                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    success: function(result,status,xhr){
                        movePhotoAnimation( obj );
                        gritter("success", "Success", "The photo has been successfully moved.");
                        console.log('ajax move success');
                    },
                    error:function(xhr,status,error){
                        event.preventDefault();
                        gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
                        console.log('ajax move error');
                    }
                });

            }
        });
    }

    function movePhotoAnimation( $item ) {
        $item.fadeOut(function() {

            $item.find( "div.tools" ).remove();
            $item.appendTo( $moveTo ).fadeIn(function() {
                $item
                    .animate({
                        width: "100px",
                        height: "100px"})
                    .find("img")
                    .animate({
                        width: "100px",
                        height: "100px"});
            });
        });
    }

    $(".select-album").change(function(event)
    {
        event.preventDefault();

        var url = $getPhotos;
        var id = $(this).val();
        var list = '';
        var appendTo = "#album-to";
        var data = {
            _token: token, //global token, that has been set in the head of HTML
            id : id
        };

        $.ajax({
            url: url,
            type: "post",
            data: data,
            success: function(result,status,xhr){
                $.each(result, function (index, value) {
                    list = list +
                        "<li class=\"small-photo-thumb\">" +
                        "<a href=\"\" title=\"\">" +
                        "<img class=\"small-photo-thumb\" src=\"" + $thumbsUrl + "/" + value.file_name + "\" />" +
                        "</a>" +
                        "</li>";
                });
                $(appendTo).attr($albumToId, id).empty().append(list);
                initDragAndDrop();
            },
            error:function(xhr,status,error){
                //gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
            }
        });

    });
});