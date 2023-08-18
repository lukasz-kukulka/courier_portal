<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CourierAnnouncementLimitTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:courier-announcement-limit-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() {
        $expiredPosts = CourierTravelDate::where('experience_date', '<', now())->get();
        foreach ($expiredPosts as $post) {
            $post->delete();
        }
    }
}
