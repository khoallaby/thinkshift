(function($){


    var emailFill = {
        init: function( container ) {

            var checkExist = setInterval(function() {
                if ($(container).length) {
                    // todo: move variable out
                    var userEmail = '{{user_email}}';
                    clearInterval(checkExist);
                    $(container).find('input[type=text]').val(userEmail);
                    $(container).hide();
                }
            }, 200);
        }

    };


    $(document).ready(function() {
        emailFill.init('#sgE-3417323-1-11-box');
        emailFill.init('#sgE-3265679-8-96-box');
        emailFill.init('#sgE-3325758-1-174-box');

    });


})(jQuery);