<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Proposition Commerciale</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f9fafb; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <div style="border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 25px;">
                    <span style="font-size: 20px; font-weight: bold; color: #0f172a; letter-spacing: -0.5px;">Agence Admin Digital</span>
                </div>

                <h2 style="font-size: 18px; color: #0f172a; margin-top: 0;">Bonjour {{ $devis->client->nom }},</h2>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Nous avons le plaisir de vous transmettre notre proposition commerciale concernant :<br>
                    <strong style="color: #0f172a;">« {{ $devis->titre }} »</strong>.
                </p>

                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Numéro du devis :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $devis->numero }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Montant Total Net :</td>
                        <td align="right" style="font-size: 14px; font-weight: bold; color: #0284c7;">{{ number_format($devis->montant, 2, ',', ' ') }} DH</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Date de validité :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a;">{{ $devis->date_validite?->format('d/m/Y') ?? '30 jours' }}</td>
                    </tr>
                </table>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Vous trouverez en pièce jointe le document PDF détaillé reprenant l'ensemble des prestations et conditions tarifaires.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Vous pouvez également vous connecter directement à votre <strong>Espace Client</strong> pour consulter ce devis et procéder à son acceptation certifiée en ligne.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-top: 30px;">
                    Notre équipe reste à votre entière disposition pour tout échange ou précision complémentaire.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 0;">
                    Bien cordialement,<br>
                    <strong>L'équipe Agence Admin Digital</strong>
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Ce message vous est adressé par Agence Admin Digital suite à l'émission d'une proposition commerciale.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
