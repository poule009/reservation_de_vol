{{-- resources/views/tickets/boarding-pass.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - {{ $reservation->booking_reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .ticket {
            border: 2px solid #4F46E5;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 20px;
        }
        
        .ticket-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .ticket-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .ticket-body {
            background: white;
            padding: 30px;
        }
        
        .section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #E5E7EB;
        }
        
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #6B7280;
            margin-bottom: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 8px 0;
            width: 50%;
        }
        
        .info-label {
            font-size: 10px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
        
        .flight-route {
            display: table;
            width: 100%;
            margin: 15px 0;
        }
        
        .route-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        
        .route-cell.airport {
            width: 40%;
        }
        
        .route-cell.arrow {
            width: 20%;
        }
        
        .airport-code {
            font-size: 32px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        
        .airport-time {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }
        
        .airport-date {
            font-size: 11px;
            color: #6B7280;
        }
        
        .arrow-icon {
            font-size: 24px;
            color: #9CA3AF;
        }
        
        .seats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        
        .seat-badge {
            display: inline-block;
            background: #EEF2FF;
            color: #4F46E5;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            border: 2px solid #C7D2FE;
        }
        
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #F9FAFB;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .qr-code {
            width: 150px;
            height: 150px;
            margin: 0 auto 10px;
            background: white;
            padding: 10px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
        }
        
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 2px;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .important-info {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin-top: 20px;
        }
        
        .important-info h3 {
            color: #92400E;
            font-size: 13px;
            margin-bottom: 8px;
        }
        
        .important-info ul {
            list-style: none;
            padding: 0;
        }
        
        .important-info li {
            color: #78350F;
            font-size: 11px;
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }
        
        .important-info li:before {
            content: "✓";
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 10px;
        }
        
        .price-breakdown {
            background: #F9FAFB;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 12px;
        }
        
        .price-row.total {
            border-top: 2px solid #E5E7EB;
            margin-top: 8px;
            padding-top: 8px;
            font-weight: bold;
            font-size: 16px;
            color: #4F46E5;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête du ticket -->
        <div class="ticket">
            <div class="ticket-header">
                <h1>✈️ BILLET ÉLECTRONIQUE</h1>
                <p>Carte d'embarquement - Boarding Pass</p>
            </div>
            
            <div class="ticket-body">
                <!-- Informations de réservation -->
                <div class="section">
                    <div class="section-title">Informations de réservation</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-cell">
                                <div class="info-label">Numéro de réservation</div>
                                <div class="info-value">{{ $reservation->booking_reference }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="info-label">Date de réservation</div>
                                <div class="info-value">{{ $reservation->booking_date->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-cell">
                                <div class="info-label">Statut</div>
                                <div class="info-value" style="color: #10B981;">{{ strtoupper($reservation->status) }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="info-label">Transaction ID</div>
                                <div class="info-value" style="font-size: 11px;">{{ $reservation->payment->transaction_id ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations du vol -->
                <div class="section">
                    <div class="section-title">Détails du vol</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-cell">
                                <div class="info-label">Numéro de vol</div>
                                <div class="info-value" style="font-size: 20px; color: #4F46E5;">{{ $reservation->flight->flight_number }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="info-label">Compagnie aérienne</div>
                                <div class="info-value">{{ $reservation->flight->airline }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Itinéraire -->
                    <div class="flight-route">
                        <div class="route-cell airport">
                            <div class="airport-code">{{ $reservation->flight->departure_airport }}</div>
                            <div class="airport-time">{{ $reservation->flight->departure_time->format('H:i') }}</div>
                            <div class="airport-date">{{ $reservation->flight->departure_time->format('d/m/Y') }}</div>
                        </div>
                        <div class="route-cell arrow">
                            <div class="arrow-icon">→</div>
                            <div style="font-size: 10px; color: #6B7280; margin-top: 5px;">
                                {{ floor($reservation->flight->duration / 60) }}h {{ $reservation->flight->duration % 60 }}m
                            </div>
                        </div>
                        <div class="route-cell airport">
                            <div class="airport-code">{{ $reservation->flight->arrival_airport }}</div>
                            <div class="airport-time">{{ $reservation->flight->arrival_time->format('H:i') }}</div>
                            <div class="airport-date">{{ $reservation->flight->arrival_time->format('d/m/Y') }}</div>
                        </div>
                    </div>
                    
                    @if($reservation->flight->aircraft_type)
                    <div style="text-align: center; margin-top: 10px; color: #6B7280; font-size: 11px;">
                        Appareil: {{ $reservation->flight->aircraft_type }}
                    </div>
                    @endif
                </div>
                
                <!-- Informations du passager -->
                <div class="section">
                    <div class="section-title">Informations du passager</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-cell">
                                <div class="info-label">Nom du passager</div>
                                <div class="info-value" style="font-size: 16px;">{{ strtoupper($reservation->getFullPassengerName()) }}</div>
                            </div>
                            <div class="info-cell">
                                <div class="info-label">Contact</div>
                                <div class="info-value">{{ $reservation->passenger_phone }}</div>
                            </div>
                        </div>
                        @if($reservation->passenger_email)
                        <div class="info-row">
                            <div class="info-cell" colspan="2">
                                <div class="info-label">Email</div>
                                <div class="info-value" style="font-size: 11px;">{{ $reservation->passenger_email }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Sièges -->
                <div class="section">
                    <div class="section-title">Sièges assignés</div>
                    <div class="seats-container">
                        @foreach($reservation->seats as $seat)
                        <div class="seat-badge">
                            {{ $seat->seat_number }}
                            <span style="font-size: 10px; font-weight: normal; display: block; color: #6366F1;">
                                {{ ucfirst($seat->seat_class) }}
                                @if($seat->is_window) • Fenêtre @endif
                                @if($seat->is_aisle) • Couloir @endif
                                @if($seat->is_emergency_exit) • Issue de secours @endif
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Détails du paiement -->
                <div class="section">
                    <div class="section-title">Détails du paiement</div>
                    <div class="price-breakdown">
                        <div class="price-row">
                            <span>Prix du vol</span>
                            <span>{{ number_format($reservation->flight->price, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @if($reservation->seats->sum('extra_charge') > 0)
                        <div class="price-row">
                            <span>Frais de sièges</span>
                            <span>{{ number_format($reservation->seats->sum('extra_charge'), 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        <div class="price-row total">
                            <span>Total payé</span>
                            <span>{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    @if($reservation->payment)
                    <div style="margin-top: 10px; font-size: 10px; color: #6B7280;">
                        Méthode de paiement: {{ ucfirst(str_replace('_', ' ', $reservation->payment->payment_method)) }} •
                        Date: {{ $reservation->payment->payment_date->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
                
                <!-- Code QR et code-barres -->
                <div class="qr-section">
                    <div class="section-title">Code de vérification</div>
                    <div style="font-size: 10px; color: #6B7280; margin-bottom: 10px;">
                        Scannez ce code à l'enregistrement
                    </div>
                    <!-- QR Code généré -->
                    <div class="qr-code">
                        @if(isset($qrCode))
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 130px; height: 130px;"/>
                        @else
                        <!-- Fallback si QR code non disponible -->
                        <svg width="130" height="130" viewBox="0 0 130 130">
                            <rect width="130" height="130" fill="white"/>
                            <!-- Simulation d'un QR Code simple -->
                            @for($i = 0; $i < 13; $i++)
                                @for($j = 0; $j < 13; $j++)
                                    @if(($i + $j) % 2 == 0 || ($i * $j) % 3 == 0)
                                    <rect x="{{ $i * 10 }}" y="{{ $j * 10 }}" width="10" height="10" fill="black"/>
                                    @endif
                                @endfor
                            @endfor
                        </svg>
                        @endif
                    </div>
                    <div class="barcode">{{ $reservation->booking_reference }}</div>
                </div>
                
                <!-- Informations importantes -->
                <div class="important-info">
                    <h3>⚠️ INFORMATIONS IMPORTANTES</h3>
                    <ul>
                        <li>Présentez-vous à l'aéroport 2 heures avant le départ pour les vols internationaux</li>
                        <li>Munissez-vous d'une pièce d'identité valide (passeport ou CNI)</li>
                        <li>Vérifiez les restrictions de bagages auprès de la compagnie aérienne</li>
                        <li>L'embarquement débute 45 minutes avant le départ et se termine 15 minutes avant</li>
                        <li>Ce billet est personnel et non transférable</li>
                        <li>En cas d'annulation, contactez-nous au moins 24h avant le départ</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <p><strong>FlightBook</strong> - Système de réservation de vols</p>
            <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
            <p>Pour toute assistance, contactez notre service client</p>
            <p style="margin-top: 10px; font-size: 9px;">
                Ce document est votre preuve d'achat. Conservez-le précieusement jusqu'à la fin de votre voyage.
            </p>
        </div>
    </div>
</body>
</html>