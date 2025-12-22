# TODO: Fix Null Reference Error in Payment Create View

- [ ] Fix flight number access in payments/create.blade.php: Change `$reservation->vol->numero_vol` to `$reservation->flight->flight_number`
- [ ] Fix seat number access in payments/create.blade.php: Change `$reservation->siege->numero_siege` to `$reservation->seats->first()->numero_siege ?? 'Non défini'`
