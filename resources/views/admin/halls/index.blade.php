@extends('layouts.app')

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Halls</h2>
            <a href="{{ route('admin.halls.create') }}" class="btn btn-primary">Create New Hall</a>
        </div>

        <div class="card shadow-sm p-3">
            <table id="hallsTable" class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Rows</th>
                    <th>Columns</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($halls as $hall)
                    <tr>
                        <td>{{ $hall->name }}</td>
                        <td>{{ $hall->rows }}</td>
                        <td>{{ $hall->columns }}</td>
                        <td>
                        <span class="badge {{ $hall->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $hall->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.halls.edit', $hall->id) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.halls.destroy', $hall->id) }}" class="d-inline" onsubmit="return confirm('Delete this hall?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
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
            $('#hallsTable').DataTable({
                columnDefs: [
                    {
                        orderable: false, targets: 4
                    },
                    {
                        searchable: false, targets: 4
                    },
                ],

                pageLength: 10,
                responsive: true,
            });
        });
    </script>
@endpush
