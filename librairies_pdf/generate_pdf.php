<?php
// 1. Activer l'affichage des erreurs pour le suivi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Charger l'autoloader de Composer
require_once 'vendor/autoload.php';

// 3. Déclarer les namespaces TOUT EN HAUT (hors de tout bloc)
use Dompdf\Dompdf;
use Dompdf\Options;

// 4. Configurer et initialiser Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// 5. Charger le design du fichier template
ob_start();
include 'invoice_template.php';
$html = ob_get_clean();

$dompdf->loadHtml($html);

// 6. Format de la page (A4, portrait)
$dompdf->setPaper('A4', 'portrait');

// 7. Générer le PDF
$dompdf->render();

// 8. Télécharger automatiquement le fichier PDF
$dompdf->stream("ma_premiere_facture.pdf", ["Attachment" => true]);