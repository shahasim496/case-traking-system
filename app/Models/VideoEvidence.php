<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoEvidence extends Model
{
    use HasFactory;

      protected $table = 'video_evidences';

    protected $fillable = [
        'evidence_id',         // Foreign key to the evidence table
        'extraction_date',     // Date of video extraction
        'extracted_from',      // Source of the video
        'extraction_method',   // Method used for extraction
        'storage_media',       // Storage media used
        'retrieved_by',        // Person who retrieved the video
        'contact',             // Contact details of the retriever
        'num_cameras',         // Number of cameras
        'num_videos',          // Number of videos
        'total_length',        // Total length of the videos
    ];
}
