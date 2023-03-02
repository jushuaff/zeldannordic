//
// Module Name: Login | Forgot | Reset
// Author: Jushua FF
// Date: 09.11.2022
//

$(function () {
  var Script = {};
  (function (app) {
      var wWidth = $( window ).width();
      app.init = function() {
        app.passwordToggle();
        app.login();
        app.forgot_password();
        app.reset_password();
      }

      app.passwordToggle = function() {
        $(".icon-right").click(function(){
          var attr= $(this).prev().attr("type");
          $(this).toggleClass("fa-eye fa-eye-slash");
          $(this).prev().attr("type", (attr === "password")?("text"):("password"));
        });
      }

      app.login = function() {
        $("#login-form").submit(function(e){
          e.preventDefault();
          $.ajax({
            url: base_url + "login/Ajax_login",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            beforeSend: function(){
              $(".error").removeClass("error");
              $(".error-message, .alert").remove();
              $(this).find('input').attr('readonly',true);
              $(this).find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "form-incomplete"){
                $.each(response.errors,function(e,val){
                  $('input[name="'+e+'"]').parent().addClass('error');
                  $('input[name="'+e+'"]').parent().parent().find("label").append('<i class="error-message">'+val+'</i>');                               
                });
              }else if(response.status == "error"){
                $(".input-con").find("div").addClass('error');
                $('#login-form').prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                $('#login-form').prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                window.location.href = response.redirect;
              }
            },
            complete: function(response){
                $('#login-form').find('input').removeAttr('readonly');
                $('#login-form').find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.forgot_password = function(){
        $("#fp-form").submit(function(e){
          e.preventDefault();
          $.ajax({
            url: base_url + "forgot_password/Ajax_forgot_password",
            type: "POST",
            data: $(this).serialize(),
            dataType: "JSON",
            beforeSend: function(){
              $(".error").removeClass("error");
              $(".error-message, .alert").remove();
              $(this).find('input').attr('readonly',true);
              $(this).find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "form-incomplete"){
                $.each(response.errors,function(e,val){
                  $('input[name="'+e+'"]').parent().addClass('error');
                  $('input[name="'+e+'"]').parent().parent().find("label").append('<i class="error-message">'+val+'</i>');                               
                });
              }else if(response.status == "error"){
                $(".input-con").find("div").addClass('error');
                $('#fp-form').prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                $(this).find('input').val("");
                $('#fp-form').prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  window.location.href = base_url;
                }, 6000);
              }
            },
            complete: function(response){
                $('#fp-form').find('input').removeAttr('readonly');
                $('#fp-form').find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.reset_password = function(){
        $(document).on("submit","#rp-form",function(e){
          e.preventDefault();
          var user_id = $(this).attr("user-id");
          var form = $(this);
          $.ajax({
            url: base_url + "reset_password/Ajax_reset_password",
            type: "POST",
            data: $(this).serialize()+"&user_id="+user_id,
            dataType: "JSON",
            beforeSend: function(){
              $(".error").removeClass("error");
              $(".error-message, .alert").remove();
              form.find('input').attr('readonly',true);
              form.find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "form-incomplete"){
                $.each(response.errors,function(e,val){
                  $('input[name="'+e+'"]').parent().addClass('error');
                  $('input[name="'+e+'"]').parent().parent().find("label").append('<i class="error-message">'+val+'</i>');                               
                });
              }else if(response.status == "error"){
                $(".input-con").find("div").addClass('error');
                form.prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                form.find('input').val("");
                form.prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  window.location.href = base_url;
                }, 6000);
              }
            },
            complete: function(response){
              $(".error").removeClass("error");
              $(".error-message, .alert").remove();
              form.find('input').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }
      app.init();
  })(Script);
});