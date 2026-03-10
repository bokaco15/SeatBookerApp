@extends('layouts.app')

@section('content')

    <div class="container-fluid mt-4">

        <h2 class="mb-4">Dashboard</h2>

        <div class="row g-4">

            <div class="col-md-3">
                <div class="card text-white bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Events</h5>
                        <h3>{{$eventCount}}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Seats</h5>
                        <h3>{{$seatsCount}}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Halls</h5>
                        <h3>{{$hallsCount}}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-danger shadow">
                    <div class="card-body">
                        <h5 class="card-title">Bookings</h5>
                        <h3>{{$bookingCount}}</h3>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
