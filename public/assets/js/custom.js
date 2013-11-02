/**
 * Created by Luko on 13.11.1.
 */

$(document).ready(function (endforeach){
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

    $(".ajax").click(function(event)
    {
        event.preventDefault();

        var url = $(this).attr("href");
        var id = $(this).attr('data-id');

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
            },
            error:function(xhr,status,error){
                gritter("error", "Error", "There was an error. The the request was denied. Please try again.");
            }
        });

    });
})