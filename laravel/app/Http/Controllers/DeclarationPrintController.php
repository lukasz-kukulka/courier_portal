<?php

namespace App\Http\Controllers;
use TCPDF;
use Illuminate\Http\Request;
use App\Models\Post;

class SingePagePDF {
    public function __construct( &$form, $background, $json_file = '' ) {
        $this->pdf = $form;
        if ( !is_null( $json_file ) ) {
          $this->json = file_get_contents( $json_file );
        }
        $this->pdf->AddPage();
        $this->pdf->Image( $background, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

      }

    public function setAllTextToPrint( $post ) {
        if ( !is_null( $this->json ) ) {
            $this->json_decode = json_decode( $this->json );
            $this->setPersonalDataToPrintSender( $post );
            if ( $post['form_type'] == 'cn23' ) {
                $this->setPersonalDataToPrintReceiver( $post );
            }
            $this->setAllItemsToPrint( $post );
            $this->setAllCheckboxesToPrint( $post );
            $this->setSummarySection( $post );
            $this->setBusinessSection( $post );
            $this->pdf->Rotate( 0 );
        }
    }

    private function rotateTextIfAngleMoreThanZero( $angle, $x, $y, $text ) {
        if ( $angle > 0 ) {
            $this->pdf->startTransform();
            $this->pdf->Rotate( $angle, $x + 5, $y );
            $this->pdf->Text( $x, $y, $text );
            $this->pdf->stopTransform();
        } else {
            $this->pdf->Text( $x, $y, $text );
        }

    }

    private function setPersonalDataToPrintSender( $data ) {
        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_name[ 0 ], $this->json_decode->sender_name[ 1 ], $data[ "name_surname" ] );
        if ( $data['form_type'] == 'cn23' ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_business_name[ 0 ], $this->json_decode->sender_business_name[ 1 ], $data[ "business_name" ] );
        }
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_address[ 0 ], $this->json_decode->sender_address[ 1 ], $data[ "street" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_city[ 0 ], $this->json_decode->sender_city[ 1 ], $data[ "city" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_country[ 0 ], $this->json_decode->sender_country[ 1 ], $data[ "country" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->sender_post_code[ 0 ], $this->json_decode->sender_post_code[ 1 ], $data[ "post_code" ] );
    }

    private function setPersonalDataToPrintReceiver( $data ) {
        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_name[ 0 ], $this->json_decode->receiver_name[ 1 ], $data[ "receiver_name_surname" ] );
        if ( isset( $data[ "name_surname" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_business_name[ 0 ], $this->json_decode->receiver_business_name[ 1 ], $data[ "receiver_business_name" ] );
        }
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_address[ 0 ], $this->json_decode->receiver_address[ 1 ], $data[ "receiver_street" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_city[ 0 ], $this->json_decode->receiver_city[ 1 ], $data[ "receiver_city" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_country[ 0 ], $this->json_decode->receiver_country[ 1 ], $data[ "receiver_country" ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $this->json_decode->receiver_post_code[ 0 ], $this->json_decode->receiver_post_code[ 1 ], $data[ "receiver_post_code" ] );
    }

    private function setAllItemsToPrint( $data ) {
        for ( $i = 0; $i < $data[ "items_num_to_print" ]; $i++ ) {
            $this->setSingleItemToPrint( $data, $i );
        }
    }

    private function setSingleItemToPrint( $data, $index ) {
        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->parcel_items_font_size );
        $item_details = $this->json_decode->parcel_items[ $index ];
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $item_details->description[ 0 ], $item_details->description[ 1 ], $data["description_".($index + 1) ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $item_details->quantity[ 0 ], $item_details->quantity[ 1 ], $data["input_quantity_".($index + 1) ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $item_details->weight[ 0 ], $item_details->weight[ 1 ], $data["input_weight_".($index + 1) ] );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $item_details->value[ 0 ], $item_details->value[ 1 ], $data["input_value_".($index + 1) ] );
        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
    }

    private function setAllCheckboxesToPrint( $data ) {
        $parcel_details = $this->json_decode->parcel_details;
        $this->pdf->SetFont( $this->json_decode->font_name, '', $parcel_details->checkbox_font_size );

        if ( isset( $data[ "item_checkbox_1" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->gift[ 0 ], $parcel_details->gift[ 1 ], "X" );
        }
        if ( isset( $data[ "item_checkbox_2" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->documents[ 0 ], $parcel_details->documents[ 1 ], "X" );
        }
        if ( isset( $data[ "item_checkbox_3" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->selling_goods[ 0 ], $parcel_details->selling_goods[ 1 ], "X" );
        }
        if ( isset( $data[ "item_checkbox_4" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->sample[ 0 ], $parcel_details->sample[ 1 ], "X" );
        }
        if ( isset( $data[ "item_checkbox_5" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->returned_goods[ 0 ], $parcel_details->returned_goods[ 1 ], "X" );
        }
        if ( isset( $data[ "item_checkbox_6" ] ) ) {
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->others[ 0 ], $parcel_details->others[ 1 ], "X" );
            $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->others_description_font_size );
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_details->others[ 0 ], $parcel_details->others[ 1 ], $data[ "others_description" ] );
        }
        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
    }

    private function setSummarySection( $data ) {
        $parcel_summary = $this->json_decode->parcel_summary;

        $value_summary = $data["total_parcel_value"];
        $weight_summary = $data["total_parcel_weight"];

        if ( isset( $data[ "summary_checkbox" ] ) ) {
            $value_summary = $this->computeValueSummary( $data );
            $weight_summary = $this->computeWeightSummary( $data );
        }

        $this->pdf->SetFont( $this->json_decode->font_name, '', $parcel_summary->parcel_summary_font_size );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_summary->total_weight[ 0 ], $parcel_summary->total_weight[ 1 ], $weight_summary );
        $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $parcel_summary->total_value[ 0 ], $parcel_summary->total_value[ 1 ], $value_summary );

        $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
    }

    private function computeValueSummary( $data ) {
        $value_summary = null;

        for ( $i = 0; $i < $data["items_num_to_print"]; $i++ ) {
            $value_summary += $data[ "input_value_".( $i + 1 ) ];
        }

        return $value_summary;
    }

    private function computeWeightSummary( $data ) {
        $weight_summary = null;

        for ( $i = 0; $i < $data["items_num_to_print"]; $i++ ) {
            $weight_summary += $data[ "input_weight_".( $i + 1 ) ];
        }

        return $weight_summary;
    }

    private function setBusinessSection( $data ) {

        $info = $this->json_decode->business_section;
        if ( isset( $data[ "business_checkbox" ] ) ) {
            //var_dump( $this->json );
            $this->pdf->SetFont( $this->json_decode->font_name, '', $info->business_section_font_size );
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $info->vat_eori_num[ 0 ], $info->vat_eori_num[ 1 ], $data["vat_eori"] );
            $this->rotateTextIfAngleMoreThanZero( $this->json_decode->rotate_text, $info->tariff_num[ 0 ], $info->tariff_num[ 1 ], $data[ "tariff_num" ] );
            $this->pdf->SetFont( $this->json_decode->font_name, '', $this->json_decode->default_font_size );
        }

    }


    private $json;
    private $json_decode;
    private $pdf;
  }

class DeclarationPrintController extends Controller
{
    public function __construct( $post ) {
        $this->post_date = $post;
        $this->pdf_generator = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
        $this->setDefaultSettings();

      }

      public function generatePDFDocument() {
        $this->pdf_generator->Output('hello_world.pdf', 'I');
      }

      private function setDefaultSettings() {
        $this->setPages('cn22');
      }

      private function setPages( $form_type ) {
        $bMargin = $this->pdf_generator->getBreakMargin();
        $auto_page_break = $this->pdf_generator->getAutoPageBreak();
        $this->pdf_generator->SetAutoPageBreak(false, 0);

        $this->createPages();
        $this->pdf_generator->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->pdf_generator->setPageMark();
        $this->setAllPageContetntToPrint();
      }

      private function createPages() {
        $file_path = resource_path('files'. DIRECTORY_SEPARATOR );
        $setting_path = resource_path('settings'. DIRECTORY_SEPARATOR );

        if ( ($_POST['form_type'] == 'cn22' ) ) {
          $this->pages = array(
            new SingePagePDF( $this->pdf_generator, $file_path.'cn22_p1.jpg', $setting_path."cn22_print_setting.json" ),
            //new SingePagePDF( $this->pdf, 'Files/cn22_p2.jpg' )
          );

        } else if ( ($_POST['form_type'] == 'cn23' ) ) {
          $this->pages = array(
            new SingePagePDF( $this->pdf_generator, $file_path.'cn23_p1.jpg', $setting_path.'cn23_print_setting.json' ),
            //new SingePagePDF( $this->pdf, 'Files/cn23_p2.jpg' )
          );
        } else {
          echo"ERROR - WRONG FORM TYPE";
        }

      }

      private function setAllPageContetntToPrint() {
        foreach( $this->pages as $page ) {
          $page->setAllTextToPrint( $this->post_date );
        }
      }

    private $post_date;
    private $pages;
    private $pdf_generator;
}
