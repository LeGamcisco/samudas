@extends("layouts.theme")
@section("title","Manage Admin")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Master > Administrator</p>
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
                    <th>Email</th>
                    <th>Name</th>
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
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-createLabel">Create New Administrator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route("master.user.store") }}" id="form-create" class="row" method="post">
          @csrf
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Full Name*</label>
            <input type="text" placeholder="Full Name"  class="form-control" name="name" required>
          </div>  
           <div class="form-group col-md-6">
            <label class="form-label fw-bold">Email *</label>
            <input type="email" inputmode="email" placeholder="Email"  class="form-control" name="email" required>
          </div>  
           <div class="form-group col-md-6">
            <label class="form-label fw-bold">Password *</label>
            <input type="password" placeholder="Password"  class="form-control" name="password" required>
          </div>  
           <div class="form-group col-md-6">
            <label class="form-label fw-bold">Confirm Password *</label>
            <input type="password" placeholder="Re-type password"  class="form-control" name="password_confirmation" required>
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
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-editLabel">Edit Administrator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-edit" class="row" method="post">
          @csrf
          @method("PATCH")
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Full Name*</label>
            <input type="text" placeholder="Full Name"  class="form-control" name="name" required>
          </div>  
           <div class="form-group col-md-6">
            <label class="form-label fw-bold">Email *</label>
            <input type="email" inputmode="email" placeholder="Email"  class="form-control" name="email" required>
          </div>  
           <div class="form-group col-md-12">
            <label class="form-label fw-bold">Change Password (fill if want to change)</label>
            <input type="password" placeholder="Password"  class="form-control" name="password">
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
<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modal-deleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-deleteLabel">Delete Administrator?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" id="form-delete" method="post">
          @csrf
          @method("DELETE")
          <p class="alert alert-danger">Are you sure want to delete this admin?</p>
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
        <h5 class="modal-title" id="modal-detailLabel">Detail Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row gap-y-4">
          <div class="col-md-12">
            <strong>Fullname:</strong>
            <p class="text-muted m-0" id="name"></p>
          </div>
          <div class="col-md-6">
            <strong>Email:</strong>
            <p class="text-muted m-0" id="email"></p>
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
        url: "{{ route('master.user.datatable') }}",
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
          data : 'name',
        },
        {
          data : 'email',
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
        url : `{{ route('master.user.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          const {data} = response
          const form = $("#form-edit")
          form.attr("action",`{{ route('master.user.index') }}/${id}`)
          form.find("input[name='code']").val(data.code)
          form.find("input[name='name']").val(data.name)
          form.find("input[name='email']").val(data.email)
          $("#modal-edit").modal("show")
        },
        error : function(xhr){
        }
      })
    })
    $(document).on("click",".btn-delete",function(){
      const id = $(this).data("id")
      $("#form-delete").attr("action",`{{ route('master.user.index') }}/${id}`)
      $("#modal-delete").modal("show")
    })
    $(document).on("click",".btn-show",function(){
      const id = $(this).data("id")
      $.ajax({
        url : `{{ route('master.user.index') }}/${id}`,
        dataType : "json",
        success : function(response){
          if(response?.success){
            const {data} = response
            // console.log(data)
            $("#name").html(data?.name)
            $("#email").html(data?.email)
            $("#modal-detail").modal("show")
          }
        }
      })
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