<?php

namespace App\Http\Controllers;

use App\Models\ScrapboardRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScrapboardRecordController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $query = ScrapboardRecord::query();

        if ($request->filled('q')) {
            $searchTerm = (string) $request->string('q');
            $query->where('code', 'like', "%{$searchTerm}%");
        }

        if ($request->filled('classification')) {
            $query->where('classification', (string) $request->string('classification'));
        }

        $records = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => ScrapboardRecord::count(),
            'a1' => ScrapboardRecord::where('classification', 'A1')->count(),
            'a2' => ScrapboardRecord::where('classification', 'A2')->count(),
            'a3' => ScrapboardRecord::where('classification', 'A3')->count(),
            'a4' => ScrapboardRecord::where('classification', 'A4')->count(),
            'a5' => ScrapboardRecord::where('classification', 'A5')->count(),
            'today' => ScrapboardRecord::whereDate('created_at', now()->toDateString())->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('scrapboard-records.partials.records-table', compact('records'))->render(),
                'stats' => view('scrapboard-records.partials.stats', compact('stats'))->render(),
                'total' => $records->total(),
            ]);
        }

        return view('scrapboard-records.index', compact('records', 'stats'));
    }

    public function create()
    {
        return redirect()->route('admin.scrapboard-records.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:scrapboard_records,code'],
            'classification' => ['required', Rule::in(['A1', 'A2', 'A3', 'A4', 'A5'])],
        ]);

        ScrapboardRecord::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Scrapboard code saved successfully.',
            ]);
        }

        return redirect()
            ->route('admin.scrapboard-records.index')
            ->with('success', 'Scrapboard code saved successfully.');
    }

    public function show(ScrapboardRecord $scrapboardRecord)
    {
        return redirect()->route('admin.scrapboard-records.edit', $scrapboardRecord);
    }

    public function edit(ScrapboardRecord $scrapboardRecord): View
    {
        return view('scrapboard-records.edit', compact('scrapboardRecord'));
    }

    public function update(Request $request, ScrapboardRecord $scrapboardRecord)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('scrapboard_records', 'code')->ignore($scrapboardRecord->id),
            ],
            'classification' => ['required', Rule::in(['A1', 'A2', 'A3', 'A4', 'A5'])],
        ]);

        $scrapboardRecord->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Scrapboard code updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.scrapboard-records.index')
            ->with('success', 'Scrapboard code updated successfully.');
    }

    public function destroy(Request $request, ScrapboardRecord $scrapboardRecord)
    {
        $scrapboardRecord->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Scrapboard code deleted successfully.',
            ]);
        }

        return redirect()
            ->route('admin.scrapboard-records.index')
            ->with('success', 'Scrapboard code deleted successfully.');
    }
}
