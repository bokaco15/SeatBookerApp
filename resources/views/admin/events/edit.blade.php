@extends('layouts.app')

@section('content')

    <div class="container my-5">

        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Edit Event</h2>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                        ← Back
                    </a>
                </div>

                {{-- SUCCESS MESSAGE --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERRORS --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.update', $event->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- HALL --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hall</label>
                            <select name="hall_id" class="form-select form-select-lg" required>
                                <option value="">Select Hall</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall->id }}"
                                        {{ old('hall_id', $event->hall_id) == $hall->id ? 'selected' : '' }}>
                                        {{ $hall->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select form-select-lg" required>
                                <option value="">Select Type</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}"
                                        {{ old('type', $event->type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type->value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- NAME --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Event Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $event->name) }}"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        {{-- DATE --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date"
                                   name="date"
                                   value="{{ old('date', \Carbon\Carbon::parse($event->starts_at)->format('Y-m-d')) }}"
                                   class="form-control form-control-lg"
                                   required>
                        </div>

                        {{-- START TIME --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Start Time</label>
                            <select name="start_time" class="form-select form-select-lg" required>
                                <option value="">Select Time</option>

                                @for($h = 0; $h < 24; $h++)
                                    @foreach([0,30] as $m)

                                        @php
                                            $time = sprintf('%02d:%02d',$h,$m);
                                            $selected = old(
                                                'start_time',
                                                \Carbon\Carbon::parse($event->starts_at)->format('H:i')
                                            );
                                        @endphp

                                        <option value="{{ $time }}"
                                            {{ $selected == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>

                                    @endforeach
                                @endfor

                            </select>
                        </div>

                        {{-- END TIME --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">End Time</label>
                            <select name="end_time" class="form-select form-select-lg" required>
                                <option value="">Select Time</option>

                                @for($h = 0; $h < 24; $h++)
                                    @foreach([0,30] as $m)

                                        @php
                                            $time = sprintf('%02d:%02d',$h,$m);
                                            $selected = old(
                                                'end_time',
                                                \Carbon\Carbon::parse($event->ends_at)->format('H:i')
                                            );
                                        @endphp

                                        <option value="{{ $time }}"
                                            {{ $selected == $time ? 'selected' : '' }}>
                                            {{ $time }}
                                        </option>

                                    @endforeach
                                @endfor

                            </select>
                        </div>

                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            Update Event
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
