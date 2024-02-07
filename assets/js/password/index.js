var form = $(".steps-validation").show();
$(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            previous: '<i class="fa fa-arrow-left mr-1" /> Previous',
            next: 'Next <i class="fa fa-arrow-right ml-1" />',
            finish: 'Submit <i class="fa fa-paper-plane ml-1" />',
            /* finish: "Submit"  */
        },
        onStepChanging: function(e, t, i) {
            return (
                t > i ||
                (!(3 === i && Number($("#age-2").val()) < 18) &&
                    (t < i &&
                        (form.find(".body:eq(" + i + ") label.error").remove(),
                            form.find(".body:eq(" + i + ") .error").removeClass("error")),
                        (form.validate().settings.ignore = ":disabled,:hidden"),
                        form.valid()))
            );
        },
        onFinishing: function(e, t) {
            return (form.validate().settings.ignore = ":disabled"), form.valid();
        },
        onFinished: function(e, t) {
            e.preventDefault();
            var controller = 'main';
            var formData = new FormData(this);
            if (formData) {
                sweetedit(controller, formData);
            }
        },
    }),
    $(".steps-validation").validate({
        ignore: "input[type=hidden]",
        errorClass: "danger",
        successClass: "success",
        highlight: function(e, t) {
            $(e).removeClass(t);
        },
        unhighlight: function(e, t) {
            $(e).removeClass(t);
        },
        errorPlacement: function(e, t) {
            e.insertAfter(t);
        },
        rules: {
            user_id: {
                equalTo: "#i_user_id",
            },
            repeat_password: {
                equalTo: "#password",
            },
            repeat_e_user_password: {
                equalTo: "#e_user_password",
            },
        },
    });