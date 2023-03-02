<div class="modal fade" id="dtr-cru-modal" tabindex="-1" role="dialog" aria-labelledby="dtr-cru-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white p-0 lh-0"></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-times text-white"></i>
        </button>
      </div>
      <form>
        <div class="modal-body">
          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Date:* </label></div>
            <div class="col-md-7">
              <input type="text" name="date" class="date-format" value="<?= date("m/d/Y") ?>" maxlength="10" placeholder="mm/dd/yyyy">
            </div>
          </div>

          <div class="row input-con">
            <div class="col-md-3 offset-md-1"><label>Time-in:* </label></div>
            <div class="col-md-7">
              <input type="time" min="00:00" max="24:00" name="time-in">
            </div>
          </div>

          <div class="row input-con time-out-row d-none">
            <div class="col-md-3 offset-md-1"><label>Time-out:* </label></div>
            <div class="col-md-7">
               <input type="time" min="00:00" max="24:00" name="time-out">
            </div>
          </div>

          <div class="row input-con">
            <div class="col-md-3 offset-md-1 d-flex align-self-start"><label>Work Base:* </label></div>
            <div class="col-md-7">
              <select name="work-base">
                <option value="">---Select---</option>
                <option value="Office">Office</option>
                <option value="WFH">WFH</option>
                <option value="WFH/Office">WFH/Office</option>
              </select>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-7 offset-md-4">
              <div class="w-100 check-con d-flex align-items-center justify-content-end">
                <input type="checkbox" class="w-auto" value="shift" name="cb-moved-shift" id="cb-moved-shift">
                <label for="cb-moved-shift">&nbsp;Moved Shift</label>
              </div>
            </div>
          </div>

          <div class="row input-con shift-row d-none">
            <div class="col-md-3 offset-md-1"><label>Shift Reason:* </label></div>
            <div class="col-md-7">
              <select name="shift-reason">
                <option value="">---Select---</option>
                <option value="Personal Errand">Personal Errand</option>
                <option value="Need to rush documents asap">Need to rush documents asap</option>
                <option value="Assist TOPMAKE">Assist TOPMAKE</option>
                <option value="Training">Training</option>
                <option value="All Information Sheet">All Information Sheet</option>
                <option value="Others">Others</option>
              </select>
            </div>
          </div>

          <div class="row input-con others-row d-none">
            <div class="col-md-3 offset-md-1"><label>Others:* </label></div>
            <div class="col-md-7">
              <input type="text" name="others">
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