Webmarko — Service Comptabilité
Suivi de règlement — Facture N° {{ $facture->numero }}

Bonjour {{ $facture->client?->nom ?? 'Client' }},

Nous vous informons que la facture {{ $facture->numero }}, arrivée à échéance le {{ $facture->date_echeance?->format('d/m/Y') }}, est en attente de régularisation.

RÉCAPITULATIF DU SOLDE :
- Numéro de facture : {{ $facture->numero }}
- Montant total : {{ number_format($facture->montant, 2, ',', ' ') }} DH
@php
    $totalPaye = (float) $facture->paiements->sum('montant');
    $reste = max(0, (float) $facture->montant - $totalPaye);
@endphp
@if($totalPaye > 0)
- Acompte déjà perçu : {{ number_format($totalPaye, 2, ',', ' ') }} DH
@endif
- Solde restant dû : {{ number_format($reste, 2, ',', ' ') }} DH

COORDONNÉES BANCAIRES POUR LE RÈGLEMENT :
- Banque : {{ \App\Models\Setting::get('banque_nom', 'Attijariwafa Bank') }}
- Titulaire : {{ \App\Models\Setting::get('banque_titulaire', 'Webmarko SARL') }}
- RIB : {{ \App\Models\Setting::get('banque_rib', '007 780 0001234567890123 45') }}

La facture détaillée est jointe à cet email. Si votre virement a été émis récemment, merci de ne pas tenir compte de ce rappel.

Bien cordialement,
L'équipe Webmarko
Email : {{ \App\Models\Setting::get('agence_email', 'webmarko.company@gmail.com') }} | Tél : {{ \App\Models\Setting::get('agence_telephone', '06 61 51 11 83') }}
Webmarko SARL — Avenue des FAR, Fès, Maroc.
