<?php

namespace App\Http\Controllers;

use App\Models\ScrapboardRecord;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicScrapboardRecordController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $query = ScrapboardRecord::query();

        if ($request->filled('q')) {
            $searchTerm = (string) $request->string('q');
            $query->where('code', 'like', "%{$searchTerm}%");
        }

        $records = $query
            ->orderBy('code')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            $table = view('public.partials.records-table', compact('records'))->render();

            return response()->json([
                'table' => $table,
                'total' => $records->total(),
            ]);
        }

        return view('public.index', compact('records'));
    }
}
