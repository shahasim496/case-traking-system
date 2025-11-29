<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\CourtCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * Display the specified notice.
     */
    public function show($id)
    {
        $notice = Notice::with(['courtCase', 'creator'])->findOrFail($id);
        return view('notices.show', compact('notice'));
    }

    /**
     * Show the form for editing the specified notice.
     */
    public function edit($id)
    {
        $notice = Notice::with('courtCase')->findOrFail($id);
        return view('notices.edit', compact('notice'));
    }

    /**
     * Update the specified notice in storage.
     */
    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $validatedData = $request->validate([
            'notice_date' => 'required|date',
            'notice_details' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['updated_by'] = auth()->id();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($notice->attachment) {
                    Storage::disk('public')->delete($notice->attachment);
                }
                
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('notices', $filename, 'public');
                $validatedData['attachment'] = $path;
            }

            $notice->update($validatedData);

            DB::commit();

            return redirect()->route('notices.show', $notice->id)->with('success', 'Notice updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified notice from storage.
     */
    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $caseId = $notice->case_id;

        DB::beginTransaction();

        try {
            // Delete attachment if exists
            if ($notice->attachment) {
                Storage::disk('public')->delete($notice->attachment);
            }

            $notice->delete();

            DB::commit();

            return redirect()->route('cases.show', $caseId)->with('success', 'Notice deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

