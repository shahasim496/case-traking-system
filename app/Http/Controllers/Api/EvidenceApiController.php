<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evidence;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EvidenceApiController extends Controller
{
    /**
     * Get all evidences with pagination and filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllEvidences($key): JsonResponse
    {

    

if($key == '1211'){ 

        $query = Evidence::query()->orderBy('created_at', 'desc');   

        // Paginate the results with relationships
        $evidences = $query->with([
            'case',
            'chainOfCustodies',
            'dnaDonors',
            'ballisticsEvidence',
            'currencyEvidence',
            'toxicologyEvidence',
            'videoEvidence',
            'questionedDocumentEvidence',
            'generalEvidence',
            'evoOfficer',
            'statusUpdatedBy'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $evidences,
            'message' => 'Evidences retrieved successfully'
        ]);
    }else{
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid key'
        ]);
    }
}

    /**
     * Get a specific evidence by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getEvidenceById(int $id,$key2): JsonResponse
    {
        if($key2 == '1211'){
        $evidence = Evidence::with([
            'case',
            'chainOfCustodies',
            'dnaDonors',
            'ballisticsEvidence',
            'currencyEvidence',
            'toxicologyEvidence',
            'videoEvidence',
            'questionedDocumentEvidence',
            'generalEvidence',
            'evoOfficer',
            'statusUpdatedBy'
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $evidence,
            'message' => 'Evidence retrieved successfully'
        ]);
    }else{
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid key'
        ]);
    }
        }

    /**
     * Get evidence statistics and reports
     *
     * @return JsonResponse
     */
    public function reports($key): JsonResponse
    {
        if($key == '1211'){
        // Evidence Statistics
        $totalEvidence = Evidence::count();
        $pendingEvidence = Evidence::where('status', 'pending')->count();
        $verifiedEvidence = Evidence::where('status', 'verified')->count();
        $completedEvidence = Evidence::where('status', 'completed')->count();
        $inProgressEvidence = Evidence::where('status', 'inprogress')->count();
        $awaitingVerificationEvidence = Evidence::where('status', 'Awating Verification')->count();
        
        // Evidence by types
        $dnaEvidence = Evidence::where('type', 'dna')->count();
        $ballisticsEvidence = Evidence::where('type', 'Ballistics')->count();
        $currencyEvidence = Evidence::where('type', 'Currency')->count();
        $toxicologyEvidence = Evidence::where('type', 'Toxicology')->count();
        $videoEvidence = Evidence::where('type', 'Video Evidence')->count();
        $questionedEvidence = Evidence::where('type', 'questioned')->count();
        $generalEvidence = Evidence::where('type', 'general')->count();

   

        return response()->json([
            'status' => 'success',
            'data' => [
                'statistics' => [
                    'total' => $totalEvidence,
                    'by_status' => [
                        'pending' => $pendingEvidence,
                        'verified' => $verifiedEvidence,
                        'completed' => $completedEvidence,
                        'in_progress' => $inProgressEvidence,
                        'awaiting_verification' => $awaitingVerificationEvidence
                    ],
                    'by_type' => [
                        'dna' => $dnaEvidence,
                        'ballistics' => $ballisticsEvidence,
                        'currency' => $currencyEvidence,
                        'toxicology' => $toxicologyEvidence,
                        'video' => $videoEvidence,
                        'questioned' => $questionedEvidence,
                        'general' => $generalEvidence
                    ]
                ],
               
            ],
            'message' => 'Evidence statistics retrieved successfully'
        ]);
    }else{
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid key'
        ]);
    }
        }
} 