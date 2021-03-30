@extends('layouts.app')

@section('content')
<h2>Edit Study Site</h2>
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
        <form action="/studysites/{{ $studysite->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $studysite->name }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="alpha" class="col-sm-2 col-form-label">Alpha</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="alpha" name="alpha" placeholder="Alpha" value="{{ $studysite->alpha }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="beta" class="col-sm-2 col-form-label">Beta</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="beta" name="beta" placeholder="Beta" value="{{ $studysite->beta }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="duration_initial" class="col-sm-2 col-form-label">Duration Initial</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="duration_initial" name="duration_initial" placeholder="Duration Initial" value="{{ $studysite->duration_initial }}">
              </div>
            </div>
            <div class="form-group row">
              <label for="duration_final" class="col-sm-2 col-form-label">Duration Final</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="duration_final" name="duration_final" placeholder="Duration Final" value="{{ $studysite->duration_final }}">
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