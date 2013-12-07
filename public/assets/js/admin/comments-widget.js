(function($){
    $.commentsRequests = [];

    $.widget( "luknei.showComments", {
        data: {

        },
        options: {

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
            this.appendForm();
            this.showComments();
            this.onFormSubmit();

        },
        setData: function(){
            var data =  {
                type: this.element.attr('comments-type'),
                obj_id: this.element.attr('comments-obj-id')
            }
            $.extend(this.data, data);
        },

        showComments: function(){
            var obj = this;

            for(var i = 0; i < $.commentsRequests.length; i++)
                $.commentsRequests[i].abort();
            $.commentsRequests.push(
                $.ajax({
                    url: this.element.attr('comments-href'),
                    type: "post",
                    data: JSON.stringify(this.data),
                    contentType: 'application/json',
                    async:true,
                    success: function(result,status,xhr){
                        console.log(result);
                        obj.appendComments(result)
                    },
                    error:function(xhr,status,error){
                        //$.grit("error", "Error", "There was an error. The the request was denied. Please try again.");
                    }
                })
            );
        },
        appendComments: function(comments){
            this.element.find('.dialogs').remove();
            var options = this.options;
            var content = "";
            console.log(comments.length);
            if(comments.length == 0){
                content = '<div class="dialogs">' +
                    'There are no comments yet. Be first to comment!' +
                '</div>';
            }else{
                $.each(comments, function(){
                    content += '<div class="dialogs">' +
                        '<div class="itemdiv dialogdiv"> ' +
                            '<div class="user">' +
                                '<img alt="' + this.first_name + ' Avatar" src="' + options.profileImgUrl + this.picture + '" />' +
                            '</div>' +
                            '<div class="body">' +
                                '<div class="time">' +
                                    '<i class="icon-time"></i>' +
                                    '<span class="green">' + this.created_at + '</span>' +
                                '</div>' +
                                '<div class="name">' +
                                    '<a href="' + options.profileUrl  + this.id + '">' + this.first_name  + ' ' +  this.last_name + '</a>' +
                                '</div>' +
                                '<div class="text">' + this.comment + '</div>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                });
            }
            $(this.element).append(content);
        },
        appendForm: function(){
            var form = '<form id="comments-form" method="post" action="' + this.options.formActionUrl + '">' +
                '<div class="form-actions">' +
                    '<div class="input-group">' +
                        '<input placeholder="Type your comment here ..." type="text" class="form-control" name="comment" />' +
                        '<span class="input-group-btn">' +
                            '<button class="btn btn-sm btn-info no-radius" type="submit">' +
                                '<i class="icon-share-alt"></i> ' +
                                '' +
                            '</button>' +
                        '</span>' +
                    '</div>' +
                '</div>' +
            '</form>';
            $(this.element).append(form);
        },
        onFormSubmit: function(){
            console.log('form submit initiated');

            var obj = this;
            this.commentsForm = $( document ).find( "#comments-form" );
            $( this.commentsForm ).unbind();
            this._on(true, this.commentsForm, {
                submit: function(e){
                    e.preventDefault();

                    $.extend(obj.data, obj.commentsForm.serializeObject());
                    console.log(obj.data);
                    $.ajax({
                        url: obj.options.formActionUrl,
                        type: "post",
                        data: JSON.stringify(this.data),
                        contentType: 'application/json',
                        async:true,
                        success: function(result,status,xhr){
                            $(obj.commentsForm).each(function() { this.reset() });
                            obj.showComments();
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
