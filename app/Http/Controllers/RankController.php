<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index()
    {
        $ranks = Rank::all();
        return view('backend.ranks.index', compact('ranks'));
    }

    public function create()
    {
        return view('backend.ranks.create');
    }

    public function store(Request $request)
    {
        $path = null;
        if ($request->hasFile('image')) {
            $img = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Ranks', $img, 'img');
        }

        Rank::create([
            'name' => $request->input('name'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
            'pts' => $request->input('pts'),
            'image' => $path
        ]);

        return redirect()->route('Ranks.index')->with('success', 'Rank created successfully.');
    }

    public function edit($id)
    {
        $rank = Rank::findOrFail($id);

        return view('backend.ranks.edit', compact('rank'));
    }

    public function update(Request $request, $id)
    {
                $rank = Rank::findOrFail($id)->first();

        if ($request->hasFile('image')) {
            $img = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('Ranks', $img, 'img');
            $rank->image = $path;
        }

        $rank->update([
            'name' => $request->input('name'),
            'min' => $request->input('min'),
            'max' => $request->input('max'),
            'pts' => $request->input('pts')
        ]);

        return redirect()->route('Ranks.index')->with('success', 'Rank updated successfully.');
    }

    public function destroy($id)
    {
        $rank = Rank::findOrFail($id);
        $rank->delete();
        return redirect()->route('Ranks.index')->with('success', 'Rank deleted successfully.');
    }
    
}
