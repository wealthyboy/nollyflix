<?php

namespace App\Jobs;

use App\PushCampaign;
use App\PushDevice;
use App\Services\FirebasePushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendPushCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 600;
    protected $campaignId;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    public function handle(FirebasePushService $firebase)
    {
        $campaign = PushCampaign::findOrFail($this->campaignId);
        $campaign->update(['status' => 'sending', 'failure_message' => null]);

        $devices = PushDevice::query()
            ->where('active', true)
            ->whereHas('user', function ($query) {
                $query->where('allow_notifications', true);
            })
            ->get();

        $campaign->update(['recipients_count' => $devices->count()]);
        $sent = 0;
        $failed = 0;
        $lastError = null;

        foreach ($devices as $device) {
            try {
                $firebase->send($device->token, $campaign->title, $campaign->body, $campaign->data ?: []);
                $sent++;
            } catch (Throwable $exception) {
                $failed++;
                $lastError = $exception->getMessage();
                if ($firebase->isInvalidTokenError($exception)) {
                    $device->update(['active' => false]);
                }
                report($exception);
            }
        }

        $campaign->update([
            'status' => $devices->count() > 0 && $sent === 0 ? 'failed' : 'completed',
            'sent_count' => $sent,
            'failed_count' => $failed,
            'failure_message' => $lastError ? substr($lastError, 0, 2000) : null,
            'sent_at' => now(),
        ]);
    }
}
