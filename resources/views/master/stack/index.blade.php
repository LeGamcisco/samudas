@extends("layouts.theme")
@section("title","Manage Stack")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Master > Stack</p>
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
                    <th>Oxygen Reference</th>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-createLabel">Create Stack</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route("master.stack.store") }}" id="form-create" class="row" method="post">
          @csrf
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Stack Name *</label>
            <input type="text" class="form-control" placeholder="Stack Name" name="name" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Oxygen Reference (%) *</label>
            <input type="number" min="0" max="100" placeholder="Oxygen Reference"  class="form-control" name="oxygen_reference" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Status</label>
            <select name="is_show" class="form-control" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group mt-2 col-md-12">
            <button type="submit" class="btn btn-success w-100">Create New</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-editLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-editLabel">Edit Stack</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-edit" class="row" method="post">
          @csrf
          @method("PATCH")
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Stack Name *</label>
            <input type="text" id="stack-name" class="form-control" placeholder="Stack Name" name="name" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Oxygen Reference (%) *</label>
            <input type="number" id="oxygen-reference" min="0" max="100" placeholder="Oxygen Reference"  class="form-control" name="oxygen_reference" required>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Status</label>
            <select name="is_show" id="is_show" class="form-control" required>
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
          <div class="form-group mt-2 col-md-12">
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
        <h5 class="modal-title" id="modal-deleteLabel">Delete Stack</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-delete" method="post">
          @csrf
          @method("DELETE")
          <p class="alert alert-danger">Are you sure want to delete this stack?</p>
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
      order : [[1, 'desc']],
      ajax: {
        url: "{{ route('master.stack.datatable') }}",
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
          data : 'name'
        },
        {
          data : 'oxygen_reference',
          render:function(data,type,row){
            return  `${row.oxygen_reference} <small class="text-muted" style="font-size:12px">%</small>`
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
            return `${moment(row?.created_at).format("dddd, D MMMM YYYY, H:mm:ss")}`
          }
        },
        {
          data : 'updated_at',
          render: function(data,type,row){
            return `${moment(row?.updated_at).fromNow()}`
          }
        },
      ]
    })
    // Event Handling
    $(document).on("click",".btn-edit",function(){
      const id = $(this).data("id")
      $.ajax({
        url : `{{ route('master.stack.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          const {data} = response
          $("#form-edit").attr("action",`{{ route('master.stack.index') }}/${id}`)
          $("#stack-name").val(data.name)
          $("#oxygen-reference").val(data.oxygen_reference)
          $("#is_show").val(data.is_show)
          $("#modal-edit").modal("show")
        },
        error : function(xhr){
        }
      })
    })
    $(document).on("click",".btn-delete",function(){
      const id = $(this).data("id")
      $("#form-delete").attr("action",`{{ route('master.stack.index') }}/${id}`)
      $("#modal-delete").modal("show")
    })
  })
</script>
@endsection