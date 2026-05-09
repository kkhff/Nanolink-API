<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\UrlClick;

class ProcessUrlClick implements ShouldQueue
{
    use Queueable;

    public $urlId;
    public $ipAddress;
    public $userAgent;

    /**
     * Create a new job instance.
     */
    public function __construct($urlId, $ipAddress, $userAgent)
    {
        $this->urlId = $urlId;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UrlClick::create([
            'url_id' => $this->urlId,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ]);
    }
}
