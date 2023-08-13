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
        $expiredPosts = UserAnnouncement::with(
            'parcelAnnouncement',
            'humanAnnouncement',
            'palletAnnouncement',
            'animalAnnouncement',
            'otherAnnouncement'
        )->where('experience_date', '>', now())->get();
        foreach ($expiredPosts as $post) {
            $newArchivePost = new UserAnnouncementArchive ( [
                'direction' =>                      $post[ 'direction' ],
                'author' =>                         $post[ 'author' ],
                'post_code_sending' =>              $post[ 'post_code_sending' ],
                'post_code_receiving' =>            $post[ 'post_code_receiving' ],
                'phone_number' =>                   $post[ 'phone_number' ],
                'email' =>                          $post[ 'email' ],
                'expect_sending_date' =>            $post[ 'expect_sending_date' ],
                'experience_date' =>                $post[ 'experience_date' ],
                'title' =>                          $post[ 'title' ],
                'order_description_short' =>        $post[ 'order_description_short' ],
                'order_description_long' =>         $post[ 'order_description_long' ],
                'parcels_quantity' =>               $post[ 'parcels_quantity' ],
                'humans_quantity' =>                $post[ 'humans_quantity' ],
                'pallets_quantity' =>               $post[ 'pallets_quantity' ],
                'animals_quantity' =>               $post[ 'animals_quantity' ],
                'others_quantity' =>                $post[ 'others_quantity' ],
            ] );
            $newArchivePost->authorUser()->associate( $post[ 'author' ] );
            $newArchivePost->save();
            $this->storeAllCargoArchive( $post );
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
            $animal = new AnimalAnnouncementArchive ( [
                'announcement_id' =>                      $element[ 'announcement_id' ],
                'animal_type' =>                          $element[ 'animal_type' ],
                'weight' =>                               $element[ 'weight' ],
                'animal_description' =>                   $element[ 'animal_description' ],
            ] );
            $animal->announcementId()->associate( $announcement_id  );
            $animal->save();
        }
    }

    private function storeHumanDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $human = new HumanAnnouncementArchive ( [
                'announcement_id' =>                      $element[ 'announcement_id' ],
                'adult' =>                                $element[ 'adult' ],
                'kids' =>                                 $element[ 'kids' ],
            ] );
            $human->announcementId()->associate( $announcement_id  );
            $human->save();
        }
    }

    private function storeOtherDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $other = new OtherAnnouncementArchive ( [
                'announcement_id' =>                      $element[ 'announcement_id' ],
                'description' =>                          $element[ 'description' ],
            ] );
            $other->announcementId()->associate( $announcement_id  );
            $other->save();
        };
    }

    private function storePalletDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $pallet = new PalletAnnouncementArchive ( [
                'announcement_id' =>                        $element[ 'announcement_id' ],
                'weight' =>                                 $element[ 'weight' ],
                'length' =>                                 $element[ 'length' ],
                'width' =>                                  $element[ 'width' ],
                'height' =>                                 $element[ 'height' ],
            ] );
            $pallet->announcementId()->associate( $announcement_id  );
            $pallet->save();
        }
    }

    private function storeParcelDataArchive ( $data, $announcement_id ) {
        foreach ( $data as $element ) {
            $parcel = new ParcelAnnouncementArchive ( [
                'announcement_id' =>                        $element[ 'announcement_id' ],
                'weight' =>                                 $element[ 'weight' ],
                'length' =>                                 $element[ 'length' ],
                'width' =>                                  $element[ 'width' ],
                'height' =>                                 $element[ 'height' ],
            ] );
            $parcel->announcementId()->associate( $announcement_id  );
            $parcel->save();
        }
    }

    private $experience_days;
}
