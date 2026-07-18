<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Undo manual payment verification schema added earlier.
     */
    public function up(): void
    {
        Schema::dropIfExists('payment_activities');
        Schema::dropIfExists('payment_proofs');

        if (Schema::hasTable('payments')) {
            // Restore legacy status labels
            DB::table('payments')->where('status', 'Pending Verification')->update(['status' => 'Pending']);
            DB::table('payments')->where('status', 'Verified')->update(['status' => 'Approved']);
            DB::table('payments')->where('status', 'Re-upload Required')->update(['status' => 'Pending']);

            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'verified_by')) {
                    $table->dropConstrainedForeignId('verified_by');
                }
                foreach ([
                    'sender_name',
                    'sender_number',
                    'paid_at',
                    'rejection_reason',
                    'admin_message',
                    'verified_at',
                    'stock_deducted',
                ] as $col) {
                    if (Schema::hasColumn('payments', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        // Restore order statuses that were introduced for verification flow
        if (Schema::hasTable('orders')) {
            DB::table('orders')->where('status', 'Pending Payment Verification')->update(['status' => 'Pending']);
            DB::table('orders')->where('status', 'Payment Rejected')->update(['status' => 'Cancelled']);
        }
    }

    public function down(): void
    {
        // Irreversible undo migration
    }
};
