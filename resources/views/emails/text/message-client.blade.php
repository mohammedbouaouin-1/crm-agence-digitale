Webmarko — Notification Support Client

Bonjour l'équipe Webmarko,

Le client {{ $client->nom }} @if($client->entreprise)({{ $client->entreprise }})@endif vous a transmis une nouvelle demande depuis son espace client :

INFORMATIONS CLIENT :
- Nom : {{ $client->nom }}
@if($client->entreprise)
- Entreprise : {{ $client->entreprise }}
@endif
- Email : {{ $client->email }}
@if($client->telephone)
- Téléphone : {{ $client->telephone }}
@endif
- Sujet : {{ $sujet }}

CONTENU DU MESSAGE :
{{ $messageContent }}

Vous pouvez répondre directement à cet email pour contacter votre client.

Portail Client Webmarko — Fès, Maroc.
