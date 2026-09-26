<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Votre accès au portail client</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; padding: 30px; color: #1f2937; background-color: #f9fafb; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 30px;">
        <tr>
            <td>
                <div style="border-bottom: 2px solid #06b6d4; padding-bottom: 15px; margin-bottom: 25px;">
                    <span style="font-size: 22px; font-weight: bold; color: #06b6d4; letter-spacing: -0.5px;">Web<span style="color: #0f172a;">marko</span></span>
                    <div style="font-size: 10px; font-weight: bold; letter-spacing: 1.5px; color: #64748b; margin-top: 3px; text-transform: uppercase;">Digital Marketing Agency — Espace Partenaire</div>
                </div>

                <h2 style="font-size: 18px; color: #0f172a; margin-top: 0;">Bonjour {{ $nomClient }},</h2>

                <p style="font-size: 14px; line-height: 1.6; color: #374151;">
                    Votre accès sécurisé au portail client de l'agence Webmarko a été créé avec succès. Cet espace vous permet de suivre en temps réel vos projets web, consulter vos campagnes publicitaires, valider vos propositions commerciales et retrouver l'ensemble de vos factures officielles.
                </p>

                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin: 25px 0;">
                    <div style="font-size: 12px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Vos identifiants de connexion</div>
                    <table width="100%" cellpadding="6" cellspacing="0">
                        <tr>
                            <td style="font-size: 13px; color: #64748b; width: 140px;">Adresse e-mail :</td>
                            <td style="font-size: 14px; font-weight: 600; color: #0f172a;">{{ $email }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #64748b;">Mot de passe :</td>
                            <td style="font-size: 14px; font-family: ui-monospace, Menlo, Consolas, monospace; font-weight: bold; color: #0284c7; letter-spacing: 1px;">{{ $motDePasse }}</td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $lien }}" style="display: inline-block; background-color: #0284c7; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px;">
                        Accéder à mon portail client &rarr;
                    </a>
                </div>

                <p style="font-size: 12px; line-height: 1.5; color: #64748b; background-color: #f1f5f9; padding: 10px 14px; border-radius: 6px;">
                    Pour votre sécurité, nous vous recommandons de modifier ce mot de passe temporaire dès votre première connexion dans la section Mon Profil.
                </p>

                <p style="font-size: 14px; line-height: 1.6; color: #374151; margin-top: 30px; margin-bottom: 0;">
                    Bien cordialement,<br>
                    <strong>L'équipe Webmarko</strong><br>
                    <span style="font-size: 12px; color: #64748b;">Email: webmarko.company@gmail.com &nbsp;|&nbsp; Tél: 06 61 51 11 83</span>
                </p>

                <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 11px; color: #94a3b8; text-align: center;">
                    Message automatique expédié par la plateforme CRM Webmarko — Fès, Maroc.
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
