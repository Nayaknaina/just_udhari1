
(function($){
    "use strict";

    /*$("#contactForm").on("submit", function(event){
        event.preventDefault();
        submitForm();
    });*/

   /* $("#contactForm").on("submit", function(e){
        e.preventDefault();
        const data = $(this).serialize();
        $.post($(this).attr('action'),data,function(response){
            if(response.status && response.status==200){
                alert(response.message)
            }else{
                if(response.errors){
                    $.each(response.errors,function(i,v){
                        alert(i);
                        $.each(v,function(mi,mv){
                            alert(mv);
                        });
                    });
                }else{
                    alert(response.message);
                }
            }
        });
    });*/
    $("#contactForm").on("submit", function(e){
        e.preventDefault();

        let form = $(this);

        $(".error-text").remove();
        $(".form-control").removeClass("is-invalid");
        $("#msgSubmit").removeClass().hide();


        $("#msgSubmit")
            .show()
            .removeClass()
            .addClass("text-info")
            .html('<span class="spinner-border spinner-border-sm"></span> Sending...');

        $.post(form.attr('action'), form.serialize(), function(response){

            $("#msgSubmit").removeClass().hide();

            if(response.status){

                
                $("#msgSubmit")
                    .show()
                    .removeClass()
                    .addClass("alert alert-success mt-3")
                    .text(response.message);

                form[0].reset();
            }
            else if(response.errors){

                // SHOW ERRORS UNDER FIELDS
                $.each(response.errors, function(field, messages){
                    let input = $('[name="'+field+'"]');

                    input.addClass("is-invalid");
                    input.after(
                        '<div class="error-text text-danger mt-1">'+messages[0]+'</div>'
                    );
                });

                // Show general failed message
                // $("#msgSubmit")
                //     .show()
                //     .removeClass()
                //     .addClass("alert alert-danger mt-3")
                //     .text("Please fix the errors");
            }
            else {
                $("#msgSubmit")
                    .show()
                    .removeClass()
                    .addClass("alert alert-danger mt-3")
                    .text(response.message);
            }

        });

    });
    

   /* function submitForm(){
        let formData = {
            name: $("#name").val(),
            email: $("#email").val(),
            msg_subject: $("#msg_subject").val(),
            phone_number: $("#phone_number").val(),
            message: $("#message").val(),
            _token: $('input[name="_token"]').val()
        };
        

        $.ajax({
            type: "POST",
            url: $("#contactForm").attr("action"), 
            data: formData,
            success: function(response){
                if(response.status === 200){
                    formSuccess();
                } else {
                    formError();
                    submitMSG(false, response.message);
                }
            },
            error: function(xhr){
                formError();
                let error = "Something went wrong!";
                if(xhr.responseJSON && xhr.responseJSON.errors){
                    error = Object.values(xhr.responseJSON.errors)[0][0];
                }
                submitMSG(false, error);
            }
        });
    }

    function formSuccess(){
        $("#contactForm")[0].reset();
        submitMSG(true, "Message Submitted Successfully!");
    }

    function formError(){
        $("#contactForm")
            .removeClass()
            .addClass('shake animated')
            .one('animationend', function(){
                $(this).removeClass();
            });
    }

    function submitMSG(valid, msg){
        let msgClasses = valid 
            ? "h4 tada animated text-success" 
            : "h4 text-danger";

        $("#msgSubmit")
            .removeClass()
            .addClass(msgClasses)
            .text(msg);
    }
*/
})(jQuery);

