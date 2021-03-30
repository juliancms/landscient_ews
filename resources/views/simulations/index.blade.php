@extends('layouts.app')

@section('content')
<h2>Simulations</h2>
<hr>
<div class="row">
    <div class="form-group col-md-12">
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Study Site</th>
                <th>Raingauge</th>
                <th>Date of Creation</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($simulations as $simulation)
            
            <tr>
                <td>{{ $simulation->raingauges->studysites->name }}</td>
                <td>{{ $simulation->raingauges->name }}</td>
                <td>{{ $simulation->created_at }}</td>
                <td>
                    <form action="/simulations/{{ $simulation->id }}" method="POST">
                        @csrf
                        @method('delete')
                        <a href="/simulations/{{ $simulation->id }}" data-toggle="tooltip" title="Show"><i class="fa fa-eye"></i></a>                         
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