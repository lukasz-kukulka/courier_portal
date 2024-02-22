<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\UserAnnouncement;
use App\Models\UserAnnouncementArchive;
use App\Models\AnimalAnnouncementArchive;
use App\Models\HumanAnnouncementArchive;
use App\Models\OtherAnnouncementArchive;
use App\Models\PalletAnnouncementArchive;
use App\Models\ParcelAnnouncementArchive;
use Illuminate\Support\Facades\Log;

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
    public function handle() {
        $this->actionsForUserAnnouncement();
        $this->actionsForUserAnnouncementArchive();

    }

    private function actionsForUserAnnouncement() {
        $expiredPosts = UserAnnouncement::with(
            'parcelAnnouncement',
            'humanAnnouncement',
            'palletAnnouncement',
            'animalAnnouncement',
            'otherAnnouncement'
        )->where('experience_date', '<', now())->get();
        foreach ($expiredPosts as $post) {
            $newArchivePost = new UserAnnouncementArchive ( $post->getAttributes() );
            $newArchivePost->authorUser()->associate( $post[ 'author' ] );
            $newArchivePost->save();
            $this->storeAllCargoArchive( $post );
            $post->delete();
        }
    }

    private function actionsForUserAnnouncementArchive() {
        $archivePosts = UserAnnouncementArchive::with(
            'parcelAnnouncement',
            'humanAnnouncement',
            'palletAnnouncement',
            'animalAnnouncement',
            'otherAnnouncement'
        )->where('experience_date', '<', now()->subDays(31) )->get();
        foreach ($archivePosts as $post) {
            $post->delete();
        }
    }

    private function storeAllCargoArchive( $data ) {
        if ( isset( $data[ 'parcelAnnouncement' ] ) ) {
            $this->storeParcelDataArchive( $data[ 'parcelAnnouncement' ], $data[ 'id' ] );
        }
        if ( $data[ 'humanAnnouncement' ] != null ) {
            $this->storeHumanDataArchive( $data[ 'humanAnnouncement' ], $data[ 'id' ] );
        }
        if ( $data[ 'palletAnnouncement' ] != null ) {
            $this->storePalletDataArchive( $data[ 'palletAnnouncement' ], $data[ 'id' ] );
        }
        if ( $data[ 'animalAnnouncement' ] != null ) {
            $this->storeAnimalDataArchive( $data[ 'animalAnnouncement' ], $data[ 'id' ] );
        }
        if ( $data[ 'otherAnnouncement' ] != null ) {
            $this->storeOtherDataArchive( $data[ 'otherAnnouncement' ], $data[ 'id' ] );
        }
    }

    private function storeAnimalDataArchive ( $data, $announcement_id  ) {
        foreach ( $data as $element ) {
            $animal = new AnimalAnnouncementArchive ( $element->getAttributes() );
            $animal->announcementId()->associate( $announcement_id  );
            $animal->save();
        }
    }

    private function storeHumanDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $human = new HumanAnnouncementArchive ( $element->getAttributes() );
            $human->announcementId()->associate( $announcement_id  );
            $human->save();
        }
    }

    private function storeOtherDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $other = new OtherAnnouncementArchive ( $element->getAttributes() );
            $other->announcementId()->associate( $announcement_id  );
            $other->save();
        };
    }

    private function storePalletDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $pallet = new PalletAnnouncementArchive ( $element->getAttributes() );
            $pallet->announcementId()->associate( $announcement_id  );
            $pallet->save();
        }
    }

    private function storeParcelDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $parcel = new ParcelAnnouncementArchive ( $element->getAttributes() );
            $parcel->announcementId()->associate( $announcement_id  );
            $parcel->save();
        }
    }

    private $experience_days;
}