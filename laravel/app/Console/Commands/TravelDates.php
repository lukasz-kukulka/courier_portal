<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourierTravelDate;

class TravelDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:travel-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old travel dates from database';

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
