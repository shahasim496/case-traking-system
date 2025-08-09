<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobPosting;
use App\Models\Designation;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobPostings = JobPosting::with('department', 'designation', 'createdBy')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('job-posting.index', compact('jobPostings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('job-posting.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,id',
                'designation_id' => 'required|exists:designations,id',
                'pay_scale' => 'required|string|max:50',
                'job_type' => 'required|in:full_time,part_time,contract,temporary,internship',
                'gender' => 'required|in:any,male,female',
                'job_advertisement' => 'required|mimes:pdf|max:10240', // 10MB max
                'description' => 'required|string',
                'requirements' => 'required|string',
                'positions' => 'required|integer|min:1',
                'age_limit' => 'required|integer|min:18|max:65',
                'deadline' => 'required|date|after:' . now()->addDays(1)->format('Y-m-d'),
                'status' => 'required|in:active,inactive,draft',
            ], [
                'deadline.after' => 'The deadline must be at least 15 days from today.',
                'department_id.exists' => 'The selected department is invalid.',
                'designation_id.exists' => 'The selected designation is invalid.',
                'job_advertisement.required' => 'Job advertisement PDF is required.',
                'job_advertisement.mimes' => 'Job advertisement must be a PDF file.',
                'job_advertisement.max' => 'Job advertisement file size must not exceed 10MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', $e->getMessage());
        }

        // If validation fails, it will automatically redirect back with errors
     

        try {
            // Handle file upload
            $advertisementPath = null;
            if ($request->hasFile('job_advertisement')) {
                $file = $request->file('job_advertisement');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $advertisementPath = $file->storeAs('job_advertisements', $fileName, 'public');
            }

            // Create the job posting
            $jobPosting = JobPosting::create([
                'title' => $request->title,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'pay_scale' => $request->pay_scale,
                'job_type' => $request->job_type,
                'gender' => $request->gender,
                'job_advertisement' => $advertisementPath,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'positions' => $request->positions,
                'age_limit' => $request->age_limit,
                'deadline' => $request->deadline,
                'status' => $request->status,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('job-posting.index')
                ->with('success', 'Job posting created successfully!');

        } catch (\Exception $e) {
            return redirect()->route('job.posting')
                ->withInput()
                ->with('error', 'Failed to create job posting. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobPosting = JobPosting::with('department', 'createdBy')->findOrFail($id);
        return view('job-posting.show', compact('jobPosting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jobPosting = JobPosting::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();
        return view('job-posting.edit', compact('jobPosting', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'pay_scale' => 'required|string|max:50',
            'job_type' => 'required|in:full_time,part_time,contract,temporary,internship',
            'gender' => 'required|in:any,male,female',
            'job_advertisement' => 'nullable|mimes:pdf|max:10240', // Optional for update
            'description' => 'required|string',
            'requirements' => 'required|string',
            'positions' => 'required|integer|min:1',
            'age_limit' => 'required|integer|min:18|max:65',
            'deadline' => 'required|date|after:' . now()->addDays(14)->format('Y-m-d'),
            'status' => 'required|in:active,inactive,draft',
        ], [
            'deadline.after' => 'The deadline must be at least 15 days from today.',
            'department_id.exists' => 'The selected department is invalid.',
            'designation_id.exists' => 'The selected designation is invalid.',
            'job_advertisement.mimes' => 'Job advertisement must be a PDF file.',
            'job_advertisement.max' => 'Job advertisement file size must not exceed 10MB.',
        ]);

        try {
            $jobPosting = JobPosting::findOrFail($id);
            
            // Handle file upload if new file is provided
            $updateData = [
                'title' => $request->title,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'pay_scale' => $request->pay_scale,
                'job_type' => $request->job_type,
                'gender' => $request->gender,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'positions' => $request->positions,
                'age_limit' => $request->age_limit,
                'deadline' => $request->deadline,
                'status' => $request->status,
            ];

            if ($request->hasFile('job_advertisement')) {
                // Delete old file if exists
                if ($jobPosting->job_advertisement && \Storage::disk('public')->exists($jobPosting->job_advertisement)) {
                    \Storage::disk('public')->delete($jobPosting->job_advertisement);
                }
                
                // Upload new file
                $file = $request->file('job_advertisement');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $advertisementPath = $file->storeAs('job_advertisements', $fileName, 'public');
                $updateData['job_advertisement'] = $advertisementPath;
            }

            $jobPosting->update($updateData);

            return redirect()->route('job-posting.index')
                ->with('success', 'Job posting updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update job posting. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->delete();

            return redirect()->route('job-posting.index')
                ->with('success', 'Job posting deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('job-posting.index')
                ->with('error', 'Failed to delete job posting. Please try again.');
        }
    }

    /**
     * Display a listing of active job postings for public view.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicIndex(Request $request)
    {
        $query = JobPosting::with('department', 'designation')
            ->where('status', 'active')
            ->where('deadline', '>=', now())
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%")
                  ->orWhereHas('department', function($dept) use ($search) {
                      $dept->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobPostings = $query->paginate(3);
        
        return view('careers.index', compact('jobPostings'));
    }

    /**
     * Display the specified job posting for public view.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function publicShow($id)
    {
        $jobPosting = JobPosting::with('department', 'designation')
            ->where('status', 'active')
            ->where('deadline', '>=', now())
            ->findOrFail($id);
            
        return view('careers.show', compact('jobPosting'));
    }
}
