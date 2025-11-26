@extends("layouts.theme")
@section("title","Manage Sensor")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Master > Sensor</p>
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
                    <th>Name</th>
                    <th>Unit</th>
                    <th>Extra Parameter</th>
                    <th>Has Parameter Reference</th>
                    <th>Status</th>
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
        <h5 class="modal-title" id="modal-createLabel">Create Sensor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route("master.sensor.store") }}" id="form-create" class="row" method="post">
          @csrf
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Sensor Code *</label>
            <input type="text" placeholder="Sensor Code"  class="form-control" name="code" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Parameter Name *</label>
            <input type="text" placeholder="Parameter Name"  class="form-control" name="name" required>
          </div>
          <div class="form-group col-md-6">
            <div class="d-flex justify-content-start gap-1">
              <label class="form-label fw-bold">Parameter ID *</label>
              <p class="text-info m-0">Parameter ID must be synchronized with DIS</p>
            </div>
            <input type="number" placeholder="Parameter ID"  class="form-control" name="parameter_id" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Stack *</label>
            <select name="stack_id" class="form-control" required>
              <option value="">Select Stack</option>
              @foreach ($stacks as $stack)
                <option value="{{ $stack->id }}">{{ $stack->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Unit *</label>
            <select name="unit_id" class="form-control" required>
              <option value="">Select Unit</option>
              @foreach ($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
              @endforeach
            </select>
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Status</label>
            <select name="is_show" class="form-control" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Analyzer IP</label>
            <input type="text" class="form-control" name="analyzer_ip" placeholder="Analyzer IP">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Port</label>
            <input type="text" class="form-control" name="port" placeholder="Port">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Extra Parameter *</label>
            <select name="extra_parameter" class="form-control" required>
              <option value="0">No</option>
              <option value="1">O2</option>
              <option value="3">Flowrate</option>
              <option value="2">Parameter RCA</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Has Parameter Reference? *</label>
            <select name="is_has_reference" class="form-control" required>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div  class="o2-correction-section form-group col-md-6">
            <label class="form-label fw-bold">O2 Correction *</label>
            <select name="o2_correction" class="form-control">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
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
        <h5 class="modal-title" id="modal-editLabel">Edit Sensor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-edit" class="row" method="post">
          @csrf
          @method("PATCH")
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Sensor Code *</label>
            <input type="text" placeholder="Sensor Code"  class="form-control" name="code" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Parameter Name *</label>
            <input type="text" placeholder="Parameter Name"  class="form-control" name="name" required>
          </div>
          <div class="form-group col-md-6">
            <div class="d-flex justify-content-start gap-1">
              <label class="form-label fw-bold">Parameter ID *</label>
              <p class="text-info m-0">Parameter ID must be synchronized with DIS</p>
            </div>
            <input type="number" placeholder="Parameter ID"  class="form-control" name="parameter_id" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Stack *</label>
            <select name="stack_id" class="form-control" required>
              <option value="">Select Stack</option>
              @foreach ($stacks as $stack)
                <option value="{{ $stack->id }}">{{ $stack->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Unit *</label>
            <select name="unit_id" class="form-control" required>
              <option value="">Select Unit</option>
              @foreach ($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
              @endforeach
            </select>
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Status</label>
            <select name="is_show" class="form-control" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>     
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Analyzer IP</label>
            <input type="text" class="form-control" name="analyzer_ip" placeholder="Analyzer IP">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Port</label>
            <input type="text" class="form-control" name="port" placeholder="Port">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Extra Parameter *</label>
            <select name="extra_parameter" class="form-control" required>
              <option value="0">No</option>
              <option value="1">O2</option>
              <option value="3">Flowrate</option>
              <option value="2">Parameter RCA</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Has Parameter Reference? *</label>
            <select name="is_has_reference" class="form-control" required>
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div  class="o2-correction-section form-group col-md-6">
            <label class="form-label fw-bold">O2 Correction *</label>
            <select name="o2_correction" class="form-control">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Formula *</label>
            <textarea name="formula" class="form-control" placeholder="Formula"></textarea>
          </div>          
          <div class="form-group mt-2 col-md-12">
            <button type="reset" class="btn btn-danger w-100 d-none">Reset</button>
            <button type="submit" class="btn btn-success w-100">Save Changes</button>
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
        <h5 class="modal-title" id="modal-deleteLabel">Delete Sensor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-delete" method="post">
          @csrf
          @method("DELETE")
          <p class="alert alert-danger">Are you sure want to delete this sensor?</p>
          <div class="form-group mt-2 col-md-12">
            <button type="submit" class="btn btn-danger w-100">Confirm Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detailLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-detailLabel">Detail Sensor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row gap-y-4">
          <div class="col-md-12">
            <strong>Stack:</strong>
            <p class="text-muted m-0" id="stack_name"></p>
          </div>
          <div class="col-md-6">
            <strong>Sensor Code:</strong>
            <p class="text-muted m-0" id="sensor_code"></p>
          </div>
          <div class="col-md-6">
            <strong>Sensor Name:</strong>
            <p class="text-muted m-0" id="sensor_name"></p>
          </div>
          <div class="col-md-6">
            <strong>Analyzer IP:</strong>
            <p class="text-muted m-0" id="analyzer_ip"></p>
          </div>
          <div class="col-md-6">
            <strong>Port:</strong>
            <p class="text-muted m-0" id="port"></p>
          </div>
          <div class="col-md-6">
            <strong>Unit:</strong>
            <p class="text-muted m-0" id="unit_name"></p>
          </div>
          <div class="col-md-6">
            <strong>Extra Parameter:</strong>
            <p class="text-muted m-0" id="extra_parameter" ></p>
          </div>
          <div class="col-md-6">
            <strong>Has Parameter Reference?:</strong>
            <p class="text-muted m-0" id="is_has_reference" ></p>
          </div>
          <div class="col-md-6">
            <strong>Status:</strong>
            <p class="text-muted m-0" id="is_show"></p>
          </div>
          <div class="col-md-6">
            <strong>Is Multi Parameter:</strong>
            <p class="text-muted m-0" id="is_multi_parameter"></p>
          </div>
          <div class="col-md-12">
            <strong>Formula:</strong>
            <textarea id="formula" readonly class="form-control"></textarea>
          </div>
        </div>
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
        url: "{{ route('master.sensor.datatable') }}",
      },
      columns: [
        {
          data : 'id',
          render:function(data,type,row){
            return `
              <button type="button" data-id="${row.id}" class="btn-show btn btn-sm text-light btn-primary">Detail</button>
              <button type="button" data-id="${row.id}" class="btn-edit btn btn-sm text-light btn-info">Edit</button>
              <button type="button" data-id="${row.id}" class="btn-delete btn btn-sm text-light btn-danger">Delete</button>
            `
          }
        },
        {
          data : 'stack.name'
        },
        {
          data : 'name',
        },
        {
          data : 'unit.name',
        },
        {
          data : 'extra_parameter',
          render:function(data,type,row){
            if(row?.extra_parameter == 0){
              return `<span class="badge bg-secondary">Non Extra Parameter</span> | ${row?.o2_correction == 1 ? `<span class="badge bg-success">O2 Correction</span>` :  `<span class="badge bg-danger">Non Correction</span>`}`
            }
            if(row?.extra_parameter == 1){
              return `<span class="badge bg-success">O2</span>`
            }
            if(row?.extra_parameter == 2){
              return `<span class="badge bg-info">Parameter RCA </span> | ${row?.o2_correction == 1 ? `<span class="badge bg-success">O2 Correction</span>` :  `<span class="badge bg-danger">Non Correction</span>`}`
            }
            return `-`
          }
        },
        {
          data : 'is_has_reference',
          render:function(data,type,row){
            return  `${row.is_has_reference == 1 ? `<span class="badge bg-success">Yes</span>` :  `<span class="badge bg-danger">No</span>`}`
          }
        },
        {
          data : 'is_show',
          render:function(data,type,row){
            return  `${row.is_show == 1 ? `<span class="badge bg-success">Active</span>` :  `<span class="badge bg-danger">Inactive</span>`}`
          }
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
        url : `{{ route('master.sensor.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          const {data} = response
          const form = $("#form-edit")
          form.attr("action",`{{ route('master.sensor.index') }}/${id}`)
          form.find("input[name='code']").val(data.code)
          form.find("input[name='name']").val(data.name)
          form.find("input[name='parameter_id']").val(data.parameter_id)
          form.find("select[name='stack_id']").val(data.stack_id)
          form.find("select[name='unit_id']").val(data.unit_id)
          form.find("select[name='is_show']").val(data.is_show)
          form.find("input[name='analyzer_ip']").val(data.analyzer_ip)
          form.find("input[name='port']").val(data.port)
          form.find("select[name='extra_parameter']").val(data.extra_parameter)
          form.find("select[name='is_has_reference']").val(data.is_has_reference ? 1 : 0).trigger("change")
          form.find("select[name='o2_correction']").val(data.o2_correction)
          form.find("textarea[name='formula']").val(data.formula)
          form.find("select[name='extra_parameter']").trigger("change")
          $("#modal-edit").modal("show")
        },
        error : function(xhr){
        }
      })
    })
    $(document).on("click",".btn-delete",function(){
      const id = $(this).data("id")
      $("#form-delete").attr("action",`{{ route('master.sensor.index') }}/${id}`)
      $("#modal-delete").modal("show")
    })
    $(document).on("click",".btn-show",function(){
      const id = $(this).data("id")
      $.ajax({
        url : `{{ route('master.sensor.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          if(response?.success){
            const {data} = response
            // console.log(data)
            $("#stack_name").html(data?.stack?.name)
            $("#sensor_code").html(data?.code)
            $("#sensor_name").html(data?.name)
            $("#analyzer_ip").html(data?.analyzer_ip)
            $("#port").html(data?.port)
            $("#unit_name").html(data?.unit?.name)
            $("#is_show").html(data?.is_show == 1 ? `<span class="badge bg-success">Active</span>` :  `<span class="badge bg-danger">Inactive</span>`)
            $("#extra_parameter").html(data?.extra_parameter == 1 ? `<span class="badge bg-success">02</span>` :  data?.extra_parameter == 2 ? `<span class="badge bg-info">Parameter RCA</span> | ${data?.o2_correction == 1 ? `<span class="badge bg-success">O2 Correction</span>` :  `<span class="badge bg-danger">Non Correction</span>`}` :  `<span class="badge bg-secondary">No</span>`)
            $("#is_has_reference").html(data?.is_has_reference == 1 ? `<span class="badge bg-success">Yes</span>` :  `<span class="badge bg-secondary">No</span>`)
            $("#is_multi_parameter").html(data?.is_multi_parameter == 1 ? `<span class="badge bg-success">Yes</span>` :  `<span class="badge bg-secondary">Non Multi</span>`)
            $("#formula").val(data?.formula)
            $("#modal-detail").modal("show")
          }
        }
      })
    })
    // $("select[name='extra_parameter']").change(function(){
    //   if($(this).val() == 2){
    //     $(".o2-correction-section").removeClass("invisible")
    //     $("select[name='o2_correction']").attr("required",true)
    //   }else{
    //     $("select[name='o2_correction']").attr("required",false)
    //     if(!$(".o2-correction-section").hasClass("invisible")){
    //       $(".o2-correction-section").addClass("invisible")
    //     }
    //   }
    // })
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