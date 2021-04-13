@extends('layouts.app')
@push('scripts')
    <script>
        var arr = @json($rainfalldatas);
        var duration_initial = arr[0]['raingauges']['studysites']['duration_initial'] * 60;
        var duration_final = arr[0]['raingauges']['studysites']['duration_final'] * 60;
        var i = 0;
        setInterval(function(){
            var advisorylevel = arr[i]['advisorylevel'];
            var number = Number(advisorylevel);
            var row = document.getElementById("row_simulation");
            var mins = Number(arr[i]['advisorylevel_duration']);
            if(arr[i]['rainfallevent_duration'] > duration_initial && arr[i]['rainfallevent_duration'] < duration_final){
                if(number == 0){
                row.className = '';
                document.getElementById("advisory_level").innerHTML = "No Rainfall";
                } else if(number < 0.5){
                    row.className = 'bg-green';
                    document.getElementById("advisory_level").innerHTML = "Low";
                } else if(number < 0.75 && advisorylevel >= 0.5){
                    row.className = 'bg-yellow';
                    document.getElementById("advisory_level").innerHTML = "Medium";
                } else if(number >= 0.75 && advisorylevel < 0.9){
                    row.className = 'bg-orange';
                    document.getElementById("advisory_level").innerHTML = "Moderate High";
                } else if(number >= 0.9 && advisorylevel < 1){
                    row.className = 'bg-red';
                    document.getElementById("advisory_level").innerHTML = "High";
                } else if(number > 1){
                    row.className = 'bg-purple';
                    document.getElementById("advisory_level").innerHTML = "Extreme";
                }
                document.getElementById("conditions").innerHTML = "IR = " + number.toFixed(4);
                document.getElementById("duration").innerHTML = mins + " min";
                document.getElementById("datetime").innerHTML = arr[i]['dateTime'];   
            } else {
                document.getElementById("conditions").innerHTML = "IR = " + number.toFixed(4);
                document.getElementById("duration").innerHTML = mins + " min";
                document.getElementById("datetime").innerHTML = arr[i]['dateTime'];
                document.getElementById("advisory_level").innerHTML = "None";
                row.className = '';
            }
                     
            i++;
        }, 1000);
    </script>
@endpush
@section('content')
<h2>Simulation DB {{ $simulation->demodbs->name }}</h2>
<hr>
<div class="row">
    <div class="form-group col-md-12">
    <table data-toggle="table">
        <thead>
            <tr>
                <th>Study Site</th>
                <th>Rain Gauge</th>
                <th>Advisory Level</th>
                <th>Conditions</th>
                <th>Duration</th>
                <th>Datetime</th>
            </tr>
        </thead>
        <tbody>           
            <tr id="row_simulation">
                <td>{{ $simulation->raingauges->studysites->name }}</td>
                <td>{{ $simulation->raingauges->name }}</td>
                <td id="advisory_level"></td>
                <td id="conditions"></td>
                <td id="duration"></td>
                <td id="datetime"></td>
            </tr>
        </tbody>
    </table>
    </div>
</div>
@endsection