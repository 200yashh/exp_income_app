<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Transaction</h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto">
        <form method="POST" action="{{ route('transactions.store') }}">
            @csrf

            <div class="mb-4">
                <label>Type</label>
                <select name="type" class="w-full border px-4 py-2">
                    <option value="expense">Expense</option>
                    <option value="income">Income</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Method</label>
                <select name="method" class="w-full border px-4 py-2">
                    <option value="Cash">Cash</option>
                    <option value="Online">Online</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Title</label>
                <input type="text" name="title" class="w-full border px-4 py-2" required>
            </div>

            <div class="mb-4">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="w-full border px-4 py-2" required autocomplete="off">
            </div>

            <div class="mb-4">
                <label>Date</label>
                <input type="date" name="date" class="w-full border px-4 py-2" required>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
