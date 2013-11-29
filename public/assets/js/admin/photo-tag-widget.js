(function($){

    $.maggerRequests = [];

    $.widget("luknei.magger", {
        data: {
        },
        //default options
        options: {
            tag: null,
            tagContainerId: 'photo-tag-container',
            dataPrefix: 'magger-',
            replacementPrefix: "",
            tagIcon: "icon-map-marker",
            tagSize: "bigger-200",
            tagColor: "#4986e7",
            formId: "magger-form",
            formContainerClass: "magger-form-container",
            formSubmitClass: "magger-form-submit",
            formCloseClass: "magger-form-close",
            formDeleteClass: "magger-form-delete",
            tagClass: "magger-tag"

            //callbacks

        },

        // the constructor
        _create: function(){
            console.log("create called");
            $(".select-tag-color").maggerColorPicker();

        },

        _init: function(){
            console.log("init called");

            this.options["tag"] = null;
            this.setGetters();

            $("." + this.options.formContainerClass).appendTo($("." + this.options.tagContainerId)).draggable();

            this._setOptions();
            this.getAttributes(this.element.context.attributes);
        },

        // _setOptions is called with a hash of all options that are changing
        // always refresh when changing options
        _setOptions: function() {
            console.log("setOptions called");
            // _super and _superApply handle keeping the right this-context
            //this._superApply( arguments );
            //this._refresh();
        },

        setGetters: function(){
            console.log("setGetters called");


            if($("#" + this.options.tagContainerId).find("." + this.options.tagContainerId).length > 0){
                $("#" + this.options.tagContainerId).find("img").unwrap();
            }
            this.element.maggerShow({
                getTagsUrl: this.options.getTagsUrl,
                async: false});

            this.tagsConainer = $(document).find("." + this.options.tagContainerId);
            this.tagsForm = $("#" + this.options.formId);
            this.tags = $(document).find("." + this.options.tagClass);
            this.closeButton = $(document).find("." + this.options.formCloseClass);
            this.deleteButton = $(document).find("." + this.options.formDeleteClass);
            console.log(this.closeButton);
            this.colorpickButton = $(document).find(".color-pick-button").removeClass("selected");

            this.unsetGetters();

            this._on(true, this.tags, {
                click: "onClickTag"
            });

            this._on(true, this.tagsConainer, {
                click: "onClickContainer"
            });

            this._on(true, this.tagsForm, {
                submit: "onFormSubmit",
                click: function(e){
                    e.stopPropagation();
                }
            });

            this._on(true, this.closeButton, {
                click: "onClickCloseButton"
            });

            this._on(true, this.deleteButton, {
                click: "onClickDeleteButton"
            });

            this._on(true, this.colorpickButton, {
                click: "onClickColorpickButton"
            });

        },

        unsetGetters: function(){

            $( this.tagsConainer ).unbind();
            $( this.tags ).unbind();
            $( this.tagsForm ).unbind();
            $( this.closeButton ).unbind();
            $( this.deleteButton ).unbind();
            $( this.colorpickButton ).unbind();

        },

        onClickColorpickButton: function(e){
            console.log("onClickColorpickButton clicked");
            this.colorpickButton.removeClass("selected");
            var color = $(e.currentTarget).addClass("selected").attr("data-color");
            var newTag = this.options.tag;
            if(color !== undefined && this.editableTag !== undefined){
                this.editableTag.css({color: color});
            }
            if(color !== undefined && newTag !== null){
                newTag.css({color: color});
            }

        },

        onClickDeleteButton: function(e){
            console.log("onClickDeleteButton clicked");
            this.options["formUrl"] = this.options.formDeleteUrl;
        },

        onClickCloseButton: function(e){
            console.log("onClickCloseButton clicked");
            e.stopPropagation();
            $(document).find("." + this.options.formContainerClass).hide();
        },

        onClickTag: function(e){
            e.stopPropagation();
            console.log("onClickTag called");
            var obj = e.currentTarget;
            var tag = this.options.tag;
            this.onDrag($(obj));
            this.getAttributes(obj.attributes);
            this.fillTagForm(obj);
            this.options["formUrl"] = this.options.formEditUrl;
            this.editableTag = $(obj);
            if(tag !== null){
                tag.remove();
                tag = null;
            }
            $("." + this.options.formDeleteClass).show();
            $("." + this.options.formContainerClass).css({top: this.data.y, left: this.data.x}).show();
        },

        onFormSubmit: function(e){
            e.preventDefault();
            console.log("onFormSubmit called");

            $.extend(this.data, this.tagsForm.serializeObject());
            var self = this;

            $.ajax({
                url: this.options.formUrl,
                type: "post",
                data: JSON.stringify(this.data),
                contentType: 'application/json',
                success: function(result,status,xhr){
                    onSuccess(result, self);
                    console.log(this.data);
                    console.log(result);
                },
                error:function(xhr,status,error){
                    console.log(error);
                    alert("Error, please try again.");
                }
            })
            function onSuccess(data, obj){
                $(obj.options.tag).remove();
                $("." + obj.options.formContainerClass).hide();
                obj.options["tag"] = null;
                self.setGetters();
            }

        },

        onClickContainer: function(e){
            console.log("onClickContainer called");
            var obj = this.element.parent();
            var tag = this.options.tag;
            var options = this.options;
            var data = this.data;
            this.editableTag = undefined;
            this.deleteButton.hide();

            $.extend(this.data, this.getCoordinates(obj, e));
            console.log(tag);
            if(tag == null){
                var tag = this.tagDiv(this.options);
                $("#" + options.formId).each(function() { this.reset() });
                this.options["tag"] = tag;
                this.options["formUrl"] = options.formCreateUrl;
                $("." + options.formDeleteClass).hide();
            }
            $("." + options.tagContainerId).append(tag);
            tag.css({top: data.y, left:data.x});
            $("." + options.formContainerClass).css({top: data.y, left: data.x}).show();
        },

        fillTagForm: function(tag){
            console.log("fillTagForm called");
            var options = this.options;
            $.each(tag.attributes, function() {
                if(this.specified) {
                    var dataPrefix = new RegExp(options.dataPrefix + ".+","g");
                    if(this.name.match(dataPrefix) !== null){
                        var name = this.name.replace(options.dataPrefix, "");
                        var tagId = new RegExp(options.dataPrefix + "tag-id","g");
                        if(this.name.match(tagId)){
                            if($("#tag-id").length == 0) {
                                $("#" + options.formId).append("<input type='hidden' id='tag-id' name='tag-id'>").val(this.value);
                            }else{
                                $("#tag-id").val(this.value);
                            }
                        }
                        $("#" + name).val(this.value);
                    }
                }
            });
        },

        onDrag: function(obj){
            var self = this;
            var coords;
            $(obj).draggable({
                containment: self.tagsConainer,
                stop: function(e){
                    coords = self.getCoordinates(self.tagsConainer, e);
                    $(obj).attr({"magger-x": coords.x, "magger-y": coords.y})
                }
            });
            return coords;
        },

        getCoordinates: function(obj, e){
            var relX = e.pageX - obj.offset().left;
            var relY = e.pageY - obj.offset().top;
            var percentOffsetX = relX / obj.width() * 100;
            var percentOffsetY = relY / obj.height() * 100;
            var x = Math.round( percentOffsetX * 100 ) / 100;
            var y = Math.round( percentOffsetY * 100 ) / 100;
            return {x: x + "%", y: y + "%"};
        },

        getAttributes: function(obj){
            console.log("getAttributes called");

            var dataPrefix = this.options.dataPrefix;
            var replacementPrefix = this.options.replacementPrefix;
            var data = this.data;

            $.each(obj, function() {
                if(this.specified) {
                    var regex = new RegExp(dataPrefix + ".+","g");
                    if(this.name.match(regex) !== null)
                    {
                        var name = this.name.replace(dataPrefix, replacementPrefix);
                        data[name] = this.value;
                    }
                }
            });
        },
        tagDiv: function(options, tagIcon, tagSize, tagColor){
            console.log(tagColor, tagIcon, tagSize)
            var tagIcon = typeof icon == 'undefined' || icon == null ? options.tagIcon : tagIcon;
            var tagSize = typeof tagSize == 'undefined' || tagSize == null ? options.tagSize : tagSize;
            var tagColor = typeof tagColor == 'undefined' || tagColor == null ? options.tagColor : tagColor;

            var icon = $("<i></i>")
                .addClass(tagIcon)
                .addClass(tagSize)
            var holder = $("<div class='tag-holder'></div>")
                .append(icon)
                .addClass("tag-holder");
            return $("<div></div>")
                .append(holder)
                .addClass(this.options.tagClass)
                .css({color: tagColor});
        }

    });



    $.widget( "luknei.maggerShow", {
        options: {
            tagContainerId: 'photo-tag-container',
            dataPrefix: 'magger-',
            tagIcon: "icon-map-marker",
            tagSize: "bigger-200",
            tagColor: "#478fca",
            tagClass: "magger-tag",
            getTagsUrl: "",
            async: true,

            ////callbacks
            onTagsLoaded: function(){}
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
            this.element.wrap("<div class='" + this.options.tagContainerId + "'></div>");

            this.thePopover = this.createPopover();
            this.loadTags();

        },

        enableTooltips: function(){
            console.log("enableTooltips called");

            this.loadedTags = $("." + this.options.tagContainerId).find("." + this.options.tagClass);

            this.loadedTags.unbind();
            this._on(this.loadedTags, {
                mouseenter: function(e){
                    console.log("enter");
                    obj = $(e.currentTarget);

                    $(".popover .popover-title").empty().append(obj.attr("magger-tag-title"));
                    $(".popover .popover-content").empty().append(obj.attr("magger-tag-description"));

                    $(".popover").show().css({display: "block", top: obj.attr("magger-y"), left: obj.attr("magger-x")});

                },
                mouseleave: function(e){
                    console.log("leave");
                    $(".popover").stop().hide("fast");
                }
            });

            this._on($(".popover"), {
                mouseenter: function(e){
                    $(".popover").stop().show();

                },
                mouseleave: function(e){
                    $(".popover").stop().hide("fast");
                }
            });

        },

        createPopover: function(){
            $(document).find('.popover').remove();
            var frame = $("<div></div>",{
                class: "popover fade in bottom"
            });
            $("<div></div>", {
                class: "arrow"
            }).appendTo(frame);
            $("<h3></h3>", {
                class: "popover-title"
            }).appendTo(frame);
            $("<div></div>", {
                class: "popover-content"
            }).appendTo(frame);

            $(document).find(".photo-tag-container").append(frame);

            return frame;
        },

        loadTags: function(){
            console.log("loadTags called")
            $(document).find("." + this.options.tagClass).remove();

            var obj = this;
            var options = this.options;
            var data = {
                photo_id: this.element.attr("magger-photo-id")
            };

            for(var i = 0; i < $.maggerRequests.length; i++)
                $.maggerRequests[i].abort();

            $.maggerRequests.push(
                $.ajax({
                    url: this.options.getTagsUrl,
                    type: "post",
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    async:this.options.async,
                    success: function(result,status,xhr){
                        console.log(result);
                        appendTags(result, options, obj);
                        obj.options.onTagsLoaded();
                    },
                    error:function(xhr,status,error){
                        console.log(error);
                    }
                })
            )
            function appendTags(data, options, obj)
            {
                $.each(data, function(key, value){
                    var tag = obj.tagDiv(obj.options, value["tag-icon"], value["tag-size"], value["tag-color"]);
                    $.each(value, function(name, value){
                        if(name == "x") tag.css({left: value});
                        if(name == "y") tag.css({top: value});
                        tag.attr(options.dataPrefix + name, value);
                    });
                    $("." + options.tagContainerId).append(tag);
                });
                console.log("appended all tags");
                obj.enableTooltips();
            }
        },

        tagDiv: function(options, tagIcon, tagSize, tagColor){
            var tagIcon = (typeof icon == 'undefined' || icon == null || tagIcon == "") ? options.tagIcon : tagIcon;
            var tagSize = (typeof tagSize == 'undefined' || tagSize == null || tagSize == "") ? options.tagSize : tagSize;
            var tagColor = (typeof tagColor == 'undefined' || tagColor == null || tagColor == "") ? options.tagColor : tagColor;

            var icon = $("<i></i>")
                .addClass(tagIcon)
                .addClass(tagSize)
            var holder = $("<div class='tag-holder'></div>")
                .append(icon)
                .addClass("tag-holder");
            return $("<div></div>")
                .append(holder)
                .addClass(this.options.tagClass)
                .attr('data-rel', 'popover')
                .css({color: tagColor});
        }
    });

    $.widget( "luknei.maggerColorPicker", {
        _create: function(){
            console.log("create called");
            var radio_buttons = this.element.find(".color-pick-radio");
            var i = 1;
            $.each(radio_buttons, function(){

                var radio = $(this);
                var id = "color" + i;
                var color = radio.val();
                var label = $("<label></label>").addClass("color-pick-button").attr("for", id).attr("data-color", color).css({background: color});
                radio.attr("id", id).hide();
                radio.wrap("<li></li>").wrap(label);
                i++;
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
