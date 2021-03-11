@extends('layouts.app')

@section('content')
<h2>Edit Demo DB</h2>
<hr>
<div class="row">
    <div class="form-group col-md-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/rainfalldatas/{{ $demodb->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $demodb->name }}">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
    </div>
</div>
@endsection