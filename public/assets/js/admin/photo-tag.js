(function($){
    $.fn.magger = function(settings){
        var data = {}, tag, formUrl;
        var config = {
            tagContainerId: 'photo-tag-container',
            dataPrefix: 'magger-',
            replacementPrefix: "",
            tagIcon: "<i class=\"icon-map-marker bigger-200\"></i>",
            tagColor: "blue",
            formId: "magger-form",
            formContainerClass: "magger-form-container",
            formSubmitClass: "magger-form-submit",
            formCloseClass: "magger-form-close",
            formDeleteClass: "magger-form-delete",
            tagClass: "magger-tag"
        };

        if (settings){$.extend(config, settings);}

        return this.each(function(){
            var imgObj = this;
            $(this).maggerShow({getTagsUrl: config.getTagsUrl});

            $("." + config.formContainerClass).appendTo("#" + config.tagContainerId).draggable();

            //getting attributes that needs to be passed to form from image
            $.each(this.attributes, function() {
                if(this.specified) {
                    var dataPrefix = new RegExp(config.dataPrefix + ".+","g");
                    if(this.name.match(dataPrefix) !== null)
                    {
                        var name = this.name.replace(config.dataPrefix, config.replacementPrefix)
                        data[name] = this.value;

                        console.log(name, this.value);
                    }
                }
            });

            console.log(data);
            //ON tag container click (creating new or editing existing tag)
            $(document).on("click", "#" + config.tagContainerId, function(e){
            console.log(data);
                $.extend(data, getCoordinates($(this), e));
                console.log("Click X:" + data.x, "Y:" + data.y);
                if(tag == undefined){
                    $("#" + config.formId).each(function() { this.reset() });
                    tag = $("<div>" + config.tagIcon + "</div>").addClass(config.tagClass).addClass(config.tagColor);
                    $("#" + config.tagContainerId).append(tag);
                    formUrl = config.formCreateUrl;
                    $("." + config.formDeleteClass).hide();
                }
                tag.css({top: data.y, left:data.x});
                $("." + config.formContainerClass).css({top: data.y, left: data.x}).show();
            });

            //On form submit
            $("#" + config.formId).bind("submit", function(e){
                e.preventDefault();
                var formData = $(this).serializeObject();
                $.extend(data, formData);

                console.log("Form is ready to be submitted to" + formUrl);
                console.log(data);

                $.ajax({
                    url: formUrl,
                    type: "post",
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(result,status,xhr){
                        console.log(result);
                        $(imgObj).maggerShow({getTagsUrl: config.getTagsUrl});
                        $("." + config.formContainerClass).hide();
                        tag = undefined;
                    },
                    error:function(xhr,status,error){
                        console.log(error);
                    }
                });
            });

            //clicking on form container
            $("." + config.formContainerClass).bind("click", function(e){
                e.stopPropagation();
            });

            $("#" + config.formId).on("click", "." + config.formCloseClass, function(e){
                e.stopPropagation();
                $("." + config.formContainerClass).hide();

            });

            //On edit tag click
            $("#" + config.tagContainerId).on("click", "." + config.tagClass, function(e){
                e.stopPropagation();
                $.extend(data, getCoordinates($("#" + config.tagContainerId), e));
                fillTagForm(this);
                formUrl = config.formEditUrl;
                if(tag !== undefined){
                    tag.remove();
                    tag = undefined;
                }
                $("." + config.formDeleteClass).show();
                $("." + config.formContainerClass).css({top: data.y, left: data.x}).show();
            });

            //On click delete
            $("#" + config.formId).on("click", "." + config.formDeleteClass, function(e){
                console.log("clicked delete");
                formUrl = config.formDeleteUrl;
                $("." + config.formContainerClass).css({top: data.y, left: data.x}).hide();
            });

            function fillTagForm(tag)
            {
                $.each(tag.attributes, function() {
                    if(this.specified) {
                        var dataPrefix = new RegExp(config.dataPrefix + ".+","g");
                        if(this.name.match(dataPrefix) !== null){
                            var name = this.name.replace(config.dataPrefix, "");
                            var tagId = new RegExp(config.dataPrefix + "tag-id","g")
                            if(this.name.match(tagId)){
                                if($("#tag-id").length == 0) {
                                    $("#" + config.formId).append("<input type='hidden' id='tag-id' name='tag-id'>").val(this.value);
                                }else{
                                    $("#tag-id").val(this.value);
                                }
                            }
                            $("#" + name).val(this.value);
                            console.log(name, this.value);
                        }
                    }
                });
            }

            function getCoordinates(obj, e)
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
    };

    $.fn.maggerShow = function(settings){
        var config = {
            tagContainerId: 'photo-tag-container',
            dataPrefix: 'magger-',
            tagIcon: "<i class=\"icon-map-marker bigger-200\"></i>",
            tagColor: "blue",
            tagClass: "magger-tag"
        };

        if (settings){$.extend(config, settings);}

            if($("#" + config.tagContainerId).length == 0){
                $(this).wrap("<div id='" + config.tagContainerId + "'></div>");
            }
            $("#" + config.tagClass).remove();

            var photoId = $(this).attr("magger-photo-id");
            var data = {
                photo_id: photoId
            };
            $("." + config.tagClass).remove();
            $.ajax({
                url: config.getTagsUrl,
                type: "post",
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(result,status,xhr){
                    console.log(result);
                    appendTags(result);
                },
                error:function(xhr,status,error){
                    console.log(error);
                }
            });

            function appendTags(data)
            {
                $.each(data, function(key, value){
                    var tag = $("<div>" + config.tagIcon + "</div>").addClass(config.tagClass).addClass(config.tagColor);
                    $.each(value, function(name, value){
                        if(name == "x") tag.css({left: value});
                        if(name == "y") tag.css({top: value});
                        tag.attr(config.dataPrefix + name, value);
                    });
                    $("#" + config.tagContainerId).append(tag);
                });
            }


    };

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
