<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $ticket->numero_billet }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { background: #0d6efd; color: #fff; padding: 10px 15px; }
        .section { margin: 15px 0; }
        .box { border: 1px solid #ccc; padding: 10px; border-radius: 4px; }
        .label { font-weight: bold; width: 140px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Billet de vol</h2>
        <p>Numéro de billet : {{ $ticket->numero_billet }}</p>
    </div>

    <div class="section box">
        <h3>Détails du passager</h3>
        <p><span class="label">Nom :</span> {{ $reservation->getFullPassengerName() }}</p>
        <p><span class="label">Réservation :</span> #{{ $reservation->booking_reference }}</p>
    </div>

    <div class="section box">
        <h3>Détails du vol</h3>
        <p><span class="label">Vol :</span> {{ $reservation->flight->flight_number }}</p>
        <p><span class="label">Trajet :</span>
            {{ $reservation->flight->departure_city }} → {{ $reservation->flight->arrival_city }}
        </p>
        <p><span class="label">Départ :</span> {{ $reservation->flight->departure_time->format('d/m/Y H:i') }}</p>
        <p><span class="label">Arrivée :</span> {{ $reservation->flight->arrival_time->format('d/m/Y H:i') }}</p>
        <p><span class="label">Siège :</span> {{ $reservation->seats->first()?->seat_number ?? '-' }}</p>
    </div>

    <div class="section box">
        <h3>Paiement</h3>
        <p><span class="label">Montant :</span> {{ number_format($reservation->payment->amount, 0) }} XOF</p>
        <p><span class="label">Mode :</span> {{ $reservation->payment->payment_method }}</p>
        <p><span class="label">Statut :</span> {{ strtoupper($reservation->payment->status) }}</p>
        <p><span class="label">Transaction :</span> {{ $reservation->payment->transaction_id }}</p>
    </div>



    <p style="margin-top: 30px; font-size: 10px; color: #555;">
        Merci d’avoir réservé avec RésaVols Pro. Présentez ce billet lors de l’embarquement.
    </p>
</body>
</html>
