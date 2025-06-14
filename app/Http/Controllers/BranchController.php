<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    public function create(): View
    {
        return view('branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'branchName' => 'required|string|max:255|unique:branch',
            'judge_name' => 'nullable|string|max:255',
        ]);

        Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validate([
            'branchName' => 'required|string|max:255|unique:branch,' . $branch->id,
            'judge_name' => 'nullable|string|max:255',
        ]);

        $branch->update($validated);

        return redirect()->route('branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()->route('branches.index')
            ->with('success', 'Branch deleted successfully.');
    }
}
