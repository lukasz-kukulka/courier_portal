<?php

require_once 'tcpdf/tcpdf.php' ;
require_once 'sema_single_page_pdf.php' ;

class PDFGenerator {
  public function __construct( $post ) {
    
    $this->post_date = $post;
    $this->pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
    $this->setDefaultSettings();
    
  }

  public function generatePDFDocument() {
    $this->pdf->Output('hello_world.pdf', 'I');
  }

  private function setDefaultSettings() {
    // form post data of type
    $this->setPages('cn22');
  }

  private function setPages( $form_type ) {
    $bMargin = $this->pdf->getBreakMargin();
    $auto_page_break = $this->pdf->getAutoPageBreak();
    $this->pdf->SetAutoPageBreak(false, 0);
    
    $this->createPages();
    $this->pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    $this->pdf->setPageMark();
    $this->setAllPageContetntToPrint();
  }

  private function createPages() {
    if ( ($_POST['form_type'] == 'cn22' ) ) {
      $this->pages = array(
        new SingePagePDF( $this->pdf, 'Files/cn22_p1.jpg', "settings/cn22_print_setting.json" ),
        //new SingePagePDF( $this->pdf, 'Files/cn22_p2.jpg' )
      );
    } else if ( ($_POST['form_type'] == 'cn23' ) ) { 
      $this->pages = array(
        new SingePagePDF( $this->pdf, 'Files/cn23_p1.jpg', "settings/cn23_print_setting.json" ),
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
  private $pdf;
}

//var_dump( $_POST );

$testPDF = new PDFGenerator( $_POST );
$testPDF->generatePDFDocument();

      



