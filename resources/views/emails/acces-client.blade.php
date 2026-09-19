<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; padding: 30px; color: #1f2937;">
    <h2>Bonjour {{ $nomClient }},</h2>
    <p>Votre accès au portail client a été créé. Voici vos identifiants de connexion :</p>

    <table style="margin: 20px 0;">
        <tr><td><strong>Email :</strong></td><td>{{ $email }}</td></tr>
        <tr><td><strong>Mot de passe :</strong></td><td>{{ $motDePasse }}</td></tr>
    </table>

    <p>
        <a href="{{ $lien }}" style="background: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;">
            Accéder à mon portail
        </a>
    </p>

    <p style="color: #6b7280; font-size: 0.85rem; margin-top: 30px;">
        Nous vous recommandons de changer ce mot de passe après votre première connexion.
    </p>
</body>
</html>
