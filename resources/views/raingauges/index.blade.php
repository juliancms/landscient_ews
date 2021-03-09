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
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> 
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Remove"><i class="fa fa-trash-alt text-danger"></i></a>
                 </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
