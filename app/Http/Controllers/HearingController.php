<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\CourtCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HearingController extends Controller
{
    /**
     * Display the specified hearing.
     */
    public function show($id)
    {
        $hearing = Hearing::with(['courtCase', 'creator'])->findOrFail($id);
        return view('hearings.show', compact('hearing'));
    }

    /**
     * Show the form for editing the specified hearing.
     */
    public function edit($id)
    {
        $hearing = Hearing::with('courtCase')->findOrFail($id);
        return view('hearings.edit', compact('hearing'));
    }

    /**
     * Update the specified hearing in storage.
     */
    public function update(Request $request, $id)
    {
        $hearing = Hearing::findOrFail($id);

        $validatedData = $request->validate([
            'hearing_date' => 'required|date',
            'purpose' => 'nullable|string',
            'person_appearing' => 'nullable|string|max:255',
            'outcome' => 'nullable|string',
            'next_hearing_date' => 'nullable|date',
            'court_order' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['updated_by'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('court_order')) {
                // Delete old court order if exists
                if ($hearing->court_order) {
                    Storage::disk('public')->delete($hearing->court_order);
                }
                
                $file = $request->file('court_order');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('court_orders', $filename, 'public');
                $validatedData['court_order'] = $path;
            }

            $hearing->update($validatedData);

            DB::commit();

            return redirect()->route('hearings.show', $hearing->id)->with('success', 'Hearing updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified hearing from storage.
     */
    public function destroy($id)
    {
        $hearing = Hearing::findOrFail($id);
        $caseId = $hearing->case_id;

        DB::beginTransaction();

        try {
            // Delete court order if exists
            if ($hearing->court_order) {
                Storage::disk('public')->delete($hearing->court_order);
            }

            $hearing->delete();

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Hearing deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

