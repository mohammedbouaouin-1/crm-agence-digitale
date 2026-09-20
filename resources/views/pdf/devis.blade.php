<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Devis N° {{ $devis->numero }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            padding: 40px;
            background: #ffffff;
        }

        /* En-tête */
        .en-tete {
            display: table;
            width: 100%;
            margin-bottom: 35px;
            border-bottom: 2px solid #06b6d4;
            padding-bottom: 20px;
        }
        .en-tete-gauche, .en-tete-droite {
            display: table-cell;
            vertical-align: middle;
        }
        .en-tete-droite { text-align: right; }
        
        .logo-nom {
            font-size: 26px;
            font-weight: bold;
            color: #06b6d4;
            letter-spacing: -0.5px;
        }
        .logo-nom span {
            color: #0f172a;
        }
        .logo-sous-titre {
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .titre-devis {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .numero-devis {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Badge de statut */
        .statut-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 8px;
        }
        .statut-accepte { background: #dcfce7; color: #15803d; }
        .statut-envoye  { background: #e0f2fe; color: #0369a1; }
        .statut-brouillon { background: #f1f5f9; color: #475569; }
        .statut-refuse  { background: #fee2e2; color: #b91c1c; }
        .statut-expire  { background: #fef3c7; color: #b45309; }

        /* Deux colonnes : Agence & Client */
        .coordonnees {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .bloc-agence, .bloc-client {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .bloc-client {
            padding-left: 20px;
        }
        .bloc-titre {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .bloc-nom {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .bloc-texte {
            color: #475569;
            line-height: 1.6;
        }

        /* Dates */
        .bloc-dates {
            background: #f8fafc;
            border-left: 4px solid #06b6d4;
            padding: 12px 18px;
            margin-bottom: 30px;
            border-radius: 0 6px 6px 0;
            display: table;
            width: 100%;
        }
        .date-item {
            display: table-cell;
            width: 50%;
        }
        .date-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .date-valeur {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 2px;
        }

        /* Objet du devis */
        .objet-devis {
            margin-bottom: 25px;
            padding: 15px 18px;
            background: #f1f5f9;
            border-radius: 6px;
        }
        .objet-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
        }
        .objet-titre {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 4px;
        }

        /* Tableau des prestations */
        .tableau-prestations {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .tableau-prestations th {
            background: #0f172a;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 14px;
            text-align: left;
        }
        .tableau-prestations th.col-montant { text-align: right; }
        .tableau-prestations td {
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .tableau-prestations td.col-montant {
            text-align: right;
            font-weight: bold;
        }
        .prestation-titre {
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .prestation-desc {
            color: #64748b;
            line-height: 1.5;
            white-space: pre-line;
        }

        /* Totaux */
        .totaux-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .totaux-gauche {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        .totaux-droite {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .totaux-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totaux-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .totaux-table td.label {
            color: #64748b;
            font-weight: 500;
        }
        .totaux-table td.valeur {
            text-align: right;
            font-weight: bold;
            color: #0f172a;
        }
        .totaux-table tr.total-ttc td {
            background: #0f172a;
            color: #ffffff;
            font-size: 15px;
            padding: 12px;
            border: none;
        }
        .totaux-table tr.total-ttc td.valeur {
            color: #38bdf8;
        }

        /* Conditions & Signature */
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .conditions-bloc {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 25px;
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }
        .signature-bloc {
            display: table-cell;
            width: 45%;
            vertical-align: top;
            border: 1px dashed #cbd5e1;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
        }
        .signature-titre {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }
        .signature-mention {
            font-size: 10px;
            color: #94a3b8;
            font-style: italic;
            margin-bottom: 45px;
        }
        .signature-ligne {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 11px;
            color: #475569;
        }

        /* Pied de page */
        .pied-page {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <div class="en-tete">
        <div class="en-tete-gauche">
            <div class="logo-nom">Agence<span>Admin</span></div>
            <div class="logo-sous-titre">Agence Web & Marketing Digital</div>
        </div>
        <div class="en-tete-droite">
            <div class="titre-devis">DEVIS</div>
            <div class="numero-devis">N° {{ $devis->numero }}</div>
            <div>
                @php
                    $statutLabels = [
                        'accepte'   => 'Accepté',
                        'envoye'    => 'Envoyé',
                        'brouillon' => 'Brouillon',
                        'refuse'    => 'Refusé',
                        'expire'    => 'Expiré',
                    ];
                @endphp
                <span class="statut-badge statut-{{ $devis->statut }}">
                    {{ $statutLabels[$devis->statut] ?? $devis->statut }}
                </span>
            </div>
        </div>
    </div>

    <!-- Coordonnées Agence & Client -->
    <div class="coordonnees">
        <div class="bloc-agence">
            <div class="bloc-titre">Émetteur</div>
            <div class="bloc-nom">Agence Admin Digital</div>
            <div class="bloc-texte">
                Fès, Maroc<br>
                contact@agence.com<br>
                +212 5 35 00 00 00<br>
                RC : 00000 | IF : 00000000 | ICE : 000000000000000
            </div>
        </div>
        <div class="bloc-client">
            <div class="bloc-titre">Destinataire</div>
            <div class="bloc-nom">{{ $devis->client->nom }}</div>
            <div class="bloc-texte">
                @if($devis->client->entreprise)
                    <strong>{{ $devis->client->entreprise }}</strong><br>
                @endif
                {{ $devis->client->email }}<br>
                @if($devis->client->telephone)
                    {{ $devis->client->telephone }}<br>
                @endif
                @if($devis->client->adresse)
                    {{ $devis->client->adresse }}
                @endif
            </div>
        </div>
    </div>

    <!-- Dates -->
    <div class="bloc-dates">
        <div class="date-item">
            <div class="date-label">Date d'émission</div>
            <div class="date-valeur">{{ $devis->date_emission?->format('d/m/Y') ?? '—' }}</div>
        </div>
        <div class="date-item">
            <div class="date-label">Date de validité</div>
            <div class="date-valeur">{{ $devis->date_validite?->format('d/m/Y') ?? '—' }}</div>
        </div>
    </div>

    <!-- Objet du devis -->
    <div class="objet-devis">
        <div class="objet-label">Objet de la proposition</div>
        <div class="objet-titre">{{ $devis->titre }}</div>
    </div>

    <!-- Tableau des prestations -->
    <table class="tableau-prestations">
        <thead>
            <tr>
                <th style="width: 70%;">Description des prestations</th>
                <th class="col-montant" style="width: 30%;">Montant Net</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="prestation-titre">{{ $devis->titre }}</div>
                    <div class="prestation-desc">
                        @if($devis->description)
                            {{ $devis->description }}
                        @else
                            Prestations de conception, réalisation technique et accompagnement digital selon le cahier des charges convenu.
                        @endif
                    </div>
                </td>
                <td class="col-montant">
                    {{ number_format($devis->montant, 2, ',', ' ') }} DH
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Totaux -->
    <div class="totaux-section">
        <div class="totaux-gauche">
            <div class="bloc-titre">Modalités de règlement</div>
            <div style="font-size: 11px; color: #475569; line-height: 1.6;">
                @if($devis->conditions)
                    {{ $devis->conditions }}
                @else
                    - Acompte de 30% exigible à la signature du devis pour validation du démarrage des travaux.<br>
                    - Solde de 70% à la livraison et recette définitive du projet.<br>
                    - Paiement par virement bancaire ou chèque.
                @endif
            </div>
        </div>
        <div class="totaux-droite">
            <table class="totaux-table">
                <tr>
                    <td class="label">Total Brut</td>
                    <td class="valeur">{{ number_format($devis->montant, 2, ',', ' ') }} DH</td>
                </tr>
                <tr class="total-ttc">
                    <td style="color: #ffffff; font-weight: bold;">TOTAL NET</td>
                    <td class="valeur">{{ number_format($devis->montant, 2, ',', ' ') }} DH</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Signature & Validation -->
    <div class="signature-section">
        <div class="conditions-bloc">
            <strong>Conditions générales :</strong><br>
            La signature du présent devis emporte acceptation sans réserve des conditions générales de vente de l'agence. Le présent devis est valable jusqu'au {{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}. Tout dépassement du périmètre convenu fera l'objet d'un avenant.
        </div>
        <div class="signature-bloc">
            <div class="signature-titre">Pour le Client : Bon pour accord</div>
            <div class="signature-mention">(Date, signature et cachet précédés de la mention manuscrite « Bon pour accord »)</div>
            <div class="signature-ligne">Date & Signature :</div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="pied-page">
        Agence Admin Digital — SARL au capital de 100 000 DH — Fès, Maroc — Devis N° {{ $devis->numero }}
    </div>

</body>
</html>
