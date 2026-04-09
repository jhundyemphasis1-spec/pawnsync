<div class="table-responsive">
    <table class="table sr-table align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Classification</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                    <td class="sr-record-code">{{ $record->code }}</td>
                    <td>
                        <span class="sr-pill sr-class-pill sr-class-{{ strtolower($record->classification) }}">
                            {{ $record->classification }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center sr-empty">
                        <i class="bi bi-search fs-4 d-block mb-2"></i>
                        No code found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($records->hasPages())
    <div class="mt-3 sr-pagination-wrap">
        {{ $records->links() }}
    </div>
@endif
