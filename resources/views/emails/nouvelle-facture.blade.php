<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture N° {{ $facture->numero }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f9fafb; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <div style="border-bottom: 2px solid #06b6d4; padding-bottom: 15px; margin-bottom: 25px;">
                    <span style="font-size: 22px; font-weight: bold; color: #06b6d4; letter-spacing: -0.5px;">Web<span style="color: #0f172a;">marko</span></span>
                    <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #64748b; margin-top: 3px; text-transform: uppercase;">Digital Marketing Agency</div>
                </div>

                <h2 style="font-size: 18px; color: #0f172a; margin-top: 0;">Bonjour {{ $facture->client->nom }},</h2>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Veuillez trouver ci-joint votre facture officielle émise par l'agence Webmarko :
                </p>

                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Numéro de Facture :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $facture->numero }}</td>
                    </tr>
                    @if($facture->devis)
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Référence Devis :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a;">{{ $facture->devis->numero }} ({{ $facture->devis->titre }})</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Date d'émission :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a;">{{ $facture->date_emission?->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Date d'échéance :</td>
                        <td align="right" style="font-size: 13px; color: #b91c1c; font-weight: 600;">{{ $facture->date_echeance?->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Montant Total :</td>
                        <td align="right" style="font-size: 15px; font-weight: bold; color: #06b6d4;">{{ number_format($facture->montant, 2, ',', ' ') }} DH</td>
                    </tr>
                </table>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Le document complet au format PDF est joint à cet email. Vous pouvez également consulter l'état de vos factures et règlements directement sur votre <strong>Espace Client</strong>.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-top: 30px;">
                    Nous vous remercions pour votre confiance et restons à votre disposition pour toute information comptable.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 0;">
                    Bien cordialement,<br>
                    <strong>L'équipe Webmarko</strong><br>
                    <span style="font-size: 12px; color: #64748b;">Email: webmarko.company@gmail.com</span>
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Ce message vous est adressé automatiquement suite à l'émission de la facture N° {{ $facture->numero }}.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
