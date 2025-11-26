@extends("layouts.theme")
@section("title","Configuration")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <p class="h4 d-block">Configuration </p>
      </div>
    </div>
    <div class="col-md-8 mx-auto">
      <div class="card">
        <div class="card-header bg-info py-2 text-white">
          <p class="text-bold m-0 card-title">Configuration</p>
        </div>
        <div class="card-body">
          <form action="{{ route("configuration.store") }}" method="post" class="row">
            @csrf
            <div class="mb-2 form-group col-md-6">
              <label class="form-label">DAS Name *</label>
              <input type="text" name="name" value="{{ old("name", $config->name) }}" placeholder="Name" required class="form-control">
            </div>
            <div class="mb-2 form-group col-md-6">
              <label class="form-label">Server IP *</label>
              <input type="text" name="server_ip" value="{{ old("server_ip", $config->server_ip) }}" placeholder="Server IP" required class="form-control">
            </div>
            <div class="mb-2 form-group col-md-6">
              <label class="form-label" title="Server URL API POST">API URL *</label>
              <input type="text" name="server_url" value="{{ old("server_url", $config->server_url) }}" placeholder="Server API URL" required class="form-control">
            </div>  
            <div class="mb-2 form-group col-md-6">
              <label class="form-label">API Key *</label>
              <input type="password" name="server_apikey" value="{{ old("server_apikey", $config->server_apikey) }}" placeholder="API Key" class="form-control">
            </div>
            <div class="mb-2 form-group col-md-6">
              <label class="form-label">Status Mode RCA</label>
              @if($config->is_rca == 1)
                <p class="text-success">Active ({{ $stack->name ?? "-" }})</p>
              @else
                <p class="text-danger">Inactive</p>
              @endif
            </div>
            <div class="mb-2 form-group col-md-6 text-end align-self-end">
              <button type="submit" class="btn btn-info text-white ">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("js")
@endsection