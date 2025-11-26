@extends("layouts.theme")
@section("title","Manage References")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Master > Reference</p>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <button type="button" data-bs-toggle="modal" data-bs-target="#modal-create" class="btn btn-sm btn-success">Create New</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="table-data" class="table table-sm table-bordered table-hover table-striped">
              <thead>
                  <tr>
                    <th>Action</th>
                    <th>Stack</th>
                    <th>Sensor</th>
                    <th>Range Start</th>
                    <th>Range End</th>
                    <th>Formula</th>
                    <th>Created at</th>
                    <th>Last updated</th>
                  </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("modal")
<!-- Modal Create -->
<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="modal-createLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-createLabel">Create Reference</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route("master.reference.store") }}" id="form-create" class="row" method="post">
          @csrf
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Parameter *</label>
            <select name="sensor_id" class="form-control" required>
              <option value="">Select Parameter</option>
              @foreach ($sensors as $sensor)
                <option value="{{ $sensor->id }}">{{ $sensor->stack->name }} | {{ $sensor->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Range Start *</label>
            <input type="numeric" inputmode="numeric" class="form-control" name="range_start" placeholder="Range Start" required>
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Range End *</label>
            <input type="numeric" inputmode="numeric" class="form-control" name="range_end" placeholder="Range Start" required>
          </div>     
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Formula *</label>
            <textarea name="formula" class="form-control" placeholder="Formula"></textarea>
          </div>          
          <div class="form-group mt-2 col-md-12">
            <button type="reset" class="btn btn-danger w-100 d-none">Reset</button>
            <button type="submit" class="btn btn-success w-100">Create New</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-editLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-editLabel">Edit Reference</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-edit" class="row" method="post">
          @csrf
          @method("PATCH")
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Parameter *</label>
            <select name="sensor_id" class="form-control" required>
              <option value="">Select Parameter</option>
              @foreach ($sensors as $sensor)
                <option value="{{ $sensor->id }}">{{ $sensor->stack->name }} | {{ $sensor->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Range Start *</label>
            <input type="numeric" inputmode="numeric" class="form-control" name="range_start" placeholder="Range Start">
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Range End *</label>
            <input type="numeric" inputmode="numeric" class="form-control" name="range_end" placeholder="Range Start">
          </div>     
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Formula *</label>
            <textarea name="formula" class="form-control" placeholder="Formula"></textarea>
          </div>          
          <div class="form-group mt-2 col-md-12">
            <button type="reset" class="btn btn-danger w-100 d-none">Reset</button>
            <button type="submit" class="btn btn-success w-100">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-deleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-deleteLabel">Delete Reference</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-delete" method="post">
          @csrf
          @method("DELETE")
          <p class="alert alert-danger">Are you sure want to delete this reference?</p>
          <div class="form-group mt-2 col-md-12">
            <button type="submit" class="btn btn-danger w-100">Confirm Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset("assets/dist/vendor/DataTables/css/dataTables.bootstrap5.min.css") }}">
@section("js")
<script src="{{ asset("assets/dist/vendor/DataTables/js/dataTables.min.js") }}"></script>
<script src="{{ asset("assets/dist/vendor/DataTables/js/dataTables.bootstrap.min.js") }}"></script>
<script>
  $(document).ready(function(){
    const table = $('#table-data').DataTable({
      processing: true,
      serverSide: true,
      order : [[0, 'desc']],
      ajax: {
        url: "{{ route('master.reference.datatable') }}",
      },
      columns: [
        {
          data : 'id',
          render:function(data,type,row){
            return `
              <button type="button" data-id="${row.id}" class="btn-edit btn btn-sm text-light btn-info">Edit</button>
              <button type="button" data-id="${row.id}" class="btn-delete btn btn-sm text-light btn-danger">Delete</button>
            `
          }
        },
        {
          data : 'sensor.stack.name',
          searchable : false,
          orderable : false
        },
        {
          data : 'sensor.name'
        },
        {
          data : 'range_start',
        },
        {
          data : 'range_end',
        },
        {
          data : 'formula',
        },
        
        {
          data : 'created_at',
          render: function(data,type,row){
            return row?.created_at ? `${moment(row?.created_at).format("dddd, D MMMM YYYY, H:mm:ss")}` : `-`
          }
        },
        {
          data : 'updated_at',
          render: function(data,type,row){
            return row?.updated_at ? `${moment(row?.updated_at).fromNow()}` : `-`
          }
        },
      ]
    })
    // Event Handling
    $(document).on("click",".btn-edit",function(){
      const id = $(this).data("id")
      $.ajax({
        url : `{{ route('master.reference.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          const {data} = response
          const form = $("#form-edit")
          form.attr("action",`{{ route('master.reference.index') }}/${id}`)
          form.find("select[name='sensor_id']").val(data.sensor_id)
          form.find("input[name='range_start']").val(data.range_start)
          form.find("input[name='range_end']").val(data.range_end)
          form.find("textarea[name='formula']").val(data.formula)
          $("#modal-edit").modal("show")
        },
        error : function(xhr){
        }
      })
    })
    $(document).on("click",".btn-delete",function(){
      const id = $(this).data("id")
      $("#form-delete").attr("action",`{{ route('master.reference.index') }}/${id}`)
      $("#modal-delete").modal("show")
    })
    $("#form-create").submit(function(e){
      e.preventDefault()
      $.ajax({
        url : $(this).attr("action"),
        type : "POST",
        data : $(this).serialize(),
        success : function(response){
          table.ajax.reload()
          $(this).find("button[type='reset']").trigger("click")
          $("#modal-create").modal("hide")
        },
        error : function(xhr){
          toastr.error(xhr.responseJSON?.message)
          
        }
      })
    })
    $("#form-edit").submit(function(e){
      e.preventDefault()
      $.ajax({
        url : $(this).attr("action"),
        type : "PATCH",
        data : $(this).serialize(),
        success : function(response){
          table.ajax.reload()
          $(this).find("button[type='reset']").trigger("click")
          $("#modal-edit").modal("hide")
        },
        error : function(xhr){
          toastr.error(xhr.responseJSON?.message)
        }
      })
    })
  })
</script>
@endsection