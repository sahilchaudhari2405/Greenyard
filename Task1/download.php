<?php
session_start();
if (isset($_SESSION['loggedUserName'])) {
    $loggedUser = $_SESSION['loggedUserName'];
}


include 'config.php';
$connectionString = "host=" . $config['DB_HOST'] . " port =5432 dbname=" . $config['DB_DATABASE'] . " user=" . $config['DB_USERNAME'] . " password=" . $config['DB_PASSWORD'];
$conn = pg_connect($connectionString);

if (!$conn) {
    echo 'something went wrong!';
    exit();
}


function createmyPdf()
{
    global $conn;
    $imgId = $_SESSION['imgId'];
    $query = "Select * from ImageData where imageId='$imgId'";
    $result = pg_query($conn, $query);
    $row = pg_fetch_all($result);
    for ($i = 0; $i < count($row); $i++) {
        $imageTitle = $row[$i]['imagetitle'];
        $imageDescription = $row[$i]['imagedescription'];
        $imgSrc = $row[$i]['imagename'];
        $imageAuthor = $row[$i]['imageauthor'];
    }
    require('fpdf/fpdf.php');

    // New object created and constructor invoked
    $pdf = new FPDF();

    // Add new pages. By default no pages available.
    $pdf->AddPage();

    // Set font format and font-size
    $pdf->SetFont('Times', 'B', 20);

    // Framed rectangular area
    $pdf->Cell(176, 5, 'GreenVerz', 0, 0, 'C');

    // Set it new line
    $pdf->Ln();
    $pdf->Image('images/logo.png', 30, 8, 20);
    $pdf->Image('images/logo.png', 160, 8, 20);

    // Set font format and font-size
    $pdf->SetFont('Times', 'B', 12);

    // Framed rectangular area
    $pdf->Cell(176, 10, 'Exploring the Real Nature!', 0, 1, 'C');

    $pdf->SetFont('Times', 'B', 22);
    $pdf->Image('uploads/' . $imgSrc, 60, 40, 100, 100);
    $pdf->SetXY(100, 150);
    $pdf->Cell(10, 0, $imageTitle, 0, 10, 'C');

    $pdf->SetFont('Times', 'B', 16);
    $pdf->SetXY(85, 160);
    $pdf->Cell(50, 0, 'Author: ' . $imageAuthor, 0, 1, 'C');

    $pdf->SetXY(0, 170);
    $pdf->Cell(50, 0, 'Description:', 0, 1, 'C');

    $pdf->SetFont('Times', '', 10);
    $pdf->SetXY(10, 175);
    $pdf->Write(10, $imageDescription);

    // Close document and sent to the browser
    $pdf->Output($imageTitle.'.pdf', 'D');
    // $pdf->Output('Myfile.pdf','D');


}
createmyPdf();
?>