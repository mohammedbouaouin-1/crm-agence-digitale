Webmarko — Digital Marketing Agency
Facture Officielle N° {{ $facture->numero }}

Bonjour {{ $facture->client?->nom ?? 'Client' }},

Veuillez trouver ci-joint votre facture officielle émise par l'agence Webmarko :

DÉTAILS DE LA FACTURE :
- Numéro : {{ $facture->numero }}
@if($facture->devis)
- Référence Devis : {{ $facture->devis->numero }} ({{ $facture->devis->titre }})
@endif
- Date d'émission : {{ $facture->date_emission?->format('d/m/Y') }}
- Date d'échéance : {{ $facture->date_echeance?->format('d/m/Y') }}
- Montant Total : {{ number_format($facture->montant, 2, ',', ' ') }} DH

Le document officiel au format PDF est joint à cet email.
Vous pouvez également consulter l'historique de vos factures et règlements sur votre Espace Client.

Bien cordialement,
L'équipe Webmarko
Email : {{ \App\Models\Setting::get('agence_email', 'webmarko.company@gmail.com') }} | Tél : {{ \App\Models\Setting::get('agence_telephone', '06 61 51 11 83') }}
Webmarko SARL — Avenue des FAR, Fès, Maroc.
