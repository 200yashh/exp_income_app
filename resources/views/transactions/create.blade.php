<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Transaction</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        @php
            $action = route('transactions.store');
            if (!empty($data) && !empty($data['id'])) {
                $action = route('transactions.update',$data['id']);
            }
            
        @endphp
        <form method="POST" action="{{ $action }}">
            @csrf
            @if (!empty($data) && !empty($data['id']))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label>Type</label>
                <select name="type" class="w-full border px-4 py-2">
                    <option value="expense" {{ isset($data['type']) && $data['type'] =="expense" ? "selected":'' }}>Expense</option>
                    <option value="income" {{ isset($data['type']) && $data['type'] =="income" ? "selected":'' }}>Income</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Method</label>
                <select name="method" class="w-full border px-4 py-2">
                    <option value="Cash" {{ isset($data['method']) && $data['method'] =="Cash" ? "selected":'' }}>Cash</option>
                    <option value="Online" {{ isset($data['method']) && $data['method'] =="Online" ? "selected":'' }}>Online</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Title</label>
                <input type="text" name="title" value="{{ $data['title']??'' }}" class="w-full border px-4 py-2" required>
            </div>

            <div class="mb-4">
                <label>Amount</label>
                <input type="number" step="0.01" value="{{ $data['amount']??'' }}" name="amount" class="w-full border px-4 py-2" required autocomplete="off">
            </div>

            <div class="mb-4">
                <label>Date</label>
                <input type="date" name="date" class="w-full border px-4 py-2" value="{{ $data['date']??date('Y-m-d') }}" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
