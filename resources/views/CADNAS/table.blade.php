<table class="sortable table table-striped">
  <thead>
    <tr><th><input type="checkbox" id="select-all">All</th>
        @foreach ($items as $item)
          <th class='sort-button'>{{$item_names[$item]}}</th>
        @endforeach
    </tr></thead>
  <tbody>
    <?php $n=1 ?>
    @foreach ($result as $row)
      <tr><td><input type='checkbox' name='select' class='select-td'>{{$n}}</td>
          @foreach ($items as $item)
            @if ($item == 'name')
              <td><a href='http://www.rcsb.org/pdb/explore/explore.do?structureId={{$row[$item]}}' target='_blank'>{{$row[$item]}}</a></td>
            @else
              <td>{{$row[$item]}}</td>
            @endif
          @endforeach
      </tr>
      <?php $n=$n+1 ?>
    @endforeach
  </tbody>
  <tfoot>
  </tfoot></table>



