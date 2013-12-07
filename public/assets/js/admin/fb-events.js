/*
window.fbAsyncInit = function() {
    FB.init({
        appId      : '367895586644041', // App ID
        //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
    FB.Event.subscribe('edge.create',
        function(href, widget) {
            var type = $(widget).attr('data-type');
            var id = $(widget).attr('data-id');
            if(type == "album"){
                sendData($.albumCommentsUrl, "increment", "id");
            }
            if(type == "photo"){
                sendData($.photoCommentsUrl, "increment", "id");
            }
        }
    );

    FB.Event.subscribe('edge.remove',
        function(href, widget) {
            var type = $(widget).attr('data-type');
            var id = $(widget).attr('data-id');
            if(type == "album"){
                sendData($.albumCommentsUrl, "decrement", "id");
            }
            if(type == "photo"){
                sendData($.photoCommentsUrl, "decrement", "id");
            }
        }
    );

    FB.Event.subscribe('comment.create',
        function(response) {
            //onCommentCreate(response.commentID,response.href); //Handle URL on function to store on database
            console.log(response.href);
        }
    );

    function sendData(url, action, id)
    {
        var data = {
            token: token,
            action: action,
            id: id
        }

        $.ajax(
            $.ajax({
                url: url,
                type: "post",
                data: JSON.stringify(data),
                contentType: 'application/json',
                async:true
            })
        )
    }
}

(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);

}(document));
*/
window.fbAsyncInit = function() {
    FB.init({
        //appId      : '367895586644041', // App ID
        //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
    });

    // Additional initialization code here
    FB.Event.subscribe('edge.create',
        function(href, widget) {
            var type = $(widget).attr('data-type');
            var url = $(widget).attr('data-href');

            if(type == "photo"){
                console.log(url);
                sendData(url, "increment");
            }
        }
    );

    FB.Event.subscribe('edge.remove',
        function(href, widget) {
            var type = $(widget).attr('data-type');
            var url = $(widget).attr('data-href');

            if(type == "photo"){
                sendData(url, "decrement");
            }
        }
    );

    FB.Event.subscribe('comment.create',
        function(response) {
            //onCommentCreate(response.commentID,response.href); //Handle URL on function to store on database
            console.log(response.href);
        }
    );

    FB.Event.subscribe('comment.create',
        function(response) {
            //onCommentCreate(response.commentID,response.href); //Handle URL on function to store on database
            console.log(response.href);
        }
    );

    FB.Event.subscribe('comment.create',
        function(response) {
            //onCommentCreate(response.commentID,response.href); //Handle URL on function to store on database
            console.log(response.href);
        }
    );

    function sendData(url, action)
    {
        var data = {
            token: token,
            action: action
        }
        console.log(url);

        $.ajax({
            url: url,
            type: "post",
            data: JSON.stringify(data),
            contentType: 'application/json',
            async:true
        })

    }
};

// Load the SDK Asynchronously

    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);

    }(document));
