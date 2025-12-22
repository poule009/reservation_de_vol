<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'reservation_id',
        'numero_billet',
        'pdf_path',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
