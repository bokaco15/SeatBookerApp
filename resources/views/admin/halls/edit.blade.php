@extends('layouts.app')

@section('content')

    <div class="container my-5">

        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Edit Hall</h2>

                    <a href="{{ route('admin.halls.index') }}" class="btn btn-outline-secondary">
                        ← Back
                    </a>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- VALIDATION ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.halls.update', $hall->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Hall Name --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hall Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $hall->name) }}"
                                class="form-control form-control-lg"
                                required
                            >
                        </div>

                        {{-- Rows --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Number of Rows</label>
                            <input
                                type="number"
                                name="rows"
                                value="{{ old('rows', $hall->rows) }}"
                                class="form-control form-control-lg"
                                min="1"
                                required
                            >
                        </div>

                        {{-- Columns --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Seats per Row</label>
                            <input
                                type="number"
                                name="columns"
                                value="{{ old('columns', $hall->columns) }}"
                                class="form-control form-control-lg"
                                min="1"
                                required
                            >
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            Update Hall
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
