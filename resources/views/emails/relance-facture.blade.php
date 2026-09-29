<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Relance de Facture — {{ $facture->numero }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f9fafb; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <div style="border-bottom: 2px solid #06b6d4; padding-bottom: 15px; margin-bottom: 25px;">
                    <span style="font-size: 22px; font-weight: bold; color: #06b6d4; letter-spacing: -0.5px;">Web<span style="color: #0f172a;">marko</span></span>
                    <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #64748b; margin-top: 3px; text-transform: uppercase;">Digital Marketing Agency — Service Comptabilité</div>
                </div>

                <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 6px 6px 0;">
                    <div style="font-size: 13px; font-weight: bold; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px;">Rappel de règlement en attente</div>
                    <div style="font-size: 13px; color: #b91c1c; margin-top: 4px;">Sauf erreur ou virement en cours, la facture ci-dessous est arrivée à son terme.</div>
                </div>

                <h2 style="font-size: 18px; color: #0f172a; margin-top: 0;">Bonjour {{ $facture->client?->nom ?? 'Client' }},</h2>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Nous vous rappelons que la facture <strong>{{ $facture->numero }}</strong>, arrivée à échéance le <strong>{{ $facture->date_echeance?->format('d/m/Y') }}</strong>, est toujours en attente de régularisation.
                </p>

                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Numéro de Facture :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $facture->numero }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Montant Total TTC :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ number_format($facture->montant, 2, ',', ' ') }} DH</td>
                    </tr>
                    @php
                        $totalPaye = (float) $facture->paiements->sum('montant');
                        $reste = max(0, (float) $facture->montant - $totalPaye);
                    @endphp
                    @if($totalPaye > 0)
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Acompte déjà versé :</td>
                        <td align="right" style="font-size: 13px; color: #16a34a; font-weight: 600; border-bottom: 1px solid #e2e8f0;">{{ number_format($totalPaye, 2, ',', ' ') }} DH</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Reste à régler :</td>
                        <td align="right" style="font-size: 15px; font-weight: bold; color: #dc2626;">{{ number_format($reste, 2, ',', ' ') }} DH</td>
                    </tr>
                </table>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 14px; margin: 20px 0; font-size: 12px; color: #475569; line-height: 1.6;">
                    <strong style="color: #0f172a;">Modalités de virement bancaire :</strong><br>
                    Banque : <strong>{{ \App\Models\Setting::get('banque_nom', 'Attijariwafa Bank') }}</strong> &nbsp;|&nbsp;
                    Titulaire : <strong>{{ \App\Models\Setting::get('banque_titulaire', 'Webmarko SARL') }}</strong><br>
                    RIB : <span style="font-family: ui-monospace, Menlo, Consolas, monospace; font-weight: bold; color: #0284c7;">{{ \App\Models\Setting::get('banque_rib', '007 780 0001234567890123 45') }}</span>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Vous trouverez la facture détaillée en pièce jointe de cet email. Si votre virement a été émis entre-temps, merci de ne pas tenir compte de cette notification.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-top: 30px; margin-bottom: 0;">
                    Bien cordialement,<br>
                    <strong>L'équipe Webmarko</strong><br>
                    <span style="font-size: 12px; color: #64748b;">Email: {{ \App\Models\Setting::get('agence_email', 'webmarko.company@gmail.com') }} &nbsp;|&nbsp; Tél: {{ \App\Models\Setting::get('agence_telephone', '06 61 51 11 83') }}</span>
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Rappel comptable automatique — Plateforme CRM Webmarko.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
