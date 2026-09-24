<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; padding: 30px; color: #1f2937;">
    <h2>Bonjour {{ $facture->client?->nom ?? 'Client' }},</h2>
    <p>Nous vous rappelons que la facture <strong>{{ $facture->numero }}</strong>, d'un montant de
        <strong>{{ number_format($facture->montant, 2, ',', ' ') }} DH</strong>,
        arrivée à échéance le <strong>{{ $facture->date_echeance?->format('d/m/Y') }}</strong>,
        est toujours en attente de règlement.</p>

    <p>Vous trouverez la facture détaillée en pièce jointe de cet email.</p>

    <p>N'hésitez pas à nous contacter si vous avez déjà effectué le paiement ou pour toute question.</p>

    <p style="color: #6b7280; font-size: 0.85rem; margin-top: 30px;">
        Webmarko — Digital Marketing Agency
    </p>
</body>
</html>
