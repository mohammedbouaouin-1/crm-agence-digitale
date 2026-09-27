<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nouveau message client — {{ $sujet }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f9fafb; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <div style="border-bottom: 2px solid #06b6d4; padding-bottom: 15px; margin-bottom: 25px;">
                    <span style="font-size: 22px; font-weight: bold; color: #06b6d4; letter-spacing: -0.5px;">Web<span style="color: #0f172a;">marko</span></span>
                    <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #64748b; margin-top: 3px; text-transform: uppercase;">Digital Marketing Agency — Notification Support</div>
                </div>

                <div style="background-color: #f0f9ff; border-left: 4px solid #0284c7; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 6px 6px 0;">
                    <div style="font-size: 13px; font-weight: bold; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px;">Nouvelle Demande Client</div>
                    <div style="font-size: 13px; color: #0284c7; margin-top: 4px;">Un message a été envoyé depuis l'espace dédié du portail client.</div>
                </div>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Bonjour l'équipe Webmarko,<br>
                    Le client <strong>{{ $client->nom }}</strong> @if($client->entreprise) (<em>{{ $client->entreprise }}</em>) @endif vous a adressé un nouveau message :
                </p>

                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin: 20px 0;">
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0; width: 140px;">Client :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $client->nom }}</td>
                    </tr>
                    @if($client->entreprise)
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Entreprise :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $client->entreprise }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Email :</td>
                        <td align="right" style="font-size: 13px; font-weight: 600; color: #0284c7; border-bottom: 1px solid #e2e8f0;">{{ $client->email }}</td>
                    </tr>
                    @if($client->telephone)
                    <tr>
                        <td style="font-size: 13px; color: #64748b; border-bottom: 1px solid #e2e8f0;">Téléphone :</td>
                        <td align="right" style="font-size: 13px; color: #0f172a; border-bottom: 1px solid #e2e8f0;">{{ $client->telephone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: 13px; color: #64748b;">Sujet :</td>
                        <td align="right" style="font-size: 13px; font-weight: bold; color: #0f172a;">{{ $sujet }}</td>
                    </tr>
                </table>

                <div style="margin: 25px 0;">
                    <div style="font-size: 12px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Contenu du message :</div>
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; font-size: 14px; line-height: 1.6; color: #1e293b; white-space: pre-line;">{{ $messageContent }}</div>
                </div>

                <p style="font-size: 13px; line-height: 1.5; color: #64748b; background-color: #f1f5f9; padding: 10px 14px; border-radius: 6px;">
                    Vous pouvez répondre directement à cet email pour contacter le client (réponse transmise directement à {{ $client->email }}).
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Notification automatique générée par le portail client Webmarko — Fès, Maroc.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
