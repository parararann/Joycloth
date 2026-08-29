<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::orderBy('sort_order', 'asc')->orderByDesc('created_at')->get();
        return view('admin.designs.index', compact('designs'));
    }

    public function create()
    {
        return view('admin.designs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('designs', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $data['is_active'] = $request->has('is_active');

        Design::create($data);

        return redirect()->route('admin.desain.index')->with('success', 'Design reference added successfully.');
    }

    public function edit(Design $desain)
    {
        return view('admin.designs.edit', ['design' => $desain]);
    }

    public function update(Request $request, Design $desain)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($desain->image) {
                Storage::disk('public')->delete('designs/' . $desain->image);
            }
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('designs', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $data['is_active'] = $request->has('is_active');

        $desain->update($data);

        return redirect()->route('admin.desain.index')->with('success', 'Design reference updated successfully.');
    }

    public function destroy(Design $desain)
    {
        if ($desain->image) {
            Storage::disk('public')->delete('designs/' . $desain->image);
        }
        $desain->delete();

        return redirect()->route('admin.desain.index')->with('success', 'Design reference deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:designs,id'
        ]);

        foreach ($request->ids as $index => $id) {
            Design::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
