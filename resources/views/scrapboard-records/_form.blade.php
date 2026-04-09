@php
    /** @var \App\Models\ScrapboardRecord|null $record */
    $record = $record ?? null;
@endphp

<div class="row g-3">
    <div class="col-12">
        <label for="code" class="form-label">Scrapboard Code <span class="text-danger">*</span></label>
        <input
            type="text"
            class="form-control @error('code') is-invalid @enderror"
            id="code"
            name="code"
            value="{{ old('code', $record?->code) }}"
            placeholder="Ex: IP13-A3-0045"
            maxlength="255"
            required
        >
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="classification" class="form-label">Classification <span class="text-danger">*</span></label>
        <select class="form-select @error('classification') is-invalid @enderror" id="classification" name="classification" required>
            <option value="">Select Classification</option>
            @foreach (['A1', 'A2', 'A3', 'A4', 'A5'] as $classification)
                <option value="{{ $classification }}" @selected(old('classification', $record?->classification) === $classification)>{{ $classification }}</option>
            @endforeach
        </select>
        @error('classification')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
