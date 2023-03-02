$(function () {
  var Script = {};
  (function (app) {
      var ww = window.innerWidth;

      app.init = function() {
        app.bindings();
        app.employee_cru_modal();
        app.update_password();
      }

      app.bindings = function() {
        /*This is when the page is reloaded*/
        if(ww <= 767){
          if($("#sidebar, #main").hasClass("toggled")){
            $("#sidebar, #main").removeClass("toggled");
            $(".sidebar-toggle").find(".fas").toggleClass("fa-angle-left fa-angle-right");
          }
        }else{
          if(!$("#sidebar, #main").hasClass("toggled")){
            $("#sidebar, #main").addClass("toggled");
            $(".sidebar-toggle").find(".fas").toggleClass("fa-angle-right fa-angle-left");
          }
        }

        /*alert($("#main-div").css("width"));*/

        /*This is when your adjusting the screen*/
        window.addEventListener("resize", function(){
          if(ww <= 767){
            if($("#sidebar, #main").hasClass("toggled")){
              $("#sidebar, #main").removeClass("toggled");
              $(".sidebar-toggle").find(".fas").toggleClass("fa-angle-left fa-angle-right");
            }
          }else{
            if(!$("#sidebar, #main").hasClass("toggled")){
              $("#sidebar, #main").addClass("toggled");
              $(".sidebar-toggle").find(".fas").toggleClass("fa-angle-right fa-angle-left");
            }
          }
        });

        $(document).on("click",".sidebar-toggle",function(){
          var side_bar = $(".sidebar-toggle");
          $("#sidebar, #main").toggleClass("toggled");
          side_bar.find(".fas").toggleClass("fa-angle-left fa-angle-right");
          if(side_bar.attr("title") == "Hide sidebar"){
            side_bar.attr("title","Display sidebar");
          }else{
            side_bar.attr("title","Hide sidebar");
          }
        });

        $("#sidebar button.drop-button").on("click", function(){
          var target = $(this).attr("target");
          $(this).find("i.right").toggleClass("fa-angle-down fa-angle-up");
          $("#sidebar "+target).slideToggle();
        });

        $(document).on("click", "[data-toggle='modal']", function(){
          var modal = $(this).attr("data-target");
          $(modal).modal("show");
        });


      }

      app.employee_cru_modal = function(){
        var modal = $("#employee-cru-modal");

        $("#employee-cru-modal").on('hidden.bs.modal',function(){
          var filename = modal.find("[name='filename']").val();
          if(filename){
            var split = filename.split("-");
            if(split[0] == "Temporary"){
              $.ajax({
                url: base_url + "admin/Ajax_users/delete_temp_image",
                type: "POST",
                data: {filename:filename},
                success: function(response){
                  //do nothing
                }
              });
            }
          }
          modal.find("form").removeAttr("user-type own-profile user-id");
          modal.find(".schedule-row, .fixed-schedule-row, .salary-grade-row").addClass("d-none");
          modal.find("img").attr("src","");
          modal.find("select").prop('selectedIndex',0);
          modal.find("input").val("");
          modal.find(".error").removeClass("error");
          modal.find(".error-message, .alert").remove();
          modal.find("[name='filename']").removeAttr("old-profile-name");
        });

        $(document).on("click", ".edit-profile", function(){
          var user_id = $(this).attr("user-id");
          modal.find(".modal-title").empty().append("<i class='fas fa-user'></i> Profile");
          modal.find("form").attr("id","employee-update-form");
          modal.find("form").attr("user-id",user_id);
          modal.find(".role-row, .password-row").addClass("d-none");
          modal.find(".modal-footer button").text("Update");
          modal.find("form").attr("user-type",$(this).attr("user-type"));
          var own_profile = false;

          if($(this).hasClass("own-profile")){ 
            modal.find("form").attr("own-profile",true);
            own_profile = true;
          }else{
            modal.find("form").removeAttr("own-profile"); 
          }

          $.ajax({
            url: base_url + "admin/Ajax_users/fetch_user",
            type: "POST",
            data: {user_id:user_id},
            dataType: "JSON",
            success: function(response){
              if(response.status == "success"){
                modal.find("[name='name']").val(response.name);
                modal.find("[name='email']").val(response.email);
                modal.find("[name='mobile-number']").val(response.mobile_number);
                modal.find("[name='date-of-birth']").val(response.date_of_birth);
                modal.find("[name='gender'] option[value='"+response.gender+"']").prop('selected', true);
                modal.find("[name='username']").val(response.username);
                if(response.profile_pic){
                  modal.find("img").attr("src",base_url+"assets_module/user_profile/"+response.profile_pic);
                  modal.find("[name='filename']").val(response.profile_pic).attr("old-profile-name",response.profile_pic);
                }
                if(own_profile == false && response.user_type !== "1"){
                  modal.find(".schedule-row, .salary-grade-row").removeClass('d-none');
                  modal.find("[name='salary-grade'] option[value='"+response.salary_grade+"']").prop('selected',true);

                  const schedule = response.schedule.split("-");
                  modal.find("[name='schedule'] option[value='"+schedule[0]+"']").prop('selected',true);

                  if(schedule[0] == "fixed"){
                    modal.find(".fixed-schedule-row").removeClass("d-none");
                    modal.find("[name='time-in']").val(schedule[1]);
                    modal.find("[name='time-out']").val(schedule[2]);
                  }
                }
              }else{
                alert(response.message);
              }
            }
          });
        });

        $(".modal .preview-con").on("click", function(){
          $('#upload-profile').trigger('click');
        });

        $('#upload-profile').change(function (e){
          e.preventDefault();
          let validExt = ['jpg', 'jpeg', 'png'];
          var file_type = this.files[0].type.split('/')[1];
          var file_size = this.files[0].size;
          var error_message = "";
          if(validExt.indexOf(file_type) == -1){
            error_message = "Uploaded file must be an image";
          }else if(file_size > 15000000){
            error_message = "Image size must not exceed 15mb";
          }

          if(error_message !== ""){
            $(".preview-con").addClass("error");
            $(".preview-con").parent().prepend("<span class='t-red'>"+error_message+"</span>")
          }else{
            var fd = new FormData();
            var files = $(this)[0].files;

            if(files.length > 0){
              fd.append('file',files[0]);
              $.ajax({
                url: base_url + 'admin/Ajax_users/profile_image',
                type: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function(){
                  $(".error").removeClass("error");
                  $(".preview-con").parent().remove("span");
                },
                success: function(response){
                  var split = response.split("/").pop();
                  var src = base_url + "assets_module/user_profile/"+split;
                  if(response !== 0){
                    $(".preview-con").find("img").attr("src",src);
                    $("[name='filename']").val(split);
                  }else{
                    alert('file not uploaded');
                  }
                },
              });
            }
          }
        });

        $(document).on("submit", "#employee-update-form", function(e){
          e.preventDefault();
          var form = $(this);
          var user_id = form.attr("user-id");
          var file = form.find("[name='filename']");
          var old_profile_name = "";
          var user_type = $(this).attr("user-type");
          if( file.attr('old-profile-name')){ old_profile_name = file.attr('old-profile-name'); }

          $.ajax({
            url: base_url + "admin/Ajax_users/update_user",
            type: "POST",
            data: form.serialize()+"&user_id="+user_id+"&old_profile_name="+old_profile_name+"&user_type="+user_type,
            dataType: "JSON",
            beforeSend: function(){
              $(".error").removeClass("error");
              $(".error-message, .alert").remove();
              form.find('input, select').attr('readonly',true);
              form.find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "form-incomplete"){
                $.each(response.errors,function(e,val){
                  form.find('[name="'+e+'"]').addClass('error');
                  form.find('[name="'+e+'"]').parent().append('<i class="error-message">'+val+'</i>');                               
                });
              }else if(response.status == "error"){
                form.prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                form.prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  form.find("img").attr("src","");
                  form.find("select").prop('selectedIndex',0);
                  form.find("input").val("");
                  form.find(".error").removeClass("error");
                  form.find(".error-message, .alert").remove();
                  form.find("[name='filename']").removeAttr("old-profile-name");
                  form.removeAttr("user-id");
                  $("#employee-cru-modal").modal("hide");

                  if(form.attr("own-profile")){
                    $("#sidebar .profile-con img").attr("src",base_url+"/assets_module/user_profile/"+response.profile_pic);
                    $("#sidebar .name").attr("title",response.name).text(response.name);
                  }else{
                    var table = $("#employee-list [tr-id='"+user_id+"']");
                    if(response.profile_pic !== ""){
                      table.find(".profile_pic").attr("src",base_url+"/assets_module/user_profile/"+response.profile_pic);
                    }
                    table.find(".name").text(response.name);
                    table.find(".username").text(response.username);
                    table.find(".email").text(response.email);
                    table.find(".mobile_number").text(response.mobile_number);
                  }
                }, 3000);
              }
            },
            complete: function(){
              form.find('input, select').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.update_password = function(){
        $(document).on("click",".update-password",function(){
          var modal = $("#update-password-modal");
          var user_id = $(this).attr('user-id');
          modal.find("form").attr('user-id',user_id);

          if($(this).hasClass("own-password")){
            modal.find(".current-row, .confirm-row").removeClass("d-none");
          }else{
            modal.find(".current-row, .confirm-row").addClass("d-none");
          }
        });

        $(document).on("submit","#update-password-form",function(e){
          e.preventDefault();
          var form = $(this);
          var user_id = $(this).attr("user-id");
          $.ajax({
            url:base_url + "admin/Ajax_users/update_password",
            type: "POST",
            data: form.serialize()+"&user_id="+user_id,
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
                  form.find('[name="'+e+'"]').addClass('error');
                  form.find('[name="'+e+'"]').parent().append('<i class="error-message">'+val+'</i>');                               
                });
              }else if(response.status == "error"){
                form.prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                form.prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  form.find("input").val("");
                  $("#update-password-modal").modal("hide");
                }, 1500);
              }
            },
            complete: function(){
              form.find('input, select').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.init();
  })(Script);
});