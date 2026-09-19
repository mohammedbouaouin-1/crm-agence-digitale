<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: sans-serif; padding: 30px; color: #1f2937;">
    <h2>Nouveau message du portail client</h2>

    <table style="margin: 20px 0;">
        <tr><td><strong>Client :</strong></td><td>{{ $client->nom }}</td></tr>
        <tr><td><strong>Email :</strong></td><td>{{ $client->email }}</td></tr>
        <tr><td><strong>Sujet :</strong></td><td>{{ $sujet }}</td></tr>
    </table>

    <div style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-top: 20px;">
        <p style="white-space: pre-line;">{{ $messageContent }}</p>
    </div>

    <p style="color: #6b7280; font-size: 0.85rem; margin-top: 30px;">
        Vous pouvez répondre directement à cet email pour contacter le client.
    </p>
</body>
</html>
