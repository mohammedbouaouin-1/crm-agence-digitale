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
        .conditions-bloc {
            margin-top: 25px;
            margin-bottom: 25px;
        }
        .conditions-texte {
            font-size: 11px;
            color: #475569;
            line-height: 1.6;
        }
        .conditions-cgv {
            font-size: 10px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.5;
            font-style: italic;
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
            <div class="infos-nom">{{ $devis->client?->nom ?? 'Client non spécifié' }}</div>
            @if($devis->client?->entreprise)
                <div class="infos-ligne"><strong>Page / Marque :</strong> {{ $devis->client->entreprise }}</div>
            @endif
            @if($devis->client?->adresse)
                <div class="infos-ligne"><strong>Lien :</strong> {{ $devis->client->adresse }}</div>
            @endif
            @if($devis->client?->email)
                <div class="infos-ligne">{{ $devis->client->email }}</div>
            @endif
            @if($devis->client?->telephone)
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

    @if($devis->conditions)
        <div class="conditions-bloc" style="clear: both;">
            <div class="section-titre">Modalités de règlement</div>
            <div class="conditions-texte">
                {!! nl2br(e($devis->conditions)) !!}
            </div>
            <div class="conditions-cgv">
                Offre valable jusqu'au {{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}.
            </div>
        </div>
    @endif

    {{-- Pied de page --}}
    <div class="pied">
        <strong>{{ \App\Models\Setting::get('agence_nom', 'Webmarko') }} — {{ \App\Models\Setting::get('agence_slogan', 'Digital Marketing Agency') }}</strong><br>
        Email: {{ \App\Models\Setting::get('agence_email', 'webmarko.company@gmail.com') }} &nbsp;|&nbsp; Tél: {{ \App\Models\Setting::get('agence_telephone', '+212 5 22 00 00 00') }}<br>
        Devis N° {{ $devis->numero }} — Merci pour votre confiance.
    </div>

</body>
</html>
