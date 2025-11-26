@extends("layouts.theme")
@section("title","Measurement 1 Hour")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Measurement 1 Hour</p>
        <div class="dropdown">
          <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ $stack->name }}
          </a>
          <ul class="dropdown-menu">
            @foreach ($stacks as $stack)
            <li><a class="dropdown-item" href="{{ url("measurements/$stack->id") }}">{{ $stack->name }}</a></li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between">
          <button type="button" data-bs-toggle="modal" data-bs-target="#modal-filter" class="btn btn-sm btn-secondary">Filter </button>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-exportKLHK" class="btn btn-sm btn-info">Export with SIMPEL Format</button>
            <button type="button" id="btn-export" class="btn btn-sm btn-success">Export</button>
          </div>
        </div>
        <div class="card-body">
          @if(session()->has("error"))
          <p class="alert alert-danger">
            {{ session()->get("error") }}
          </p>
          @endif
          <div class="table-responsive">
            <table id="table-data" class="table table-sm table-bordered table-hover table-striped">
              <thead>
                  <tr>
                    <th>#ID</th>
                    <th>Timestamp</th>
                    <th>Sensor</th>
                    <th>Measured</th>
                    <th>Corrected</th>
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
<!-- Modal -->
<div class="modal fade" id="modal-filter" tabindex="-1" aria-labelledby="modal-filterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-filterLabel">Filter Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="#" id="form-filter" class="row" method="get">
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Data Source</label>
            <select name="data_source" class="form-control">
              <option value="">measurements</option>
              @foreach ($tables as $table)
                <option value="{{ $table }}">{{ $table }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Parameter</label>
            <select name="parameter_id" class="form-control">
              <option value="">All</option>
              @foreach ($parameters as $parameter)
                <option value="{{ $parameter->parameter_id }}">{{ $parameter->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Start At</label>
            <input type="datetime-local" class="form-control" name="start_at" value="{{ now()->subDays(7)->format("Y-m-d H:i") }}">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">End At</label>
            <input type="datetime-local" class="form-control" name="end_at" value="{{ now()->format("Y-m-d H:i") }}">
          </div>
          
          <div class="form-group mt-2 col-md-12">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Export KLHK -->
<div class="modal fade" id="modal-exportKLHK" tabindex="-1" aria-labelledby="modal-exportKLHKLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-exportKLHKLabel">Export Data with Format SIMPEL</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route("measurement.exportKLHK") }}" id="form-export-klhk" class="row" method="post">
          @csrf
          <input type="hidden" name="data_source" value="measurements">
          <div class="form-group col-md-12">
            <label class="form-label fw-bold">Parameter</label>
            <select name="parameter_id" class="form-control">
              @foreach ($parameters as $parameter)
                <option value="{{ $parameter->parameter_id }}">{{ $parameter->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">Start At</label>
            <input type="datetime-local" class="form-control" name="start_at" value="{{ now()->subDays(7)->format("Y-m-d H:00") }}">
          </div>
          <div class="form-group col-md-6">
            <label class="form-label fw-bold">End At</label>
            <input type="datetime-local" class="form-control" name="end_at" value="{{ now()->format("Y-m-d H:00") }}">
          </div>
          
          <div class="form-group mt-2 col-md-12">
            <button type="submit" class="btn btn-primary w-100">Export</button>
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
        url: "{{ route('measurement.datatable') }}",
        data : function(params){
          params._token = "{{ csrf_token() }}"
          params.data_source = $('select[name="data_source"]').val()
          params.start_at = $('input[name="start_at"]').val()
          params.end_at = $('input[name="end_at"]').val()
          params.parameter_id = $('select[name="parameter_id"]').val()
          params.is_sent = $('select[name="is_sent"]').val()
          params.stack_id = {{ $stackId }}
        }
      },
      columns: [
        {
          data : 'id'
        },
        {
          data : 'time_group'
        },
        {
          data : 'parameter_name'
        },
        {
          data : 'measured',
          render : function(data,type,row){
            return `${data} <small style="font-size:9px" class="text-muted">${row?.unit_name}</small>`
          }
        },
        {
          data : 'corrected',
          render : function(data,type,row){
            return `${data} <small style="font-size:9px" class="text-muted">${row?.unit_name}</small>`
          }
        },
      ]
    })
    $("#form-filter").submit(function(e){
      e.preventDefault()
      table.ajax.reload()
      $("#modal-filter").modal("hide")
      $("input[name='data_source']").val($("select[name='data_source']").val())
    })
    $("#btn-export").click(function(){
       $("#form-filter").submit()
       const params = $("#form-filter").serialize()
       window.location.href = `{{ route('measurement.export') }}?${params}`
    })
  })
</script>
@endsection