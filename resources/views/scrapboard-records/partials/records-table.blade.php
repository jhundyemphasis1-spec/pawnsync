<div class="table-responsive">
    <table class="table sr-table align-middle mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Classification</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr
                    data-record-id="{{ $record->id }}"
                    data-record-code="{{ $record->code }}"
                    data-record-classification="{{ $record->classification }}"
                >
                    <td>{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                    <td class="sr-record-code">{{ $record->code }}</td>
                    <td>
                        <span class="sr-pill sr-class-pill sr-class-{{ strtolower($record->classification) }}">
                            {{ $record->classification }}
                        </span>
                    </td>
                    <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="{{ route('admin.scrapboard-records.edit', $record) }}"
                                class="btn btn-sm btn-sr-subtle"
                                data-edit-record
                            >
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form
                                action="{{ route('admin.scrapboard-records.destroy', $record) }}"
                                method="POST"
                                class="d-inline"
                                data-delete-form
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn btn-sm btn-sr-danger"
                                    data-delete-button
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center sr-empty">
                        <i class="bi bi-inboxes fs-4 d-block mb-2"></i>
                        No codes found.
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
