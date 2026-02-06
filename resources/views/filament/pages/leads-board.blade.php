    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($statuses as $status => $leads)
            <div class="bg-gray-100 p-4 rounded-lg dark:bg-gray-800">
                <h3 class="font-bold text-lg mb-4 capitalize">{{ $status }}</h3>
                <div class="space-y-4">
                    @foreach($leads as $lead)
                        <div class="bg-white p-4 rounded shadow dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                            <h4 class="font-semibold">{{ $lead->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $lead->phone }}</p>
                            @if($lead->notes)
                                <p class="text-xs text-gray-500 mt-2 bg-gray-50 p-1 rounded">{{Str::limit($lead->notes, 50)}}</p>
                            @endif
                            <div class="mt-2 text-right">
                                <span class="text-xs text-gray-400">{{ $lead->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
