<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->date)->format('F Y');
            });

        $summary = [];

        foreach ($transactions as $month => $trans) {
            $income = $trans->where('type', 'income')->sum('amount');
            $expense = $trans->where('type', 'expense')->sum('amount');

            $summary[$month] = [
                'income' => $income,
                'expense' => $expense,
                'remaining' => $income - $expense,
                'list' => $trans
            ];
        }

        return view('transactions.index', compact('summary'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added!');
    }
}
