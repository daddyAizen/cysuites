<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();

        return Inertia::render('Menu/Index', [
            'menus' => $menus,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'picture' => 'nullable|image|max:2048',
            'is_out_of_stock' => 'boolean',
        ]);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('menu_pictures', 'public');
            $validated['picture'] = $path;
        }

        Menu::create($validated);

        return redirect()->back()->with('success', 'Menu added successfully.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'picture' => 'nullable|image|max:2048',
            'is_out_of_stock' => 'boolean',
        ]);

        if ($request->hasFile('picture')) {
            if ($menu->picture) {
                Storage::disk('public')->delete($menu->picture);
            }
            $validated['picture'] = $request->file('picture')->store('menu_pictures', 'public');
        }

        $menu->update($validated);

        return redirect()->back()->with('success', 'Menu updated successfully.');
    }

    public function toggleStock(Menu $menu)
    {
        $menu->is_out_of_stock = !$menu->is_out_of_stock;
        $menu->save();

        return redirect()->back()->with('success', 'Menu stock status updated.');
    }
}
