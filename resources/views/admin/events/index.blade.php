@extends('layouts.app')

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Events</h2>

            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                Create New Event
            </a>
        </div>

        <div class="card shadow-sm p-3">

            <table id="eventsTable" class="table table-striped">

                <thead>

                <tr>
                    <th>ID</th>
                    <th>Hall</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Starts At</th>
                    <th>Ends At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                </thead>

                <tbody>

                @foreach($events as $event)

                    <tr>

                        <td>{{ $event->id }}</td>

                        <td>{{ $event->hall->name ?? '-' }}</td>

                        <td>{{ $event->name }}</td>

                        <td>{{ $event->type }}</td>

                        <td>{{ $event->starts_at }}</td>

                        <td>{{ $event->ends_at }}</td>

                        <td>
                        <span class="badge {{ $event->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $event->status ? 'Active' : 'Inactive' }}
                        </span>
                        </td>

                        <td>

                            <a href="{{ route('admin.events.edit', $event->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.events.destroy', $event->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this event?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection

@push('footer_scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function(){

            $('#eventsTable').DataTable({
                columnDefs: [
                    {
                        orderable: false, targets: 7
                    },
                    {
                        searchable: false, targets: [0,4,5,7]
                    },
                ],
                pageLength: 10,
                responsive: true
            });

        });

    </script>

@endpush
