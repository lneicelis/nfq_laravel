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
                                    '<span class="green"> ' + $.timeSpan(this.created_at) + '</span>' +
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

    $.timeSpan = function(date, ref_date, date_formats, time_units) {
        //Date Formats must be be ordered smallest -> largest and must end in a format with ceiling of null
        date_formats = date_formats || {
            past: [
                { ceiling: 60, text: "$seconds seconds ago" },
                { ceiling: 3600, text: "$minutes minutes ago" },
                { ceiling: 86400, text: "$hours hours ago" },
                { ceiling: 2629744, text: "$days days ago" },
                { ceiling: 31556926, text: "$months months ago" },
                { ceiling: null, text: "$years years ago" }
            ],
            future: [
                { ceiling: 60, text: "in $seconds seconds" },
                { ceiling: 3600, text: "in $minutes minutes" },
                { ceiling: 86400, text: "in $hours hours" },
                { ceiling: 2629744, text: "in $days days" },
                { ceiling: 31556926, text: "in $months months" },
                { ceiling: null, text: "in $years years" }
            ]
        };
        //Time units must be be ordered largest -> smallest
        time_units = time_units || [
            [31556926, 'years'],
            [2629744, 'months'],
            [86400, 'days'],
            [3600, 'hours'],
            [60, 'minutes'],
            [1, 'seconds']
        ];

        date = new Date(date);
        ref_date = ref_date ? new Date(ref_date) : new Date();
        var seconds_difference = (ref_date - date) / 1000;

        var tense = 'past';
        if (seconds_difference < 0) {
            tense = 'future';
            seconds_difference = 0-seconds_difference;
        }

        function get_format() {
            for (var i=0; i<date_formats[tense].length; i++) {
                if (date_formats[tense][i].ceiling == null || seconds_difference <= date_formats[tense][i].ceiling) {
                    return date_formats[tense][i];
                }
            }
            return null;
        }

        function get_time_breakdown() {
            var seconds = seconds_difference;
            var breakdown = {};
            for(var i=0; i<time_units.length; i++) {
                var occurences_of_unit = Math.floor(seconds / time_units[i][0]);
                seconds = seconds - (time_units[i][0] * occurences_of_unit);
                breakdown[time_units[i][1]] = occurences_of_unit;
            }
            return breakdown;
        }

        function render_date(date_format) {
            var breakdown = get_time_breakdown();
            var time_ago_text = date_format.text.replace(/\$(\w+)/g, function() {
                return breakdown[arguments[1]];
            });
            return depluralize_time_ago_text(time_ago_text, breakdown);
        }

        function depluralize_time_ago_text(time_ago_text, breakdown) {
            for(var i in breakdown) {
                if (breakdown[i] == 1) {
                    var regexp = new RegExp("\\b"+i+"\\b");
                    time_ago_text = time_ago_text.replace(regexp, function() {
                        return arguments[0].replace(/s\b/g, '');
                    });
                }
            }
            return time_ago_text;
        }

        return render_date(get_format());
    }
})(jQuery);
