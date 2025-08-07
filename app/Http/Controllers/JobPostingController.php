<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobPosting;
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
        $jobPostings = JobPosting::with('department', 'createdBy')
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
        return view('job-posting.create', compact('departments'));
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
                'description' => 'required|string',
                'requirements' => 'required|string',
                'positions' => 'required|integer|min:5',
                'deadline' => 'required|date|after:' . now()->addDays(1)->format('Y-m-d'),
                'status' => 'required|in:active,inactive,draft',
            ], [
                'deadline.after' => 'The deadline must be at least 15 days from today.',
                'department_id.exists' => 'The selected department is invalid.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', $e->getMessage());
        }

        // If validation fails, it will automatically redirect back with errors
     

        try {
            // Create the job posting
            $jobPosting =JobPosting::create([
                'title' => $request->title,
                'department_id' => $request->department_id,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'positions' => $request->positions,
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
        return view('job-posting.edit', compact('jobPosting', 'departments'));
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
            'description' => 'required|string',
            'requirements' => 'required|string',
            'positions' => 'required|integer|min:1',
            'deadline' => 'required|date|after:' . now()->addDays(14)->format('Y-m-d'),
            'status' => 'required|in:active,inactive,draft',
        ], [
            'deadline.after' => 'The deadline must be at least 15 days from today.',
            'department_id.exists' => 'The selected department is invalid.',
        ]);

        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->update([
                'title' => $request->title,
                'department_id' => $request->department_id,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'positions' => $request->positions,
                'deadline' => $request->deadline,
                'status' => $request->status,
            ]);

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
}
