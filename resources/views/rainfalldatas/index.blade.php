@extends('layouts.app')

@section('content')
<h2>Demo DBs</h2>
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
            @foreach ($demodbs as $demodb)
            <tr>
                <td>{{ $demodb->name }}</td>
                <td>{{ $demodb->created_at }}</td>
                <td>
                    <form action="/rainfalldatas/{{ $demodb->id }}" method="POST">
                        @csrf
                        @method('delete')
                        <a href="rainfalldatas/{{ $demodb->id }}/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a> 
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
