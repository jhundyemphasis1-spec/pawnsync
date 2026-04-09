<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scrapboard_records', function (Blueprint $table) {
            if (! Schema::hasColumn('scrapboard_records', 'code')) {
                $table->string('code')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('scrapboard_records', 'classification')) {
                $table->enum('classification', ['A1', 'A2', 'A3', 'A4', 'A5'])->default('A1')->after('code');
            }
        });

        if (Schema::hasColumn('scrapboard_records', 'motherboard_code')) {
            DB::table('scrapboard_records')
                ->whereNull('code')
                ->update([
                    'code' => DB::raw('motherboard_code'),
                ]);
        }

        DB::table('scrapboard_records')
            ->select('id')
            ->whereNull('code')
            ->orWhere('code', '')
            ->orderBy('id')
            ->get()
            ->each(function ($record): void {
                DB::table('scrapboard_records')
                    ->where('id', $record->id)
                    ->update([
                        'code' => 'CODE-'.$record->id,
                    ]);
            });

        DB::table('scrapboard_records')
            ->whereNull('classification')
            ->update([
                'classification' => 'A1',
            ]);

        $columnsToDrop = [];

        foreach (['platform', 'motherboard_code', 'device_model', 'remarks'] as $column) {
            if (Schema::hasColumn('scrapboard_records', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop !== []) {
            Schema::table('scrapboard_records', function (Blueprint $table) use ($columnsToDrop): void {
                $table->dropColumn($columnsToDrop);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scrapboard_records', function (Blueprint $table) {
            if (! Schema::hasColumn('scrapboard_records', 'platform')) {
                $table->enum('platform', ['android', 'ios'])->default('android')->after('id');
            }

            if (! Schema::hasColumn('scrapboard_records', 'motherboard_code')) {
                $table->string('motherboard_code')->nullable()->after('platform');
            }

            if (! Schema::hasColumn('scrapboard_records', 'device_model')) {
                $table->string('device_model')->nullable()->after('motherboard_code');
            }

            if (! Schema::hasColumn('scrapboard_records', 'remarks')) {
                $table->text('remarks')->nullable()->after('device_model');
            }
        });

        if (Schema::hasColumn('scrapboard_records', 'code')) {
            DB::table('scrapboard_records')
                ->whereNull('motherboard_code')
                ->update([
                    'motherboard_code' => DB::raw('code'),
                ]);
        }

        $columnsToDrop = [];

        foreach (['classification', 'code'] as $column) {
            if (Schema::hasColumn('scrapboard_records', $column)) {
                $columnsToDrop[] = $column;
            }
        }

        if ($columnsToDrop !== []) {
            Schema::table('scrapboard_records', function (Blueprint $table) use ($columnsToDrop): void {
                $table->dropColumn($columnsToDrop);
            });
        }
    }
};
