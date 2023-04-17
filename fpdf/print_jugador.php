<?php	
require('fpdf.php');
//require('rounded_rect2.php');
require('makefont/makefont.php');
require_once("../../../../wp-load.php");
$post = get_post($_GET['id']);
$image = get_post_meta($post->ID, '_anwpfl_photo', true);
// $pdf = new FPDF($orientation='P',$unit='mm');
$pdf = new FPDF();
$pdf->AddPage();

$textypos = 5;
// $pdf->setY(10);
// $pdf->setX(10);

//carnet
$pdf->Cell(100, 70, "", 1, 1, 'C');

//image
$pdf->Image($image, 12, 22, -750);

//image2
$pdf->Image("logo.jpg", 86, 56, -890);

// categoria
$pdf->SetFont('Arial','B',10);    
$pdf->setY(12);$pdf->setX(20);
$pdf->Cell(5,$textypos, "SENIOR");
$pdf->SetFont('Arial','',9);    
$pdf->setY(17);$pdf->setX(18);
$pdf->Cell(5,$textypos,utf8_decode("47 a 49 Años"));

// titulo
$pdf->SetFont('Arial','B',16);    
$pdf->setY(10);$pdf->setX(45);
$pdf->Cell(10,10,"CARNET DE JUGADOR");


// jugador
$pdf->SetFont('Arial','B',10);    
$pdf->setY(22);$pdf->setX(45);
$pdf->Cell(5,$textypos,$post->post_title);
$pdf->SetFont('Arial','',9);    
$pdf->setY(25);$pdf->setX(60);
$pdf->Cell(5,$textypos,"jugador");

// Club
$club = get_post(get_post_meta($post->ID, '_anwpfl_current_club', true));
$pdf->SetFont('Arial','B',10);    
$pdf->setY(32);$pdf->setX(45);
$pdf->Cell(5,$textypos,$club->post_title);
$pdf->SetFont('Arial','',9);    
$pdf->setY(35);$pdf->setX(60);
$pdf->Cell(5,$textypos,"club actual");
 
// Fecha de nacimiento
$fecha = get_post_meta($post->ID, '_anwpfl_date_of_birth', true);  
$pdf->SetFont('Arial','B',10);    
$pdf->setY(43);$pdf->setX(45);
$pdf->Cell(5,$textypos,$fecha." ".utf8_decode("con 52 años"));
$pdf->SetFont('Arial','',9);    
$pdf->setY(46);$pdf->setX(60);
$pdf->Cell(5,$textypos,"nacimiento y edad");

// Fecha de Registro
$pdf->SetFont('Arial','B',10);    
$pdf->setY(53);$pdf->setX(45);
$pdf->Cell(5,$textypos,"Trinidad ".date("Y/m/d h:i:sa"));
$pdf->SetFont('Arial','',9);    
$pdf->setY(56);$pdf->setX(60);
$pdf->Cell(5,$textypos,"fecha de registro");

// FIRMAS
$pdf->SetFont('Arial','B',8);    
$pdf->setY(75);$pdf->setX(15);
$pdf->Cell(5,5,"INTERESADO");

$pdf->SetFont('Arial','B',8);    
$pdf->setY(75);$pdf->setX(45);
$pdf->Cell(5,5,"PRESIDENTE");

$pdf->SetFont('Arial','B',8);    
$pdf->setY(75);$pdf->setX(75);
$pdf->Cell(5,5,"COMITE TECNICO");


$pdf->output();


?>