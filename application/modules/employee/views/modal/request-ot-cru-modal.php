<div class="modal fade" id="request-ot-cru-modal" tabindex="-1" role="dialog" aria-labelledby="request-ot-cru-modal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white p-0 lh-0 d-flex align-items-center"></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-times text-white"></i>
        </button>
      </div>
      <form>
        <div class="modal-body">

          <div class="row input-con">
            <div class="col-md-2 offset-md-1"><label>date:* </label></div>
            <div class="col-md-8">
              <input type="text" name="date" class="date-format" maxlength="10" placeholder="mm/dd/yyyy">
            </div>
          </div>

          <div class="row input-con pr-20">
            <div class="col-md-2 offset-md-1"><label>Time:* </label></div>
            <div class="col-md-8 time-in-out-col">
              <i class="fas fa-plus-circle" id="add-time" title="Add another time" ></i>
              <div class="row mb-10px last-row">
                <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-in[]"></div>
                <div class="col-md-6"><input type="time" min="00:00" max="24:00" name="time-out[]"></div>
              </div>
            </div>
          </div>
          
          <div class="row input-con">
            <div class="col-md-2 offset-md-1"><label>Task:* </label></div>
            <div class="col-md-8">
              <textarea class="w-100" rows="5" name="task" placeholder="Separate tasks by next line"></textarea>
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