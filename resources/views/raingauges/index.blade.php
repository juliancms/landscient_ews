@extends('layouts.app')

@section('content')
<h2>Raingauges</h2>
<hr>
<div class="row">
    <div class="form-group col-md-12">
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Creation</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($raingauges as $raingauge)
            <tr>
                <td>{{ $raingauge->name }}</td>
                <td>{{ $raingauge->created_at }}</td>
                <td>
                    <form action="/raingauges/{{ $raingauge->id }}" method="POST">
                        @csrf
                        @method('delete')
                        <a href="raingauges/{{ $raingauge->id }}/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> 
                        <button class="border-0 bg-transparent" type="submit" data-toggle="tooltip" title="Remove"><i class="fa fa-trash-alt text-danger"></i></button>
                    </form>
                 </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
