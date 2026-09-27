Webmarko — Notification Interne
Accord Commercial Confirmé

Bonjour l'équipe Webmarko,

Le client {{ $devis->client?->nom }} @if($devis->client?->entreprise)({{ $devis->client->entreprise }})@endif a officiellement validé et signé la proposition commerciale suivante :

DÉTAILS DU DEVIS :
- N° de Devis : {{ $devis->numero }}
- Prestation : {{ $devis->titre }}
- Montant : {{ number_format($devis->montant, 2, ',', ' ') }} DH
- Date d'accord : {{ $devis->accepte_le ? $devis->accepte_le->format('d/m/Y à H:i:s') : now()->format('d/m/Y à H:i:s') }}
- Adresse IP : {{ $devis->ip_acceptation ?? '127.0.0.1' }}

Le PDF certifié et signé est joint à cette notification.

Système CRM Webmarko — Fès, Maroc.
