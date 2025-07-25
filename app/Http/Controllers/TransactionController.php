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
        // $months = array_reduce(range(1, 12), function ($rslt, $m) {
        //     $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
        //     return $rslt;
        // });
        // $this->_viewData['monthList'] = $months;
        $filterMonth = $request->input('month','');
        $filterMonth = !empty($filterMonth) ? $filterMonth : Carbon::now()->month;

        $filterTitle = $request->input('title','');
        $user = Auth::user();


        $currentYear = Carbon::now()->year;

        $transactions = Transaction::where('user_id', $user->id)
            ->whereMonth('date', $filterMonth)
            ->whereYear('date', $currentYear);
            if (!empty($filterTitle)) {
                $filterTitle = strtolower($filterTitle);
                $transactions = $transactions->where('title', 'LIKE', "%{$filterTitle}%");
            }
            $transactions = $transactions->orderBy('date', 'desc')
            ->get()
            ->groupBy(function($item) {
            return Carbon::parse($item->date)->format('F Y');
            });

        $summary = [];

        foreach ($transactions as $month => $trans) {
            $income = $trans->where('type', 'income')->sum('amount');
            $expense = $trans->where('type', 'expense')->sum('amount');
            $cashIncome = $trans->where('type', 'income')->where('method', 'Cash')->sum('amount');
            $cashExpense =$trans->where('type', 'expense')->where('method','Cash')->sum('amount');
            $onlineIncome = $trans->where('type', 'income')->where('method', 'Online')->sum('amount');
            $onlineExpense =$trans->where('type', 'expense')->where('method','Online')->sum('amount');

            $summary[$month] = [
                'income' => $income,
                'expense' => $expense,
                'remaining' => $income - $expense,
                'remaining_cash' => $cashIncome - $cashExpense,
                'remaining_online' => $onlineIncome - $onlineExpense,
                'list' => $trans
            ];
        }

        return view('transactions.index', compact('summary'));
    }

    public function create()
    {
        $data = [];
        return view('transactions.create',compact('data'));
    }
    public function edit($id)
    {
        $data = Transaction::find($id)->toArray();

        return view('transactions.create',compact('data'));
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
            'method' => $request->method,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction added!');
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        Transaction::find($id)->update([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'method' => $request->method,
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated!');
    }
}
