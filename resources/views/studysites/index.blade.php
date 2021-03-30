@extends('layouts.app')

@section('content')
<h2>Study Sites</h2>
<hr>
<div class="row">
    <div class="form-group col-md-12">
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Alpha</th>
                <th>Beta</th>
                <th>Duration Initial</th>
                <th>Duration Final</th>
                <th>Date of Creation</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($studysites as $studysite)
            <tr>
                <td>{{ $studysite->name }}</td>
                <td>{{ $studysite->alpha }}</td>
                <td>{{ $studysite->beta }}</td>
                <td>{{ $studysite->duration_initial }}</td>
                <td>{{ $studysite->duration_final }}</td>
                <td>{{ $studysite->created_at }}</td>
                <td>
                    <form action="/studysites/{{ $studysite->id }}" method="POST">
                        @csrf
                        @method('delete')
                        <a href="studysites/{{ $studysite->id }}/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> 
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
