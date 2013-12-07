(function($){
    $.likesRequests = [];

    $.widget( "luknei.showLikes", {
        data: {

        },
        options: {
            message: " people like this photo."
            ////callbacks
        },

        // _setOptions is called with a hash of all options that are changing
        // always refresh when changing options
        _setOptions: function() {
            console.log("setOptions called");
            // _super and _superApply handle keeping the right this-context
            this._superApply( arguments );
            //this._refresh();
        },
        // called when created, and later when changing options
        _refresh: function() {
            console.log("refresh called");
        },

        _create: function(){
            console.log("create called");

        },
        _init: function(){
            console.log("init called");
            this._setOptions();

            this.element.empty();
            this.setData();
            this.showLikes();

        },
        setData: function(){
            var data =  {
                type: this.element.attr('likes-type'),
                obj_id: this.element.attr('likes-obj-id')
            }
            $.extend(this.data, data);
        },

        showLikes: function(){
            var obj = this;

            for(var i = 0; i < $.likesRequests.length; i++)
                $.likesRequests[i].abort();
            $.likesRequests.push(
                $.ajax({
                    url: this.element.attr('likes-href'),
                    type: "post",
                    data: JSON.stringify(this.data),
                    contentType: 'application/json',
                    async:true,
                    success: function(result,status,xhr){
                        console.log(result);
                        obj.appendLikes(result);
                        obj.submitLike();
                    },
                    error:function(xhr,status,error){
                        //$.grit("error", "Error", "There was an error. The the request was denied. Please try again.");
                    }
                })
            );
        },
        appendLikes: function(likes){
            this.element.empty();
            if(likes.user_likes > 0){
                var content = "" +
                    "<a href='#' class='like-button' title='Unlike this " + this.data.type + "'>" +
                        "<i class='icon-heart-empty red bigger-150'></i>" +
                    "</a> " +
                    likes.no_likes + this.options.message +
                    "" +
                    "";
            }else{
                var content = "" +
                    "<a href='#' class='like-button' title='Like this  " + this.data.type + "'>" +
                        "<i class='icon-heart red bigger-150'></i>" +
                    "</a> " +
                    likes.no_likes + this.options.message +
                    "" +
                    "";
            }
            $(this.element).append(content);
        },
        submitLike: function(){
            var obj = this;
            this.likeButton = $(document).find('.like-button');
            this.likeButton.unbind();

            this._on(true, this.likeButton, {
                click: function(e){
                    console.log('like clicked');
                    e.preventDefault();
                    $.ajax({
                        url: obj.options.postLikeUrl,
                        type: "post",
                        data: JSON.stringify(obj.data),
                        contentType: 'application/json',
                        async:true,
                        success: function(result,status,xhr){
                            console.log(result);
                            obj.showLikes()
                        },
                        error:function(xhr,status,error){
                            //$.grit("error", "Error", "There was an error. The the request was denied. Please try again.");
                        }
                    });
                }
            });

        }

    });

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

})(jQuery);
