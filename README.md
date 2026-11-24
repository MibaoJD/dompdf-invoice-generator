# 📄 Automatisation de Factures PDF en PHP 8 avec Dompdf

Démonstration de génération dynamique de factures PDF (encodage UTF-8, mise en page A4 et séparation du modèle HTML/CSS via `librairies_pdf/invoice_template.php`) conçue selon les standards **JDM — PHP Rescue & Shield**.

## ✨ Fonctionnalités clés
* **Séparation propre du template :** Chargement du modèle de facture depuis `librairies_pdf/invoice_template.php` (avec modèle de secours intégré).
* **Support complet de l'UTF-8 :** Configuration de la police `DejaVu Sans` pour éviter les caractères cassés (`€`, accents, symboles).
* **Calcul automatique :** Calcul propre des montants et totaux en PHP 8 (`strict_types=1`).

## 📂 Structure du dépôt
* `generate_invoice.php` : Script principal d'assemblage des données et de génération du flux PDF.
* `librairies_pdf/invoice_template.php` : Modèle HTML/CSS de la facture.
* `.gitignore` : Exclusion des fichiers internes lourds de la bibliothèque pour garder le dépôt léger.

---
🔗 **Besoin d'intégrer ou de réparer vos exports PDF en PHP ?**  
Retrouvez mon service complet sur ComeUp : [JDM — PHP Rescue & Shield](https://comeup.com/fr/@jdm-php-rescue)