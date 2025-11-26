@extends("layouts.theme")
@section("title","Login")
@section("content")
<div class="container py-4" style="min-height: 81vh">
  <div class="row">
    <div class="col-md-4 my-5 mx-auto">
        <div class="card">
            <div class="card-header text-primary border-primary">
                <h1 class="h6 d-block py-0 my-0">Login</h1>
            </div>
            <div class="card-body">
                <form action="{{ route("login.store") }}" method="post">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" value="{{ old("email") }}" name="email" placeholder="youremail@example" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="****" class="form-control">
                    </div>
                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-sm btn-primary w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection