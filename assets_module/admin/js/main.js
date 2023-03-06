$(function () {
  var Script = {};
  (function (app) {
      app.init = function() {
        app.bindings();
        app.dataTables();
        app.employee_cru_modal();
        app.archive_user();
        app.salary_grade_cru_modal();
        app.ot_request();
        app.dtr();
        app.download_dtr();
        app.holiday();
      }

      app.bindings = function() {
      }

      app.dataTables = function(){
        table = $("\
          #employee-list, \
          #salary-grade-list, \
          #aotr-list, \
          #adtr-list, \
          #odtr-table-list-view,\
          #odtr-group-list-view,\
          #dtr-list,\
          #holiday-list,\
          #custom-holiday-list\
        ").DataTable({
            bLengthChange: false, 
            searching: true,
            info: false,
            iDisplayLength: 15,
            order: []
        });

        $("#employee-list-search, \
          #salary-grade-list-search, \
          #aotr-list-search, \
          #adtr-list-search,\
          #odtr-list-search,\
          #dtr-list-search,\
          #holiday-list-search,\
          #custom-holiday-list-search\
        ").keyup(function(){
          table.search($(this).val()).draw();
        });

      }

      app.employee_cru_modal = function(){
        var modal = $("#employee-cru-modal");
        $(document).on("click", "#add-employee", function(){
          modal.find(".modal-title").empty().append("<i class='fas fa-plus'></i> Employee");
          modal.find("form").attr("id","employee-add-form");
          modal.find("form").removeAttr("user-id");
          modal.find(".role-row, .password-row").removeClass("d-none");
          modal.find(".modal-footer button").text("Submit");
        });

        $(document).on("change","#employee-add-form [name='role']",function(){
          var value = $(this).find(":selected").val();
          var form  = $("#employee-add-form")
          if(value !== "" && value !== "1"){//1 = admin
            form.find(".schedule-row, .salary-grade-row").removeClass("d-none");
          }else{
            form.find(".schedule-row, .fixed-schedule-row, .salary-grade-row").addClass("d-none");
          }
        });

        $(document).on("change","[name='schedule']",function(){
          var value = $(this).find(":selected").val();
          if(value == "fixed"){
            modal.find(".fixed-schedule-row").removeClass("d-none");
          }else{
            modal.find(".fixed-schedule-row").addClass("d-none");
          }
        });

        $(document).on("submit", "#employee-add-form", function(e){
          e.preventDefault();
          var form = $(this);
          $.ajax({
            url: base_url + "admin/Ajax_users/add_user",
            type: "POST",
            data: form.serialize(),
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
                  $("#employee-cru-modal").modal("hide");
                }, 1500);
              }
            },
            complete: function(response){
              form.find('input, select').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.archive_user = function(){
        var form = $("#archive-user-form");

        $(document).on("click",".archive-user, .activate-user",function(){
          var user_id = $(this).attr("user-id");
          var archive_value = $(this).attr("archive-value");
          form.attr("user-id",user_id);
          form.attr("archive",archive_value);
          $.ajax({
            url: base_url + "admin/Ajax_users/fetch_user",
            type: "POST",
            data: {user_id:user_id},
            dataType: "JSON",
            beforeSend: function(){
              form.find(".alert").remove();
              form.find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "success"){
                $("#archive-user-modal .question-row h4").html('Are you sure you want to reactivate<br><b class="t-maroon">'+response.name+'</b>');
              }else{
                alert(response.message);
              }
            },
            complete: function(response){
              form.find('button').removeAttr('disabled');
            }
          });
        });

        $("#archive-user-form").on("submit", function(e){
          e.preventDefault();
          var user_id = $(this).attr("user-id");
          var archive = $(this).attr("archive");
          $.ajax({
            url: base_url + "admin/Ajax_users/archive_user",
            type: "POST",
            data: {user_id:user_id,archive:archive},
            dataType: "JSON",
            beforeSend: function(){
              form.find(".alert").remove();
              form.find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "error"){
                $("#archive-user-modal .modal-body").prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                $("#archive-user-modal .modal-body").prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  form.find(".alert").remove();
                  $("#archive-user-modal").modal("hide");
                  $("#employee-list").find("tbody tr[tr-id='"+user_id+"']").remove();
                }, 1500);
              }
            },
            complete: function(response){
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.salary_grade_cru_modal = function(){
        var table = $("#salary-grade-list");
        var modal = $("#salary-grade-cru-modal");

        $(document).ready(function(){
          modal.on('hidden.bs.modal',function(){
            modal.find("form").removeAttr("sg-id")
            modal.find("input").val("").removeClass("error");
            modal.find(".error-message").remove();
          });

          $(".edit-salary-grade").click(function(){
            var sg_id = $(this).attr("sg-id");
            modal.find(".modal-title i").removeClass("fa-add").addClass("fa-edit");
            modal.find("form").attr({"id":"update-salary-grade-form", "sg-id":sg_id});

            $.ajax({
              url: base_url + "admin/Ajax_salary_grade/fetch_salary_grade",
              type: "POST",
              data: {sg_id:sg_id},
              dataType: "JSON",
              success: function(response){
                modal.find("[name='salary-grade']").val(response.grade_number);
                modal.find("[name='hourly-rate']").val(response.hourly_rate);
              }
            });
          });

          $("#add-salary-grade").click(function(){
            modal.find(".modal-title i").removeClass("fa-edit").addClass("fa-add");
            modal.find("form").attr("id","add-salary-grade-form");
          });

          $("#salary-grade-list .view").click(function(){
            var sg_id = $(this).attr("sg-id");
            $.ajax({
              url: base_url + "admin/Ajax_salary_grade/fetch_salary_grade_employees",
              type: "POST",
              data: {sg_id:sg_id},
              success: function(response){
                $("#salary-grade-employee-modal .data-row").html(response);
              }
            });
          });
        });

        $(document).on("submit", "#add-salary-grade-form", function(e){
          e.preventDefault();
          var form = $(this);
          $.ajax({
            url: base_url + "admin/Ajax_salary_grade/au_salary_grade",
            type: "POST",
            data: form.serialize(),
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
                  form.find(".error").removeClass("error");
                  form.find(".error-message, .alert").remove();
                  modal.modal("hide");
                }, 1500);
              }
            },
            complete: function(response){
              form.find('input').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });

        $(document).on("submit", "#update-salary-grade-form", function(e){
          e.preventDefault();
          var form = $(this);
          var sg_id = form.attr("sg-id");

          $.ajax({
            url: base_url + "admin/Ajax_salary_grade/au_salary_grade",
            type: "POST",
            data: form.serialize()+"&sg_id="+sg_id,
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
                  form.find(".error").removeClass("error");
                  form.find(".error-message, .alert").remove();
                  modal.modal("hide");
                  table.find("tr[tr-id='"+sg_id+"'] .grade-number").text(response.grade_number);
                  table.find("tr[tr-id='"+sg_id+"'] .hourly-rate").text("PHP "+response.hourly_rate);
                }, 1500);
              }
            },
            complete: function(response){
              form.find('input').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
          });
      }

      app.ot_request = function(){
        var table = $("#aotr-list");
        var modal = $("#ot-status-modal");
        var form = $("#ot-status-form");

        modal.on('hidden.bs.modal',function(){
          form.find(".statement-row").addClass("d-none");
          form.find("select").prop('selectedIndex',0);
          form.find("textarea").val("");
        });

        $(document).on("click",".update-ot-status",function(){
          var rot_id = $(this).attr("rot-id");
          form.attr("rot_id",rot_id);
          $.ajax({
            url: base_url + "admin/Ajax_ot_request/fetch_ot_request",
            type: "POST",
            data: {rot_id:rot_id},
            dataType: "JSON",
            success: function(response) {
              if(response.response_status == "success"){
                form.find("[name='status'] option[value='"+response.status+"']").prop('selected',true);
                if(response.status == "denied"){
                  form.find(".statement-row").removeClass("d-none");
                  form.find("[name='reason-denied']").val(response.reason_denied);
                }
              }
            }
          });
        });

        form.find("[name='status']").on("change",function(){
          var val = $(this).val();
          if(val == "denied"){
            form.find(".statement-row").removeClass("d-none");
          }else{
            form.find(".statement-row").addClass("d-none").find("[name='reason-denied']").val("");
          }
        });

        form.on("submit",function(e){
          e.preventDefault();
          var rot_id = form.attr("rot_id");
          var status = form.find("[name='status']").val();
          if(form.find("[name='status']").val() == "denied" && form.find("[name='reason-denied']").val() == ""){
            form.find("[name='reason-denied']").addClass("error").parent().append('<i class="error-message">required</i>')
          }else{
            $.ajax({
              url: base_url + "admin/Ajax_ot_request/update_ot_request",
              type: 'POST',
              data: form.serialize()+"&rot_id="+rot_id,
              dataType: "JSON",
              beforeSend: function(){
                $(".error").removeClass("error");
                $(".error-message, .alert").remove();
                form.find('select, textarea').attr('readonly',true);
                form.find('button').attr('disabled',true);
              },
              success: function(response){
                if(response.status == "success"){
                  form.prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                  setTimeout(function(){
                    form.find(".error").removeClass("error");
                    form.find(".error-message, .alert").remove();
                    var tr_status;
                    if(status == "pending") {
                      tr_status = "<span class='t-green'>Pending</span>";
                    }else if(status == "denied"){
                      tr_status = "<span class='t-red'>Denied</span>"
                    }else{
                      tr_status = "<span class='t-blue'>Approved</span>"
                    }
                    table.find("[tr-id='"+rot_id+"'] .status").html(tr_status);
                    modal.modal("hide");
                  }, 1500);
                }else{
                  form.prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
                }
              },
              complete: function(){
                form.find('select, textarea').removeAttr('readonly');
                form.find('button').removeAttr('disabled');
              }
            });
          }
        });
      }

      app.dtr = function(){
        $("#odtr-group-list-view_paginate").addClass("d-none");

        $(document).on("click","#odtr-view-by",function(){
          $(this).attr('title', function(index, attr){
            return attr == "Group view" ? "List view" : "Group view";
          });
          $(this).toggleClass("view-group view-list").find("i").toggleClass("fa-users fa-list");
        });

        $(document).on("click",".view-group",function(){
          $("#odtr-group-list-view").removeClass("d-none");
          $("#odtr-table-list-view").addClass("d-none");
          $("#odtr-group-list-view_paginate").removeClass("d-none");
          $("#odtr-table-list-view_paginate").addClass("d-none");
        });

        $(document).on("click",".view-list",function(){
          $("#odtr-table-list-view").removeClass("d-none");
          $("#odtr-group-list-view").addClass("d-none");
          $("#odtr-table-list-view_paginate").removeClass("d-none");
          $("#odtr-group-list-view_paginate").addClass("d-none");
        });

        var modal = $("#outstanding-dtr-modal");
        var form  = $("#outstanding-dtr-form");
        $(document).on("click",".view-odtr",function(){
          var user_id = $(this).attr("user-id");
          var date    = $(this).attr("date");

          $.ajax({
            url: base_url + "admin/Ajax_dtr/read_odtr_list",
            type: "POST",
            data: {user_id:user_id,date:date},
            success: function(data){
              form.find(".table-col").html(data);
            }
          });
        });

        $(document).on("click",".dtr-details",function(){
          var modal  = $("#view-dtr-details-modal");
          var dtr_id = $(this).attr("dtr-id");
          $.ajax({
            url: base_url + "admin/Ajax_dtr/dtr_details",
            type: "POST",
            data: {dtr_id:dtr_id},
            success: function(response){
              modal.find(".modal-body").html(response);
            }
          });
        });

        $(document).on("click",".otr-details",function(){
          var modal  = $("#view-otr-details-modal");
          var otr_id = $(this).attr("otr-id");
          $.ajax({
            url: base_url + "admin/Ajax_ot_request/otr_details",
            type: "POST",
            data: {otr_id:otr_id},
            success: function(response){
              modal.find(".modal-body").html(response);
            }
          });
        });
      }

      app.download_dtr = function(){
        var modal = $("#download-dtr-list-filter-modal");
        var form  = $("#download-dtr-list-filter-form");

        $(document).on("submit","#download-dtr-list-filter-form",function(e){
          e.preventDefault();
          $.ajax({
            url: base_url + "admin/Ajax_dtr/download_dtr_validation",
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            beforeSend: function(){
              modal.find(".error").removeClass("error");
              modal.find(".alert, .error-message").remove();
              modal.find("input").attr("readonly",true);
              modal.find("button").attr("disabled",true);
            },
            success: function(response){
              if(response.status == "form-incomplete"){
                $.each(response.errors,function(e,val){
                  form.find('[name="'+e+'"]').addClass('error');
                  form.find('[name="'+e+'"]').parent().append('<i class="error-message">'+val+'</i>');                               
                });
              }else {
                form.prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                //window.location.href=base_url+"admin/Ajax_dtr/download_dtr";
                window.open(base_url+"admin/Ajax_dtr/download_dtr");
                setTimeout(function(){
                  modal.find(".error").removeClass("error");
                  modal.find(".alert, .error-message").remove();
                  form.find("input").val("");
                  modal.modal("hide");
                }, 1500);
              }
            },
            complete: function(response){
              form.find('input').removeAttr('readonly');
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.holiday = function(){
        var modal = $("#holiday-cru-modal");
        var form = $("#holiday-cru-form");

        modal.on('hidden.bs.modal',function(){
          modal.find(".modal-title").html("<i class='fas fa-plus'></i> Custom Holiday");
          form.removeAttr("h-id");
          form.find("select").prop('selectedIndex',0);
          form.find("input").val("");
        });

        $(document).on("click",".update-holiday",function(){
          var h_id = $(this).attr("h-id");
          modal.find(".modal-title").html("<i class='fas fa-edit'></i> Custom Holiday");
          form.attr("h-id",h_id);
          $.ajax({
            url: base_url + "admin/Ajax_holiday/fetch_holiday",
            type: "POST",
            data: {h_id:h_id},
            dataType: "JSON",
            success:function(response){
              form.find("[name='name']").val(response.name);
              form.find("[name='date']").val(response.date);
              form.find("[name='type'] option[value='"+response.type+"']").prop('selected', true);
            }
          });
        });

        form.on("submit", function(e){
          e.preventDefault();
          if(form.attr("h-id")){
            var h_id = form.attr("h-id");
            var table = $("#custom-holiday-list");
            $.ajax({
              url: base_url + "admin/Ajax_holiday/update_custom_holiday",
              type: "POST",
              data: form.serialize()+"&h_id="+h_id,
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
                    form.find("select").prop('selectedIndex',0);
                    form.find("input").val("");
                    form.find(".error").removeClass("error");
                    form.find(".error-message, .alert").remove();
                    table.find("[tr-id='"+h_id+"'] .name").text(response.name);
                    table.find("[tr-id='"+h_id+"'] .date").text(response.date);
                    table.find("[tr-id='"+h_id+"'] .type").text(response.type);
                    modal.modal("hide");
                    
                  }, 1500);
                }
              },
              complete: function(response){
                form.find('input, select').removeAttr('readonly');
                form.find('button').removeAttr('disabled');
              }
            });
          }else{
            $.ajax({
              url: base_url + "admin/Ajax_holiday/add_custom_holiday",
              type: "POST",
              data: form.serialize(),
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
                    form.find("select").prop('selectedIndex',0);
                    form.find("input").val("");
                    form.find(".error").removeClass("error");
                    form.find(".error-message, .alert").remove();
                    modal.modal("hide");
                    /*add yung append newly added sa table*/
                  }, 1500);
                }
              },
              complete: function(response){
                form.find('input, select').removeAttr('readonly');
                form.find('button').removeAttr('disabled');
              }
            });
          }
        });

        $(document).on("click",".delete-holiday",function(){
          var h_id = $(this).attr("h-id");
          var modal = $("#delete-holiday-modal");
          modal.find("form").attr("h-id",h_id);
          $.ajax({
            url: base_url + "admin/Ajax_holiday/fetch_holiday",
            type: "POST",
            data: {h_id:h_id},
            dataType: "JSON",
            success:function(response){
              modal.find(".question-row b").text(response.name);
            }
          });
        });

        $(document).on("submit","#delete-holiday-form",function(e){
          e.preventDefault();
          var h_id = $(this).attr("h-id");
          $.ajax({
            url: base_url + "admin/Ajax_holiday/delete_custom_holiday",
            type: "POST",
            data: {h_id:h_id},
            dataType: "JSON",
            beforeSend: function(){
              form.find(".alert").remove();
              form.find('button').attr('disabled',true);
            },
            success: function(response){
              if(response.status == "error"){
                $("#delete-holiday-modal .modal-body").prepend('<div class="alert alert-danger text-center" role="alert">'+response.message+'</div>');
              }else {
                $("#delete-holiday-modal .modal-body").prepend('<div class="alert alert-success text-center" role="alert">'+response.message+'</div>');
                setTimeout(function(){
                  form.find(".alert").remove();
                  $("#delete-holiday-modal").modal("hide");
                  $("#custom-holiday-list").find("tbody tr[tr-id='"+h_id+"']").remove();
                }, 1500);
              }
            },
            complete: function(response){
              form.find('button').removeAttr('disabled');
            }
          });
        });
      }

      app.init();
  })(Script);
});