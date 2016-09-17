<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class='form-group' style="margin-bottom:0px">
    <label class='col-sm-2 col-xs-12 control-label'>Query your job:</label>
    <div class='col-sm-6 col-xs-8'>
        <input type='text' class='form-control' name='query' placeholder='Input your job ID or email or IP here.'>
    </div>
    <div class='col-sm-2 col-xs-2'>
        <button type='submit' class='btn btn-info'>Query</button>
    </div>
</div>

