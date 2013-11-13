(function($){
    $.fn.dataToForm = function(settings){

        var config = {
            prefix: "#form",
            callback: null
        };
        if (settings){$.extend(config, settings);}

        return this.each(function(){
            $(this).on('click', function () {

                $(this).each(function() {
                    $.each(this.attributes, function() {
                        if(this.specified) {
                            if(this.name.match(/^data-[a-z]+/) !== null)
                            {
                                var name = this.name.replace(/data/, config.prefix);

                                $(name).val(this.value);
                                console.log(name, this.value);
                            }
                        }
                    });

                    if (typeof config.callback == 'function') { // make sure the callback is a function
                        config.callback.call(this); // brings the scope to the callback
                    }
                });
            });
        });
    };
})(jQuery);