<div class="space-y-4">
    <div class="text-xl font-bold">{{ $deal->name }}</div>

    <div class="text-gray-700 dark:text-gray-300">
        Сумма: <span class="font-semibold">{{ $deal->amount }} $</span>
    </div>

    <div class="border-t pt-3">
        <h3 class="font-semibold mb-2">О клиенте</h3>
        <p>{{ $deal->client?->name }}</p>
    </div>
</div>
