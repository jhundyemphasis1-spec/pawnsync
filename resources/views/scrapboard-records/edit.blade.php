@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <section class="sr-card sr-card-soft p-4 mb-4 sr-data-card" style="--sr-delay: 40ms;">
                <p class="sr-muted mb-2 text-uppercase small fw-semibold">Record Maintenance</p>
                <h1 class="sr-page-title mb-1">Edit Scrapboard Code</h1>
                <p class="sr-muted mb-0">Update the code and its classification, then save your changes.</p>
            </section>

            <div class="sr-card p-3 p-md-4 sr-data-card" style="--sr-delay: 100ms;">
                <form action="{{ route('admin.scrapboard-records.update', $scrapboardRecord) }}" method="POST" class="sr-form-shell">
                        @csrf
                        @method('PUT')
                        @include('scrapboard-records._form', ['record' => $scrapboardRecord])

                        <div class="mt-3 d-flex flex-column flex-md-row gap-2">
                            <button class="btn btn-sr-primary px-4" type="submit">
                                <i class="bi bi-check2-circle me-1"></i> Update Record
                            </button>
                            <a href="{{ route('admin.scrapboard-records.index') }}" class="btn btn-sr-subtle px-4">Back to Dashboard</a>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
