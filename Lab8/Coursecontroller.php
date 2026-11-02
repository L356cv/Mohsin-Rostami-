<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['user_id'] = $request->user()->id;

        Course::create($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Course registered.');
    }

    public function show(Request $request, int $course)
    {
        $record = $this->ownedCourse($request, $course);

        return $this->dashboard($request, null, $record);
    }

    public function edit(Request $request, int $course)
    {
        $record = $this->ownedCourse($request, $course);

        return $this->dashboard($request, $record);
    }

    public function update(Request $request, int $course)
    {
        $record = $this->ownedCourse($request, $course);

        $data = $this->validatedData($request, $record);

        $record->update($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Course updated.');
    }

    public function destroy(Request $request, int $course)
    {
        $record = $this->ownedCourse($request, $course);

        $record->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Course deleted.');
    }

    private function dashboard(
        Request $request,
        ?Course $editing = null,
        ?Course $viewing = null
    ) {
        $courses = Course::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('dashboard', compact(
            'courses',
            'editing',
            'viewing'
        ));
    }

    private function ownedCourse(Request $request, int $course): Course
    {
        return Course::where('user_id', $request->user()->id)
            ->findOrFail($course);
    }

    private function validatedData(
        Request $request,
        ?Course $course = null
    ): array {
        $code = $request->input('course_code');

        if (is_string($code)) {
            $request->merge([
                'course_code' => strtoupper(trim($code)),
            ]);
        }

        $unique = Rule::unique('courses', 'course_code')
            ->where('user_id', $request->user()->id);

        if ($course !== null) {
            $unique->ignore($course);
        }

        return $request->validate([
            'course_code' => [
                'required',
                'string',
                'max:20',
                $unique,
            ],

            'course_name' => [
                'required',
                'string',
                'max:150',
            ],

            'credit_hours' => [
                'required',
                'integer',
                'between:1,6',
            ],

            'semester' => [
                'required',
                Rule::in([
                    'Spring 2026',
                    'Fall 2026',
                ]),
            ],
        ]);
    }
}
