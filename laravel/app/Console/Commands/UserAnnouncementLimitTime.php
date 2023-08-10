<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserAnnouncement;
use App\Models\AnimalAnnouncement;
use App\Models\HumanAnnouncement;
use App\Models\OtherAnnouncement;
use App\Models\PalletAnnouncement;
use App\Models\ParcelAnnouncement;
class UserAnnouncementLimitTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-announcement-limit-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user announcement when experience date finish';

    /**
     * Execute the console command.
     */
    // public function __construct( ) {
    //     $this->experience_days = app(\App\Http\Controllers\JsonParserController::class)->searchAnnouncementAction()[ 'days_of_experience_in_archive' ];

    // }
    public function handle()
    {
        $expiredPosts = UserAnnouncement::with(
            'parcelAnnouncement',
            'humanAnnouncement',
            'palletAnnouncement',
            'animalAnnouncement',
            'otherAnnouncement',
            )->where('experience_date', '<', now())->get();

        foreach ($expiredPosts as $post ) {

        }
    }

    private $experience_days;
}
