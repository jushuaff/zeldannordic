$(function () {
  var Script = {};
  (function (app) {
      app.init = function() {
        app.bindings();
        app.dtr();
        app.request_ot();
        app.dataTables();
      }

      app.bindings = function() {
        $(document).on("click","#dtr-today.disabled",function(){
          var span = $(this).find(".ongoing-dtr-message");
          span.slideDown();
          setTimeout(function(){
            span.slideUp();
          }, 3000);
        });
      }

      app.dataTables = function(){
        table = $("#overtime-list, #my-dtr-list").DataTable({
            bLengthChange: false, 
            searching: true,
            info: false,
            iDisplayLength: 15,
            order: []
        });

        $("#overtime-list-search, #my-dtr-list-search").keyup(function(){
          table.search($(this).val()).draw();
        });

      }

      app.dtr = function() {
        var modal = $("#dtr-cru-modal");
        $(document).ready(function(){
          modal.on('hidden.bs.modal',function(){
            modal.find(".time-out-row, .shift-row, .others-row").addClass("d-none");
            modal.find("input[type='text'], input[type='time']").val("");
            modal.find("select").prop('selectedIndex',0);
            modal.find("input[type='checkbox']").prop("checked",false);
            modal.find(".error").removeClass("error");
            modal.find(".error-message, .alert").remove();
          });

          $("#dtr-today").click(function(){
            modal.find("form").attr({
              "id"       :"create-dtr-form",
              "user-id"  :$(this).attr("user-id"),
              "per-hour" :$(this).attr("per_hour")
            });
            modal.find(".modal-title").html("<i class='fas fa-calendar'></i> Today");
          });

          $(".edit-dtr").click(function(){
            var dtr_id = $(this).attr("dtr-id");
            modal.find("form").attr({
              "id"      : "edit-dtr-form",
              "dtr-id"  : dtr_id,
              "user-id" : $(this).attr("user-id")
            });

            if($(this).find('i').hasClass("fa-rotate")){ 
              modal.find(".modal-title").html("<i class='fas fa-rotate'></i> Update DTR");
            }else{ 
              modal.find(".modal-title").html("<i class='fas fa-edit'></i> Edit DTR");
            }

            modal.find(".time-out-row").removeClass("d-none");

            $.ajax({
              url: base_url + "employee/Ajax_dtr/read_dtr",
              type: "POST",
              data: {dtr_id:dtr_id},
              dataType: "JSON",
              success: function(response) {
                if(response.status == "success"){
                  modal.find("[name='date']").val(response.date);
                  modal.find("[name='time-in']").val(response.time_in);
                  modal.find("[name='time-out']").val(response.time_out);
                  modal.find("[name='time-out']").val(response.time_out);
                  modal.find("[name='work-base']").val(response.work_base);

                  if(response.shift_reason){
                    modal.find("#cb-moved-shift").prop("checked",true);
                    modal.find(".shift-row").removeClass("d-none");
                    var shift = response.shift_reason.split(" : ");
                    if(shift[0] !== "Others"){
                      modal.find("[name='shift-reason']").val(response.shift_reason);
                    }else{
                      modal.find("[name='shift-reason'] option[value='Others']").prop("selected",true);
                      modal.find("form .others-row").removeClass("d-none");
                      modal.find("[name='others']").val(shift[1]);
                    }
                  }

                }else{
                  modal.find("form").prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
                }
              }
            });
          });

          modal.find("#cb-moved-shift").on("change",function(){
            if($(this).is(":checked")){
              modal.find(".shift-row").removeClass("d-none");
            }else{
              modal.find(".shift-row, .others-row").addClass("d-none");
              modal.find("[name='shift-reason']").prop('selectedIndex',0);
              modal.find("[name='others']").val('');
            }
          });

          modal.find("[name='shift-reason']").on("change",function(){
            var value = $(this).find(":selected").val();
            if(value == "Others"){
              modal.find(".others-row").removeClass("d-none");
            }else{
              modal.find(".others-row").addClass("d-none");
              modal.find("[name='others']").val('');
            }
          });
        });

        modal.on("submit","#create-dtr-form", function(e){
          e.preventDefault();
          var form     = $(this);
          var user_id  = form.attr("user-id");
          var per_hour = form.attr("per-hour");
          
          $.ajax({
            url: base_url + "employee/Ajax_dtr/add_dtr",
            type: "POST",
            data: form.serialize()+"&user_id="+user_id+"&per_hour="+per_hour,
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
                  form.find("[type='checkbox']").prop("checked",false);
                  form.find("select").prop('selectedIndex',0);
                  form.find("input").val("");
                  form.find(".alert").remove();
                  $("#sidebar #dtr-today").addClass("disabled").removeAttr("data-toggle data-target").attr("title","You still have an active dtr");
                  $("#dtr-cru-modal").modal("hide");
                }, 2500);
              }
            },
            complete: function(){
              form.find('input, select').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });

        modal.on("submit","#edit-dtr-form", function(e){
          e.preventDefault();
          var form    = $(this);
          var dtr_id  = form.attr("dtr-id");
          var table   = $("#my-dtr-list [tr-id='"+dtr_id+"']");
          var user_id = form.attr("user-id");
          var shift   = "";
          if(form.find("#cb-moved-shift").is(":checked")){
            shift = form.find("#cb-moved-shift").val();
          }
          $.ajax({
            url: base_url + "employee/Ajax_dtr/update_dtr",
            type: "POST",
            data: form.serialize()+"&shift="+shift+"&dtr_id="+dtr_id+"&user_id="+user_id,
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
                  table.removeClass("active");;
                  table.find(".date").text(response.date);
                  table.find(".time").text(response.time);
                  table.find(".time-in").text(response.time_in);
                  table.find(".time-out").text(response.time_out);
                  table.find(".work-base").text(response.work_base);
                  table.find(".shift-reason").text(response.shift_reason); 
                  table.find(".edit-dtr").html('<i class="fas fa-edit"></i> Edit');
                  $("#sidebar #dtr-today").removeClass("disabled").attr({"data-toggle":"modal","data-target":"#dtr-cru-modal"}).removeAttr("title");
                  $("#dtr-cru-modal").modal("hide");
                }, 2500);
              }
            },
            complete: function(){
              form.find('input, select').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });

      }

      app.request_ot = function(){
        var modal = $("#request-ot-cru-modal");
        var table = $("#overtime-list");

        $(document).on("click","#add-time",function(){
          var num = modal.find(".time-in-out-col .row").length-1;
          var time_in = $('input[name="time-in[]"]').map(function(){ return this.value; }).get();
          var time_out = $('input[name="time-out[]"]').map(function(){ return this.value; }).get();
          modal.find(".error-message").remove();
          modal.find(".error").removeClass("error");

          if(time_in[num] && time_out[num]){
            if(time_in[num].replace(":","")<time_out[num].replace(":","")){
              if((num > 0) && (time_in[num].replace(":","") < time_out[num-1].replace(":",""))){
                modal.find(".time-in-out-col .row:last").prev(".row").find("[name='time-out[]']").addClass("error");
                modal.find(".time-in-out-col .row:last [name='time-in[]']").addClass("error");
                modal.find(".time-in-out-col .row:last").append("<i class='error-message'>Time-in should not be greater than previous time-out</i>");
                setTimeout(function(){
                  modal.find(".error-message").remove();
                  modal.find(".error").removeClass("error");
                }, 5000);
              }else{
                modal.find(".time-in-out-col .last-row").removeClass('last-row');
                modal.find(".time-in-out-col").append('\
                  <div class="row mb-10px last-row">\
                    <i class="fas fa-minus remove-time" title="Remove time"></i>\
                    <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-in[]"></div>\
                    <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-out[]"></div>\
                  </div>\
                ');
              }
            }else{
              modal.find(".time-in-out-col .row:last [name='time-out[]']").addClass("error");
              modal.find(".time-in-out-col .row:last").append("<i class='error-message'>Time-out should not be less than time-in</i>");
              setTimeout(function(){
                modal.find(".error-message").remove();
                modal.find(".error").removeClass("error");
              }, 5000);
            }
          }else{
            modal.find(".time-in-out-col .row:last [type='time']").addClass("error");
            modal.find(".time-in-out-col .row:last").append("<i class='error-message'>Kindly fill up the time before adding</i>");
            setTimeout(function(){
              modal.find(".error-message").remove();
              modal.find(".error").removeClass("error");
            }, 5000);
          }
        });

        modal.on("click",".remove-time",function(){
          $(this).parent().prev(".row").addClass("last-row");
          $(this).parent().remove();
        });

        modal.on('hidden.bs.modal',function(){
          modal.find("form").removeAttr("otr-id user-id");
          modal.find("input, textarea").val("");
          modal.find(".error").removeClass("error");
          modal.find(".error-message, .alert").remove();
        });

        $(document).on("click","#request-ot",function(){
          modal.find(".modal-title").html("<i class='request-ot-cru-icon'></i> Request Overtime");
          modal.find("form").attr({"id":"send-ot-request-form","user-id":$(this).attr('user-id')});
        });

        $(document).on("click",".edit-ot-request",function(){
          modal.find(".modal-title").html("<i class='fas fa-edit'></i> Overtime Request");
          modal.find("form").attr({"id":"edit-ot-request-form","otr-id":$(this).attr('otr-id')});
          var rot_id = $(this).attr("otr-id");

          $.ajax({
            url: base_url + "admin/Ajax_ot_request/fetch_ot_request",
            type: "POST",
            data: {rot_id:rot_id},
            dataType: "JSON",
            success: function(response) {
              modal.find("[name='date']").val(response.date);
              modal.find("[name='task']").val(response.task);

              var times = response.time.split(" ");
              if(times.length-1 > 1){
                modal.find(".time-in-out-col .row").remove();
                var html = "";
                for(var a=0;a<times.length;a++){
                  if(times[a] !== ""){
                    var time = times[a].split("-");
                    html += '\
                      <div class="row mb-10px">\
                        <i class="fas fa-minus remove-time" title="Remove time"></i>\
                        <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-in[]" value="'+time[0]+'"></div>\
                        <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-out[]" value="'+time[1]+'"></div>\
                      </div>\
                    ';
                  }
                }
                modal.find(".time-in-out-col").append(html);
                modal.find(".time-in-out-col .row:first .remove-time").remove();
                modal.find(".time-in-out-col .row:last").addClass("last-row");
              }else{
                var time = times[0].split("-");
                modal.find("[name='time-in[]']").val(time[0]);
                modal.find("[name='time-out[]']").val(time[1]);
              }
            }
          });
        });

        $(document).on("click",".delete-request",function(){
          $("#delete-otr-modal form").attr("otr-id",$(this).attr("otr-id"))
        });

        modal.on("submit","#send-ot-request-form",function(e){
          e.preventDefault();
          var form = $(this);
          modal.find(".error-message").remove();
          modal.find(".error").removeClass("error");
          var user_id  = form.attr("user-id");
          var time_in  = modal.find('input[name="time-in[]"]').map(function(){ return this.value; }).get();
          var time_out = modal.find('input[name="time-out[]"]').map(function(){ return this.value; }).get();
          var time     = modal.find('input[type="time"]').map(function(){ return this.value; }).get();
          var num      = modal.find(".time-in-out-col .row").length;

          var error = 0;

          if((num-1 > 0) && (time_in[num-1].replace(":","") < time_out[num-2].replace(":",""))){
            modal.find(".time-in-out-col .row:last").prev(".row").find("[name='time-out[]']").addClass("error");
            modal.find(".time-in-out-col .row:last [name='time-in[]']").addClass("error");
            modal.find(".time-in-out-col .row:last").append("<i class='error-message'>Time-in should not be greater than previous time-out</i>");
            error++;
          }

          var empty_time = 0;
          for(var a=0;a<time.length;a++){ if(!time[a]){empty_time++;} }
          if(empty_time > 0){
            modal.find(".time-in-out-col .row:last").append("<i class='error-message'>required</i>");
            modal.find(".time-in-out-col .row:last [type='time']").addClass("error");
            error++;
          }

          if(error == 0){
            var time = "";
            for(var a=0;a<num;a++){
              time += time_in[a]+"-"+time_out[a]+" ";
            }
            $.ajax({
              url: base_url + "employee/Ajax_ot_request/ot_au_request",
              type: "POST",
              data: form.serialize()+"&user_id="+user_id+"&time="+time,
              dataType: "JSON",
              beforeSend: function(){
                $(".error").removeClass("error");
                $(".error-message, .alert").remove();
                form.find("textarea,input").attr("readonly",true);
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
                    form.find("textarea, input").val("");
                    form.find(".error-message, .alert").remove();
                    modal.modal("hide");
                  }, 1500);
                }
              },
              complete: function(){
                form.find("textarea,input").removeAttr("readonly");
                form.find('button').removeAttr('disabled');
              }
            });
          }
        });

        modal.on("submit","#edit-ot-request-form",function(e){
          e.preventDefault();
          modal.find(".error-message").remove();
          modal.find(".error").removeClass("error");
          var form     = $(this);
          var otr_id   = form.attr("otr-id");
          var table    = $("[tr-id='"+otr_id+"']");
          var time_in  = modal.find('input[name="time-in[]"]').map(function(){ return this.value; }).get();
          var time_out = modal.find('input[name="time-out[]"]').map(function(){ return this.value; }).get();
          var time     = modal.find('input[type="time"]').map(function(){ return this.value; }).get();
          var num      = time_in.length;//count number of rows

          var empty_time = 0;
          for(var a=0;a<time.length;a++){ if(!time[a]){empty_time++;} }

          if(empty_time > 0){
            modal.find(".time-in-out-col .row:last").append("<i class='error-message'>required</i>");
            modal.find(".time-in-out-col .row:last [type='time']").addClass("error");
          }else{
            var time = "";
            for(var a=0;a<num;a++){
              time += time_in[a]+"-"+time_out[a]+" ";
            }
            $.ajax({
              url: base_url + "employee/Ajax_ot_request/ot_au_request",
              type: "POST",
              data: form.serialize()+"&otr_id="+otr_id+"&time="+time,
              dataType: "JSON",
              beforeSend: function(){
                $(".error").removeClass("error");
                $(".error-message, .alert").remove();
                form.find("textarea,input").attr("readonly",true);
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
                    form.find("textarea, input").val("");
                    form.find(".error-message, .alert").remove();
                    table.find(".task").html(response.task);
                    table.find(".date").text(response.date);
                    table.find(".time").html(response.time);
                    modal.modal("hide");
                  }, 1500);
                }
              },
              complete: function(){
                form.find("textarea,input").removeAttr("readonly");
                form.find('button').removeAttr('disabled');
              }
            });
          }
        });

        $(document).on("submit","#delete-otr-form",function(e){
          e.preventDefault();
          var otr_id = $(this).attr("otr-id");
          var modal = $("#delete-otr-modal");
          var form = $(this);
          $.ajax({
            url: base_url + "employee/Ajax_ot_request/delete_request",
            type: "POST",
            data: {otr_id:otr_id},
            dataType: "JSON",
            beforeSend: function(){
              modal.find(".alert").remove();
              modal.find("button").attr("disabled",true);
            },
            success: function(response){
              if(response.status == "error"){
                $('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>').insertAfter("#delete-otr-modal .modal-header");
              }else{
                $('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>').insertAfter("#delete-otr-modal .modal-header");
                setTimeout(function(){
                  modal.find(".alert").remove();
                  modal.find("button").removeAttr("disabled");
                  table.find("[tr-id='"+otr_id+"']").remove();
                  modal.modal("hide");
                }, 2500);
              }
            }
          });
        });
      }

      app.init();
  })(Script);
});