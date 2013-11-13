(function($){
    $.grit = function(key, title, message)
    {
        console.log(key);
        console.log(title);
        console.log(message);

        var gritterClass = 'gritter-' + key + ' gritter-light';
        $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: title,
            // (string | mandatory) the text inside the notification
            text: message,
            class_name: gritterClass
        });
    }
})(jQuery);