<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture N° {{ $facture->numero }}</title>
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
        .titre-facture {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .numero-facture {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 600;
        }

        /* Badge de statut */
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-payee { background: #dcfce7; color: #15803d; }
        .badge-en_attente { background: #f1f5f9; color: #475569; }
        .badge-partiellement_payee { background: #fef3c7; color: #b45309; }
        .badge-en_retard { background: #fee2e2; color: #b91c1c; }

        /* Bloc infos client / dates */
        .infos {
            display: table;
            width: 100%;
            margin-bottom: 35px;
        }
        .infos-bloc {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .infos-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #06b6d4;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .infos-nom {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }
        .infos-ligne {
            color: #475569;
            font-size: 12px;
            margin-top: 3px;
        }
        
        .dates-table {
            width: 100%;
            border-collapse: collapse;
        }
        .dates-table td {
            padding: 4px 0;
        }
        .dates-table .cle {
            color: #64748b;
            font-size: 12px;
        }
        .dates-table .valeur {
            font-weight: bold;
            color: #0f172a;
            text-align: right;
            font-size: 13px;
        }

        /* Tableau des prestations */
        table.montant {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.montant thead th {
            background: #0f172a;
            color: #ffffff;
            text-align: left;
            padding: 12px 16px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        table.montant thead th.droite { text-align: right; }
        table.montant tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #334155;
        }
        table.montant tbody td.droite { 
            text-align: right; 
            font-weight: bold;
            color: #0f172a;
        }

        /* Bloc totaux */
        .bloc-total {
            width: 100%;
            display: table;
            margin-bottom: 40px;
        }
        .bloc-total-interieur {
            display: table-cell;
            width: 280px;
            float: right;
        }
        .ligne-total {
            display: table;
            width: 100%;
            padding: 8px 14px;
            font-size: 13px;
        }
        .ligne-total .cle { display: table-cell; color: #64748b; }
        .ligne-total .valeur { display: table-cell; text-align: right; color: #0f172a; font-weight: 600; }
        
        .ligne-total-final {
            background: #ecfeff;
            border-top: 2px solid #06b6d4;
            font-size: 15px;
            font-weight: bold;
            color: #0e7490;
            border-radius: 6px;
        }

        /* Historique des paiements */
        .section-titre {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
        table.paiements {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            font-size: 12px;
        }
        table.paiements th {
            text-align: left;
            color: #64748b;
            padding: 8px 0;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }
        table.paiements td {
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        table.paiements td.droite { text-align: right; font-weight: bold; }

        /* Pied de page */
        .pied {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 11px;
            line-height: 1.6;
        }
        .pied strong { color: #0f172a; }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <div class="en-tete">
        <div class="en-tete-gauche">
            @php
                $logoPath = public_path('images/logo.png');
                $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
            @endphp
            @if($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" style="height: 40px;">
            @else
                <div class="logo-nom">Web<span>marko</span></div>
                <div class="logo-sous-titre">Digital Marketing Agency</div>
            @endif
        </div>
        <div class="en-tete-droite">
            <div class="titre-facture">FACTURE</div>
            <div class="numero-facture">N° {{ $facture->numero }}</div>
            <span class="badge badge-{{ $facture->statut }}">
                @switch($facture->statut)
                    @case('payee') ✓ Payée @break
                    @case('partiellement_payee') ◑ Partiellement payée @break
                    @case('en_retard') ⚠ En retard @break
                    @default ○ En attente
                @endswitch
            </span>
        </div>
    </div>

    {{-- Infos client + dates --}}
    <div class="infos">
        <div class="infos-bloc">
            <div class="infos-label">Facturé à</div>
            <div class="infos-nom">{{ $facture->client->nom }}</div>
            @if($facture->client->entreprise)
                <div class="infos-ligne"><strong>Page / Marque :</strong> {{ $facture->client->entreprise }}</div>
            @endif
            @if($facture->client->adresse)
                <div class="infos-ligne"><strong>Lien :</strong> {{ $facture->client->adresse }}</div>
            @endif
            @if($facture->client->email)
                <div class="infos-ligne">{{ $facture->client->email }}</div>
            @endif
            @if($facture->client->telephone)
                <div class="infos-ligne">{{ $facture->client->telephone }}</div>
            @endif
        </div>
        <div class="infos-bloc">
            <table class="dates-table">
                <tr>
                    <td class="cle">Date d'émission</td>
                    <td class="valeur">{{ $facture->date_emission?->format('d/m/Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="cle">Date d'échéance</td>
                    <td class="valeur">{{ $facture->date_echeance?->format('d/m/Y') }}</td>
                </tr>
                @if($facture->devis)
                <tr>
                    <td class="cle">Réf. Devis</td>
                    <td class="valeur">{{ $facture->devis->numero }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Détail de la facture --}}
    <table class="montant">
        <thead>
            <tr>
                <th>Description des prestations</th>
                <th class="droite">Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Services marketing digital & web — {{ $facture->client->nom }}</td>
                <td class="droite">{{ number_format($facture->montant, 2, ',', ' ') }} DH</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    @php
        $totalPaye = $facture->totalPaye ?? 0;
        $resteAPayer = max(0, $facture->montant - $totalPaye);
    @endphp
    <div class="bloc-total">
        <div class="bloc-total-interieur">
            <div class="ligne-total">
                <div class="cle">Montant total TTC</div>
                <div class="valeur">{{ number_format($facture->montant, 2, ',', ' ') }} DH</div>
            </div>
            <div class="ligne-total">
                <div class="cle">Déjà réglé</div>
                <div class="valeur">{{ number_format($totalPaye, 2, ',', ' ') }} DH</div>
            </div>
            <div class="ligne-total ligne-total-final">
                <div class="cle">Reste à payer</div>
                <div class="valeur">{{ number_format($resteAPayer, 2, ',', ' ') }} DH</div>
            </div>
        </div>
    </div>

    {{-- Historique des paiements --}}
    @if($facture->paiements->count() > 0)
        <div style="clear: both;">
            <div class="section-titre">Historique des règlements</div>
            <table class="paiements">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Méthode de paiement</th>
                        <th class="droite">Montant versé</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facture->paiements as $paiement)
                        <tr>
                            <td>{{ $paiement->date?->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($paiement->methode) }}</td>
                            <td class="droite">{{ number_format($paiement->montant, 2, ',', ' ') }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Pied de page --}}
    <div class="pied">
        <strong>Webmarko — Digital Marketing Agency</strong><br>
        Email: webmarko.company@gmail.com<br>
        Merci pour votre confiance.
    </div>

</body>
</html>