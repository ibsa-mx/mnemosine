<?php

namespace Mnemosine;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnemosine\Exhibition;
use Mnemosine\Institution;
use Mnemosine\Venue;
use Mnemosine\Piece;
use Mnemosine\User;
//use Mnemosine\Traits\ActionBy;

class Movement extends Model
{
    use SoftDeletes;//, ActionBy;

    protected $table = 'movements';
    protected $fillable = ['movement_type', 'itinerant', 'institution_ids', 'contact_ids', 'guard_contact_ids', 'exhibition_id', 'venues', 'departure_date', 'observations', 'start_exposure', 'end_exposure', 'pieces_ids', 'authorized_by_collections', 'authorized_by_exhibitions', 'arrival_location_id', 'arrival_date', 'type_arrival', 'pieces_ids_arrived', 'updated_at'];
    protected $dates = [
        'departure_date',
        'start_exposure',
        'end_exposure',
        'arrival_date',
    ];
    protected $casts = [
        'itinerant' => 'boolean',
    ];

    protected $appends = ['pieces', 'exhibition', 'institutions', 'venue', 'authorized_by_user'];

    public function getAuthorizedByUserAttribute(){
        return !empty($this->authorized_by_collections) ? User::find($this->authorized_by_collections) : null;
    }

    public function getPiecesAttribute(){
        return !empty($this->pieces_ids) ? Piece::find(explode(",", $this->pieces_ids)) : null;
    }

    public function getExhibitionAttribute(){
        return Exhibition::find($this->exhibition_id)->toArray();
    }

    public function getInstitutionsAttribute(){
        //return Institution::whereRaw("FIND_IN_SET(id, '".$this->institution_ids."')")->get();
        return !empty($this->institution_ids) ? Institution::find(explode(",", $this->institution_ids)) : null;
    }

    public function getVenueAttribute(){
        //$res = !empty($this->venues) ? Venue::whereRaw("FIND_IN_SET(id, '".$this->venues."')")->get() : null;
        $res = !empty($this->venues) ? Venue::find(explode(",", $this->venues)) : null;
        return $res;
    }
}
