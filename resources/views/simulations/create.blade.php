@extends('layouts.app')

@section('content')
<h2>Create Simulation</h2>
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
        <form action="/simulations" method="POST">
            @csrf
            <div class="form-group row">
              <label for="demodb" class="col-sm-2 col-form-label">Demo DB</label>
              <div class="col-sm-10">
                <select class="form-control" id="demodb" name="demodb">
                  @foreach ($demodbs as $demodb)
                    <option value="{{ $demodb->id }}">{{ $demodb->name }}</option>
                  @endforeach
                </select>
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