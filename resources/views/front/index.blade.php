@extends('front._layout')

@section('title', 'Choose events')

@push('header_styles')
    <style>
        body {
            background: #f6f7fb;
        }

        .page-title {
            letter-spacing: .2px;
        }

        .event-card {
            border: 0;
            border-radius: 16px;
        }

        .event-card .card-header {
            background: transparent;
            border-bottom: 0;
        }

        .badge-soft {
            background: rgba(13, 110, 253, .1);
            color: #0d6efd;
            border: 1px solid rgba(13, 110, 253, .2);
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .meta-item {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 12px;
            padding: .75rem .9rem;
        }

        .meta-label {
            font-size: .8rem;
            color: #6c757d;
            margin-bottom: .2rem;
        }

        .meta-value {
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .meta {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush('header_styles')

@section('content')
    @include('front._navbar')
    <main class="container my-4 my-md-5">

        <!-- Header -->
        <div class="mb-4">
            <div>
                <h1 class="h3 fw-bold page-title mb-1">Events</h1>
                <div class="text-secondary">Choose an event and buy tickets in a few clicks</div>
            </div>

        </div>

        <!-- Events grid -->
        <div class="row g-4">

            @foreach($events as $event)
                <div class="col-12 col-md-6 col-lg-4 data-type={{$event->type}}">
                    <div class="card event-card shadow-sm h-100">
                        <div class="card-header pt-4 px-4">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="badge badge-soft rounded-pill mb-2">{{$event->type}}</div>
                                    <h2 class="h5 fw-bold mb-0">{{$event->name}}</h2>
                                </div>
                                <span class="badge text-bg-success rounded-pill">{{$event->status}}</span>
                            </div>
                        </div>

                        <div class="card-body px-4">
                            <div class="meta">
                                <div class="meta-item">
                                    <div class="meta-label">Hall</div>
                                    <div class="meta-value">{{$event->hall->name}}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Type</div>
                                    <div class="meta-value">{{$event->type}}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Starts at</div>
                                    <div class="meta-value">{{$event->starts_at->format('d.m.Y H:i')}}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Ends at</div>
                                    <div class="meta-value">{{$event->ends_at->format('d.m.Y H:i')}}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-4 pb-4">
                            <div class="d-flex gap-2">
                                <a class="btn btn-primary w-100" href="{{route('event.show', $event->id)}}">
                                    Buy tickets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{$events->links()}}


        </div>

        <!-- Footer -->
        <div class="text-center text-secondary mt-5">
            © <span id="year"></span> SeatBooker
        </div>
    </main>
@endsection

@push('footer_scripts')
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
@endpush
