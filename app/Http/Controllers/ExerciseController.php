<?php

namespace App\Http\Controllers;

use App\Models\Muscle;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');

        $categories = ['arm' => 'Arms', 'body' => 'Body', 'leg' => 'Legs'];

        $muscles = $category === 'all'
            ? Muscle::orderBy('name')->get()
            : Muscle::where('category', $category)->orderBy('name')->get();

        return view('exercises.index', compact('categories', 'muscles', 'category'));
    }
}