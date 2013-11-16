(function($){

    $.widget("luknei.magger", {
        data: {
        },
        //default options
        options: {
            tag: null,
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

            //callbacks
        },

        // the constrctor
        _create: function(){
            console.log("create called");
            $("." + this.options.formContainerClass).appendTo("." + this.options.tagContainerId).draggable();

        },

        _init: function(){
            console.log("init called");
            var obj = this.element.context.attributes;
            console.log(this);
            this.setGetters();
            this._setOptions();
            this.getAttributes(obj);
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
            if(this.element.maggerShow({getTagsUrl: this.options.getTagsUrl})){
                this.tagsConainer = $("." + this.options.tagContainerId);
                this.tagsForm = $("#" + this.options.formId);
                this.tags = this.tagsConainer.find("." + this.options.tagClass);
                this.closeButton = this.tagsConainer.find("." + this.options.formCloseClass);
                this.deleteButton = this.tagsConainer.find("." + this.options.formDeleteClass);

                console.log(this.tags);
            }

            this.unsetGetters();

            this._on(true, this.tagsConainer, {
                click: "onClickContainer"
            });

            this._on(true, this.tags, {
                click: "onClickTag"
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

        },

        unsetGetters: function(){

            $( this.tagsConainer ).unbind();
            $( this.tags ).unbind();
            $( this.tagsForm ).unbind();
            $( this.closeButton ).unbind();
            $( this.deleteButton ).unbind();

        },

        onClickDeleteButton: function(e){
            console.log("onClickDeleteButton clicked");
            this.options["formUrl"] = this.options.formDeleteUrl;
        },

        onClickCloseButton: function(e){
            e.stopPropagation();
            $("." + this.options.formContainerClass).hide();
        },

        onClickTag: function(e){
            e.stopPropagation();
            console.log("onClickTag called");

            var obj = e.currentTarget;
            var container = this.element.parent();
            var tag = this.options.tag;
            this.getAttributes(obj.attributes);
            this.fillTagForm(obj);
            this.options["formUrl"] = this.options.formEditUrl;
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
                    onSuccess(result, self)
                },
                error:function(xhr,status,error){
                    console.log(error);
                }
            });
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

            $.extend(this.data, this.getCoordinates(obj, e));

            if(tag == null){
                $("#" + options.formId).each(function() { this.reset() });
                var tagHolder = "<div class='tag-holder'>" + options.tagIcon + "</div>";
                tag = $("<div>" + tagHolder + "</div>").addClass(options.tagClass).addClass(options.tagColor);
                this.options["tag"] = tag;
                $("." + options.tagContainerId).append(tag);
                this.options["formUrl"] = options.formCreateUrl;
                $("." + options.formDeleteClass).hide();
            }
            tag.css({top: data.y, left:data.x});
            $("." + options.formContainerClass).css({top: data.y, left: data.x}).show();
        },

        fillTagForm: function(tag){
            console.log("fillTagForm called");
            console.log(tag);
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
                        console.log(name, this.value);
                    }
                }
            });
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
        }

     /*

        return this.each(function(){
      var imgObj = this;


            //clicking on form container
            $("." + options.formContainerClass).bind("click", function(e){
                e.stopPropagation();
            });

            $("#" + options.formId).on("click", "." + options.formCloseClass, function(e){
                e.stopPropagation();
                $("." + options.formContainerClass).hide();

            });

            //On edit tag click
            $("#" + options.tagContainerId).on("click", "." + options.tagClass, function(e){
                e.stopPropagation();
                $.extend(data, getCoordinates($("#" + options.tagContainerId), e));
                fillTagForm(this);
                formUrl = options.formEditUrl;
                if(tag !== undefined){
                    tag.remove();
                    tag = undefined;
                }
                $("." + options.formDeleteClass).show();
                $("." + options.formContainerClass).css({top: data.y, left: data.x}).show();
            });

            //On click delete
            $("#" + options.formId).on("click", "." + options.formDeleteClass, function(e){
                console.log("clicked delete");
                formUrl = options.formDeleteUrl;
                $("." + options.formContainerClass).css({top: data.y, left: data.x}).hide();
            });

            function fillTagForm(tag)
            {
                $.each(tag.attributes, function() {
                    if(this.specified) {
                        var dataPrefix = new RegExp(options.dataPrefix + ".+","g");
                        if(this.name.match(dataPrefix) !== null){
                            var name = this.name.replace(options.dataPrefix, "");
                            var tagId = new RegExp(options.dataPrefix + "tag-id","g")
                            if(this.name.match(tagId)){
                                if($("#tag-id").length == 0) {
                                    $("#" + options.formId).append("<input type='hidden' id='tag-id' name='tag-id'>").val(this.value);
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
      */
    });



    $.widget( "luknei.maggerShow", {
        options: {
            tagContainerId: 'photo-tag-container',
            dataPrefix: 'magger-',
            tagIcon: "<i class='icon-map-marker bigger-200'></i>",
            tagColor: "blue",
            tagClass: "magger-tag",
            getTagsUrl: ""
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
            this._setOptions();

            this.element.wrap("<div id='" + this.options.tagContainerId + "'></div>")

        },
        _init: function(){
            console.log("init called");

            $("." + this.options.tagClass).remove();

            var options = this.options;
            var data = {
                photo_id: this.element.attr("magger-photo-id")
            };
            $.ajax({
                url: this.options.getTagsUrl,
                type: "post",
                data: JSON.stringify(data),
                contentType: 'application/json',
                async:false,
                success: function(result,status,xhr){
                    console.log("Ajax response: " + result);
                    appendTags(result, options);
                },
                error:function(xhr,status,error){
                    console.log(error);
                }
            });
            function appendTags(data, options)
            {
                $.each(data, function(key, value){
                    var tagHolder = "<div class='tag-holder'>" + options.tagIcon + "</div>";
                    var tag = $("<div>" + tagHolder + "</div>").addClass(options.tagClass).addClass(options.tagColor);
                    $.each(value, function(name, value){
                        if(name == "x") tag.css({left: value});
                        if(name == "y") tag.css({top: value});
                        tag.attr(options.dataPrefix + name, value);
                    });
                    $("#" + options.tagContainerId).append(tag);
                });
                console.log("appended all tags");
            }

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
