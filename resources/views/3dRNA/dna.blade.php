@extends('layouts.rna')

@section('head') @parent
<link rel="stylesheet" type="text/css" href="/css/3dDNA/index.css" media="all" />
<script src="/js/3dDNA/index.js"></script>
@endsection

@section('header-top')
<a id="title" href="/3dDNA">3dDNA: Automatic building of ssDNA 3D structures</a>
@endsection

@section('content-main')
<div class='box' class='container'>
  <FORM name="form1" METHOD="POST" ACTION="/3dDNA/Submit" ENCTYPE="multipart/form-data" target="_blank" class='form-horizontal'>

    <input type="hidden" name="mol_type" value="DNA">

    <div class='form-group'>
      <label class="col-sm-2 control-label">Email (optional):</label>
      <div class="col-sm-4">
        <input type="text" name="email" size="30" class='form-control'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-sm-2 control-label'>Prediction Type:</label>
      <div class='col-sm-3'>
        <select name="pred_type" class='form-control' value='duplex' autocomplete='off'>
          <option value='duplex'>Duplex</option>
          <option value='triplex'>Triplex</option>
          <option value='quadruplex'>Quadruplex</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Sequence:</label>
      <div class="col-sm-6">
        <textarea name="seq" id='s_text' size="20" rows="3" cols="60" autocomplete='off' class='form-control'></textarea>
      </div>
      <div class='col-sm-4'>
        Examples:<ul><li><a id='example1'>Example 1</a></li><li><a id='example2'>Example 2</a></li></ul>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-sm-2 control-label'>2D Structure (optional):</label>
      <div class='col-sm-6'><textarea name="ss" size="20" rows="3" autocomplete='off' class='form-control'></textarea></div>
    </div>

    <div class='form-group'>
      <div class='col-sm-offset-2 col-sm-2'>
        <a id="a-advanced-options">Advanced Settings</a>
      </div>
    </div>

    <div id='advanced-options'>

      <div class='form-group'>
        <label class='col-sm-3 control-label'>Number of clusters:</label>
        <div class='col-sm-4'><input name="num" type="text" value="5" autocomplete='off' class='form-control'></div>
      </div>

      <div class='form-group'>
        <label class='col-sm-3 control-label'>Constraints:</label>
        <div class='col-sm-6'><textarea name="constraints" size="20" rows="3" autocomplete='off' class='form-control'></textarea></div>
      </div>

      <div class='form-group'>
        <div class='col-sm-offset-3 col-sm-4'>
          <div class='checkbox'>
            <label><input type='checkbox' id='en_min' name='en_min' checked>Energy minimization?</label>
          </div>
        </div>
      </div>

      <div class='form-group'>
        <div class='col-sm-offset-3 col-sm-4'>
          <div class='checkbox'>
            <label><input type='checkbox' id='compute_score' name='compute_score'>Compute 3dRNAscore?</label>
          </div>
        </div>
      </div>

    </div>

    <div class='row'>
      <div class='col-sm-offset-2 col-sm-4'>
        <button id='pred2d' type="button" class="btn btn-default">Pred 2D</button>
        <button id='pred3d' type="submit" class="btn btn-info">Pred 3D</button>
        <button type="reset" class="btn btn-warning">Clear</button>
      </div>
    </div>

  </form>
</div>

<!-- query box --!>
<div class='box'>
  <form method='post' action='/3dRNA/jobs' name='form2' target='_blank'>
    <table>
      <tr>
        <td>Query your job:</td>
        <td><input type='text' name='query' id='input-text-query' style='width: 300px' placeholder='Input your job ID or email address here.'></td>
        <td><input type='submit' value='Query' class='btn btn-info btn-xs'></td>
      </tr>
    </table>
  </form>
</div>

<div class='box'>
  <img src="/image/pic.jpg" width=60% />
  <p>The prediction result (blue) is superimposed on its respective experimental structure (gold).</p></div>

@endsection

