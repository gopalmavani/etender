<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfData extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tender_no', 'tender_title', 'publishing_time','closing_time','bid_end_time','bid_opening_time','ministry_state_name','department_name','organization_name','estimated_bid_value','pdftype','filename','item_category'
    ];

}
