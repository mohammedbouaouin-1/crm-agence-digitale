Webmarko — Digital Marketing Agency
Proposition Commerciale

Bonjour {{ $devis->client?->nom ?? 'Client' }},

Nous avons le plaisir de vous transmettre notre proposition commerciale concernant :
« {{ $devis->titre }} ».

RÉCAPITULATIF :
- Numéro du devis : {{ $devis->numero }}
- Montant Net : {{ number_format($devis->montant, 2, ',', ' ') }} DH
- Date de validité : {{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}

Vous trouverez en pièce jointe le document PDF officiel reprenant le détail de la prestation.
Vous pouvez également vous connecter à votre Espace Client pour consulter et signer électroniquement ce devis.

Bien cordialement,
L'équipe Webmarko
Email : webmarko.company@gmail.com | Tél : 06 61 51 11 83
Webmarko SARL — Avenue des FAR, Fès, Maroc.
