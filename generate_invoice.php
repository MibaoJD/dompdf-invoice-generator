<?php
declare(strict_types=1);



// Chargement automatique de Dompdf depuis librairies_pdf
$autoloadPaths = [
    __DIR__ . '/librairies_pdf/vendor/autoload.php',
    __DIR__ . '/librairies_pdf/dompdf/autoload.inc.php',
    __DIR__ . '/librairies_pdf/autoload.inc.php',
];

$loaded = false;
foreach ($autoloadPaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    http_response_code(500);
    exit('Fichier autoload Dompdf introuvable dans librairies_pdf/. Vérifiez le chemin exact.');
}

use Dompdf\Dompdf;
use Dompdf\Options;

// Données de démonstration de la facture
$facture = [
    'numero'       => 'FAC-2026-0042',
    'date'         => date('d/m/Y'),
    'client_nom'   => 'Client Démo SARL',
    'client_email' => 'contact@client-demo.mg',
    'prestataire'  => 'JDM — PHP Rescue & Shield',
    'lignes'       => [
        [
            'designation' => 'Sécurisation PHP 8 & requêtes préparées PDO',
            'quantite'    => 1,
            'prix_unit'   => 120.00,
        ],
        [
            'designation' => 'Génération automatisée de factures PDF (Dompdf)',
            'quantite'    => 1,
            'prix_unit'   => 90.00,
        ],
    ],
];

$totalHT = 0.0;
foreach ($facture['lignes'] as $ligne) {
    $totalHT += (float) $ligne['quantite'] * (float) $ligne['prix_unit'];
}

// Chargement de librairies_pdf/invoice_template.php si présent, sinon modèle HTML de secours
$templatePath = __DIR__ . '/librairies_pdf/invoice_template.php';

ob_start();
if (file_exists($templatePath)) {
    require $templatePath;
} else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1f2937; }
            .entete { border-bottom: 2px solid #0f172a; padding-bottom: 10px; margin-bottom: 18px; }
            .titre { font-size: 20px; font-weight: bold; color: #0f172a; }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; }
            th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
            th { background-color: #f1f5f9; }
            .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 16px; }
        </style>
    </head>
    <body>
        <div class="entete">
            <div class="titre"><?= htmlspecialchars($facture['prestataire'], ENT_QUOTES, 'UTF-8') ?></div>
            <p>Facture N° <strong><?= htmlspecialchars($facture['numero'], ENT_QUOTES, 'UTF-8') ?></strong> — Date : <?= htmlspecialchars($facture['date'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <p><strong>Client :</strong> <?= htmlspecialchars($facture['client_nom'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($facture['client_email'], ENT_QUOTES, 'UTF-8') ?>)</p>
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Qté</th>
                    <th>Prix unitaire</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facture['lignes'] as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $ligne['designation'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= (int) $ligne['quantite'] ?></td>
                        <td><?= number_format((float) $ligne['prix_unit'], 2, ',', ' ') ?> €</td>
                        <td><?= number_format((float) $ligne['quantite'] * (float) $ligne['prix_unit'], 2, ',', ' ') ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="total">Total : <?= number_format($totalHT, 2, ',', ' ') ?> €</p>
    </body>
    </html>
    <?php
}
$html = (string) ob_get_clean();

// Génération du PDF
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isRemoteEnabled', false);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream($facture['numero'] . '.pdf', ['Attachment' => false]);