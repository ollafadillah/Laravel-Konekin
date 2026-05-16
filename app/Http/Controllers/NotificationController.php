<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private array $paymentNotificationTypes = [
        'App\Notifications\PaymentInvoiceCreated',
        'App\Notifications\PaymentApproved',
        'App\Notifications\PaymentRejected',
        'App\Notifications\PaymentApprovedToCreative',
        'App\Notifications\PaymentRejectedToCreative',
    ];

    public function destroyAll(Request $request)
    {
        $user = $request->user();
        $notificationIds = collect($request->input('notification_ids', []))
            ->map(fn ($id) => (string) $id)
            ->filter()
            ->values();

        DB::connection('mongodb')
            ->getCollection('notifications')
            ->deleteMany([
                'notifiable_id' => (string) $user->getKey(),
                'type' => ['$in' => $this->paymentNotificationTypes],
            ]);

        if ($notificationIds->isNotEmpty()) {
            $dismissedIds = collect($user->dismissed_notification_ids ?? [])
                ->merge($notificationIds)
                ->unique()
                ->values()
                ->all();

            $user->dismissed_notification_ids = $dismissedIds;
            $user->save();
        }

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
