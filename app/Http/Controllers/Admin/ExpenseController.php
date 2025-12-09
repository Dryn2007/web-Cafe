<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with(['ingredient', 'user'])->orderBy('expense_date', 'desc');

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->start_date) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        // Filter by month
        if ($request->month) {
            $query->whereMonth('expense_date', $request->month)
                ->whereYear('expense_date', $request->year ?? now()->year);
        }

        $expenses = $query->paginate(20);
        $categories = Expense::categories();
        $ingredients = Ingredient::orderBy('name')->get();

        // Summary
        $totalExpenses = $query->sum('amount');

        return view('admin.expenses.index', compact('expenses', 'categories', 'ingredients', 'totalExpenses'));
    }

    public function create()
    {
        $categories = Expense::categories();
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.expenses.create', compact('categories', 'ingredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'ingredient_id' => 'nullable|exists:ingredients,id',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        // Jika pembelian bahan baku, tambahkan ke stok
        if ($request->category === Expense::CATEGORY_INGREDIENT && $request->ingredient_id && $request->quantity) {
            $ingredient = Ingredient::find($request->ingredient_id);
            if ($ingredient) {
                $ingredient->increment('stock', $request->quantity);
            }
        }

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat! 💰');
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::categories();
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.expenses.edit', compact('expense', 'categories', 'ingredients'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'ingredient_id' => 'nullable|exists:ingredients,id',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $expense->update([
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'ingredient_id' => $request->ingredient_id,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil diupdate! ✨');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus! 🗑️');
    }
}
