<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advice; // <- importáld a modellt

class AdviceController extends Controller
{
    public function index()
    {
        return view('advice.index');
    }

    public function calculateBMI(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'required|numeric|min:100|max:250',
        ]);

        $weight = $request->input('weight');
        $height = $request->input('height') / 100;

        $bmi = $weight / ($height * $height);

        $category = match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi < 25 => 'Normal weight',
            $bmi < 30 => 'Overweight',
            default => 'Obese',
        };

        // BMI pozíció kiszámítása
        if ($bmi < 18.5) {
            $position = (($bmi - 15) / (18.5 - 15)) * 25;
        } elseif ($bmi < 25) {
            $position = 25 + (($bmi - 18.5) / (25 - 18.5)) * 25;
        } elseif ($bmi < 30) {
            $position = 50 + (($bmi - 25) / (30 - 25)) * 25;
        } else {
            $position = 75 + min(($bmi - 30) / 10, 1) * 25;
        }

        $position = max(0, min($position, 100));

        // kategória normalizálása az adatbázishoz
        $dbCategory = strtolower(str_replace(' ', '', $category)); 
        if ($dbCategory === 'normalweight') $dbCategory = 'normal';

        $advice = Advice::where('category', $dbCategory)->first();

        return back()->with([
            'bmi' => round($bmi, 1),
            'category' => $category,
            'bmi_position' => $position,
            'advice' => $advice?->content,
            'old_weight' => $weight,
            'old_height' => $request->input('height')
        ]);
    }
}
