<div class="modal fade" id="employee-cru-modal" tabindex="-1" role="dialog" aria-labelledby="employee-cru-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white p-0 lh-0"></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-times text-white"></i>
        </button>
      </div>
      <form enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row d-flex justify-content-center">
            <div class="col-md-10 offset-md-1 preview">
              <div class="preview-con" title="Click to upload a profile">
                <img src="" alt="Upload Profile">
              </div>
            </div>
          </div>
          <input type="file" class="d-none" id="upload-profile" name="profile">
          <input type="text" class="d-none" name="filename">
          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Name:* </label></div>
            <div class="col-md-7">
              <input type="text" name="name">
            </div>
          </div>
          
          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Email:* </label></div>
            <div class="col-md-7">
              <input type="text" name="email">
            </div>
          </div>

          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Mobile #: </label></div>
            <div class="col-md-7">
              <input type="text" name="mobile-number">
            </div>
          </div>

          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Gender:* </label></div>
            <div class="col-md-7">
              <select name="gender">
                <option value="">--select gender--</option>  
                <option value="Male">Male</option>  
                <option value="Female">Female</option>  
              </select>
            </div>
          </div>

          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Date of Birth:* </label></div>
            <div class="col-md-7">
              <input type="text" name="date-of-birth" class="date-format" maxlength="10" placeholder="mm/dd/yyyy">
            </div>
          </div>

          <div class="row input-con role-row">
            <div class="col-md-3 offset-md-1"><label>Role:* </label></div>
            <div class="col-md-7">
              <select name="role">
                <option value="">--select role--</option>
                <?php
                  $role = $this->users_type_model->get_all();
                  foreach($role as $role):
                    echo "<option value='{$role['id']}'>".ucfirst($role['user_type'])."</option>";
                  endforeach;
                ?>
              </select>
            </div>
          </div>

          <div class="row input-con schedule-row d-none">
            <div class="col-md-3 offset-md-1"><label>Schedule:* </label></div>
            <div class="col-md-7">
              <select name="schedule">
                <option value="">--select schedule--</option>
                <option value="fixed">Fixed</option>
                <option value="flexible">Flexible</option>
              </select>
            </div>
          </div>

          <div class="row input-con fixed-schedule-row d-none">
            <div class="col-md-3 offset-md-1"><label>Time:* </label></div>
            <div class="col-md-3"><input type="time" name="time-in"></div>
            <div class="col-md-1 text-center">to</div>
            <div class="col-md-3"><input type="time" name="time-out"></div>
          </div>

          <div class="row input-con salary-grade-row d-none">
            <div class="col-md-3 offset-md-1"><label>Salary Grade:* </label></div>
            <div class="col-md-7">
              <select name="salary-grade">
                <option value="">--select salary grade--</option>
                <?php
                  $salary_grade = $this->salary_grade_model->get_all();
                  foreach($salary_grade as $salary_grade):
                    echo "<option value='{$salary_grade['id']}'>
                      {$salary_grade['grade_number']} - {$salary_grade['hourly_rate']}/hour
                    </option>";
                  endforeach;
                ?>
              </select>
            </div>
          </div>

          <div class="row input-con username-row">
            <div class="col-md-3 offset-md-1"><label>Username:* </label></div>
            <div class="col-md-7">
              <input type="text" name="username">
            </div>
          </div>

          <div class="row input-con password-row">
            <div class="col-md-3 offset-md-1"><label>Password:* </label></div>
            <div class="col-md-7">
              <input type="password" name="password">
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-sblue w-100">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>