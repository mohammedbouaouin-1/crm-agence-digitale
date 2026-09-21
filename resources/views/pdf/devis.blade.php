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
        .badge-accepte { background: #dcfce7; color: #15803d; }
        .badge-envoye { background: #e0f2fe; color: #0369a1; }
        .badge-brouillon { background: #f1f5f9; color: #475569; }
        .badge-refuse { background: #fee2e2; color: #b91c1c; }
        .badge-expire { background: #fef3c7; color: #b45309; }

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
            vertical-align: top;
        }
        table.montant tbody td.droite { 
            text-align: right; 
            font-weight: bold;
            color: #0f172a;
        }
        .prestation-titre {
            font-weight: bold;
            color: #0f172a;
            font-size: 14px;
            margin-bottom: 6px;
        }
        .prestation-desc {
            color: #475569;
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-line;
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

        /* Conditions & Accord */
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
        .accord-section {
            display: table;
            width: 100%;
            margin-bottom: 35px;
        }
        .accord-gauche {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 25px;
        }
        .accord-droite {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }
        .conditions-texte {
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }
        .conditions-cgv {
            font-size: 10px;
            color: #64748b;
            margin-top: 10px;
            line-height: 1.5;
            font-style: italic;
        }

        /* Cartouche acceptation certifiée */
        .cartouche-certifie {
            border: 2px solid #10b981;
            background-color: #f0fdf4;
            border-radius: 6px;
            padding: 12px 14px;
            text-align: left;
        }
        .cartouche-titre {
            font-size: 11px;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            border-bottom: 1px solid #bbf7d0;
            padding-bottom: 4px;
        }
        .cartouche-sous-titre {
            font-size: 10px;
            color: #047857;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .cartouche-info {
            font-size: 10px;
            color: #1e293b;
            margin-bottom: 3px;
        }
        .cartouche-mention {
            font-size: 9px;
            color: #047857;
            font-style: italic;
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px dashed #86efac;
        }

        /* Cartouche signature classique épuré */
        .cartouche-signature {
            border: 1px dashed #cbd5e1;
            background-color: #f8fafc;
            padding: 12px 14px;
            border-radius: 6px;
            text-align: center;
        }
        .signature-titre {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .signature-zone {
            height: 45px;
        }
        .signature-ligne {
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            font-size: 10px;
            color: #64748b;
        }

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
            <div class="titre-devis">DEVIS</div>
            <div class="numero-devis">N° {{ $devis->numero }}</div>
            <span class="badge badge-{{ $devis->statut }}">
                @switch($devis->statut)
                    @case('accepte') Accepté @break
                    @case('envoye') Envoyé @break
                    @case('refuse') Refusé @break
                    @case('expire') Expiré @break
                    @default Brouillon
                @endswitch
            </span>
        </div>
    </div>

    {{-- Infos client + dates --}}
    <div class="infos">
        <div class="infos-bloc">
            <div class="infos-label">Devis établi pour</div>
            <div class="infos-nom">{{ $devis->client->nom }}</div>
            @if($devis->client->entreprise)
                <div class="infos-ligne"><strong>Page / Marque :</strong> {{ $devis->client->entreprise }}</div>
            @endif
            @if($devis->client->adresse)
                <div class="infos-ligne"><strong>Lien :</strong> {{ $devis->client->adresse }}</div>
            @endif
            @if($devis->client->email)
                <div class="infos-ligne">{{ $devis->client->email }}</div>
            @endif
            @if($devis->client->telephone)
                <div class="infos-ligne">{{ $devis->client->telephone }}</div>
            @endif
        </div>
        <div class="infos-bloc">
            <table class="dates-table">
                <tr>
                    <td class="cle">Date d'émission</td>
                    <td class="valeur">{{ $devis->date_emission?->format('d/m/Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="cle">Date de validité</td>
                    <td class="valeur">{{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}</td>
                </tr>
                @if($devis->statut === 'accepte' && $devis->accepte_le)
                <tr>
                    <td class="cle">Validé le</td>
                    <td class="valeur">{{ $devis->accepte_le->format('d/m/Y à H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    {{-- Détail de la proposition / prestations --}}
    <table class="montant">
        <thead>
            <tr>
                <th>Description des prestations & Livrables</th>
                <th class="droite" style="width: 160px;">Montant Net</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="prestation-titre" @if(!$devis->description) style="margin-bottom: 0;" @endif>{{ $devis->titre }}</div>
                    @if($devis->description)
                        <div class="prestation-desc">{!! nl2br(e($devis->description)) !!}</div>
                    @endif
                </td>
                <td class="droite">
                    {{ number_format($devis->montant, 2, ',', ' ') }} DH
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Bloc total --}}
    <div class="bloc-total">
        <div class="bloc-total-interieur">
            <div class="ligne-total">
                <div class="cle">Total Brut HT</div>
                <div class="valeur">{{ number_format($devis->montant, 2, ',', ' ') }} DH</div>
            </div>
            <div class="ligne-total ligne-total-final">
                <div class="cle">Total Net</div>
                <div class="valeur">{{ number_format($devis->montant, 2, ',', ' ') }} DH</div>
            </div>
        </div>
    </div>

    {{-- Modalités de règlement & Accord commercial --}}
    <div style="clear: both;">
        <div class="section-titre">{{ $devis->conditions ? 'Modalités de règlement & Accord commercial' : 'Conditions de l\'offre & Accord commercial' }}</div>
        <div class="accord-section">
            <div class="accord-gauche">
                @if($devis->conditions)
                    <div class="conditions-texte">
                        <strong>Modalités de règlement :</strong><br>
                        {!! nl2br(e($devis->conditions)) !!}
                    </div>
                @endif
                <div class="conditions-cgv" @if(!$devis->conditions) style="margin-top: 0;" @endif>
                    La signature du présent devis emporte acceptation sans réserve des conditions générales de vente de l'agence. Offre valable jusqu'au {{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}. Tout ajout hors périmètre fera l'objet d'un avenant.
                </div>
            </div>
            <div class="accord-droite">
                @if($devis->accepte_le)
                    <div class="cartouche-certifie">
                        <div class="cartouche-titre">Acceptation Électronique Certifiée</div>
                        <div class="cartouche-sous-titre">Document validé en ligne par le client</div>
                        <div class="cartouche-info"><strong>Date & heure :</strong> {{ $devis->accepte_le->format('d/m/Y à H:i:s') }}</div>
                        @if($devis->ip_acceptation)
                            <div class="cartouche-info"><strong>Adresse IP :</strong> {{ $devis->ip_acceptation }}</div>
                        @endif
                        <div class="cartouche-mention">Mention formelle : « Bon pour accord enregistré »</div>
                    </div>
                @else
                    <div class="cartouche-signature">
                        <div class="signature-titre">Bon pour accord</div>
                        <div class="signature-zone"></div>
                        <div class="signature-ligne">Date & Signature du client</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pied de page --}}
    <div class="pied">
        <strong>Webmarko — Digital Marketing Agency</strong><br>
        Email: webmarko.company@gmail.com<br>
        Devis N° {{ $devis->numero }} — Merci pour votre confiance.
    </div>

</body>
</html>
