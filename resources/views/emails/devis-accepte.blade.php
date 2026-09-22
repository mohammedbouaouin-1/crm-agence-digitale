<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Devis Accepté par le Client</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f8fafc; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <!-- HEADER -->
                <div style="border-bottom: 2px solid #0d9488; padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-size: 22px; font-weight: bold; color: #0d9488; letter-spacing: -0.5px;">Web<span style="color: #0f172a;">marko</span></span>
                        <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #64748b; margin-top: 3px; text-transform: uppercase;">Digital Marketing Agency — Notification Interne</div>
                    </div>
                </div>

                <!-- BADGE ACCORD -->
                <div style="background-color: #ecfdf5; border-left: 4px solid #10b981; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 6px 6px 0;">
                    <div style="font-size: 14px; font-weight: bold; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px;">Accord Commercial Confirmé en Ligne</div>
                    <div style="font-size: 13px; color: #047857; margin-top: 4px;">Un client vient de valider et signer sa proposition commerciale depuis son portail dédié.</div>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Bonjour l'équipe Webmarko,<br>
                    Le client <strong>{{ $devis->client?->nom }}</strong> @if($devis->client?->entreprise) (<em>{{ $devis->client->entreprise }}</em>) @endif a officiellement donné son accord pour la proposition commerciale :
                </p>

                <!-- RECAP TABLE -->
                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">N° de Devis :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $devis->numero }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Objet des livrables :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $devis->titre }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Montant Contractuel :</td>
                        <td align="right" style="font-size: 15px; font-weight: bold; color: #0d9488; border-bottom: 1px solid #e2e8f0;">{{ number_format($devis->montant, 2, ',', ' ') }} DH</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Date & Heure d'accord :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $devis->accepte_le ? $devis->accepte_le->format('d/m/Y à H:i:s') : now()->format('d/m/Y à H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Adresse IP du signataire :</td>
                        <td align="right" style="font-size: 12px; font-family: monospace; color: #475569;">{{ $devis->ip_acceptation ?? 'Non spécifiée' }}</td>
                    </tr>
                </table>

                <!-- PROCHAINES ACTIONS -->
                <div style="background-color: #f0fdfa; border: 1px solid #ccfbf1; padding: 14px; border-radius: 6px; margin: 20px 0;">
                    <strong style="font-size: 13px; color: #115e59;">Prochaines actions recommandées (Cycle Quote-to-Cash) :</strong>
                    <ul style="font-size: 13px; color: #0f766e; margin: 8px 0 0 20px; padding: 0; line-height: 1.6;">
                        <li>Initialiser le projet web ou la campagne Ads en un clic via les boutons d'actions contextuelles.</li>
                        <li>Générer la facture officielle pour 100% du montant convenu.</li>
                        <li>Saisir les versements d'acomptes dans le module des paiements dès réception bancaire.</li>
                    </ul>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Le document PDF certifié avec la mention d'acceptation est automatiquement joint à cet email.
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Notification automatique générée par la plateforme CRM Webmarko — Traçabilité électronique des engagements clients.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
