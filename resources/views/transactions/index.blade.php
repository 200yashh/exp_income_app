<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Monthly Income & Expense Summary</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('transactions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-6 inline-block">➕ Add Transaction</a>
        <form method="GET" action="{{ route('transactions.index') }}" class="mb-6 flex items-center gap-2">
            <label for="month" class="font-medium">Filter by Month:</label>
            <select name="month" id="month" class="border rounded px-2 py-1">
            <option value="">All</option>
            @foreach([1 => 'January',2 => 'February',3 => 'March',4 => 'April',5 => 'May',6 => 'June',7 => 'July',8 => 'August',9 => 'September',10 => 'October',11 => 'November',12 => 'December',] as $key => $title)
                <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>{{ $title }}</option>
            @endforeach
            </select>
            <input type="text" name="title" id="title" placeholder="title" value="{{ Request::input('title') }}">
            <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded">Filter</button>
            <a href="{{ route('transactions.index') }}" class="bg-gray-700 text-white px-3 py-1 rounded" >Reset</a>
        </form>

        @foreach($summary as $month => $data)
            <div class="bg-white shadow-md rounded p-4 mb-8">
                <h3 class="text-xl font-bold mb-2">{{ $month }}</h3>
                <p class="mb-4">
                    <span class="text-green-600">Income: ₹{{ number_format($data['income'], 2) }}</span> | 
                    <span class="text-red-600">Expense: ₹{{ number_format($data['expense'], 2) }}</span> | 
                </p>
                <hr>
                <p class="mb-4">
                    <span class="text-blue-600 font-semibold">Remaining Total: ₹{{ number_format($data['remaining'], 2) }}</span><br>
                    <span class="text-blue-600 font-semibold">Cash: ₹{{ number_format($data['remaining_cash'], 2) }}</span> | 
                    <span class="text-blue-600 font-semibold">Online: ₹{{ number_format($data['remaining_online'], 2) }}</span>
                </p>
                

                <div class="overflow-x-auto">
                <table id="table-{{ Str::slug($month) }}" class="display table-auto border border-gray-300 w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2 text-left">Id</th>
                            <th class="border px-4 py-2 text-left">Date</th>
                            <th class="border px-4 py-2 text-left">Type</th>
                            <th class="border px-4 py-2 text-left">Method</th>
                            <th class="border px-4 py-2 text-left">Title</th>
                            <th class="border px-4 py-2 text-left">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['list'] as $t)
                            <tr>
                                <td class="border px-4 py-2">{{ $t->id }}</td>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($t->date)->format('d M Y') }}</td>
                                <td class="border px-4 py-2">
                                    @if($t->type == 'income')
                                        <span class="text-green-600 font-semibold">Income</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Expense</span>
                                    @endif
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $t->method??'' }}
                                </td>
                                <td class="border px-4 py-2">{{ $t->title }}</td>
                                <td class="border px-4 py-2">₹{{ number_format($t->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            </div>
        @endforeach
    </div>
</x-app-layout>
