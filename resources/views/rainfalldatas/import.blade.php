@extends('layouts.app')

@section('content')
<h2>Import Rainfall Data</h2>
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
        <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <select class="form-control" id="raingauge_id" name="raingauge_id">
                  @foreach ($raingauges as $raingauge)
                    <option value="{{ $raingauge->id }}">{{ $raingauge->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="name" class="col-sm-2 col-form-label">File</label>
              <div class="col-sm-10">
                <input type="file" id="file" name="file" class="form-control">
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