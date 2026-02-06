<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Check if paid_amount increased OR status changed to 'paid'
        $amountIncreased = $transaction->wasChanged('paid_amount') && $transaction->paid_amount > $transaction->getOriginal('paid_amount');
        $statusChangedToPaid = $transaction->wasChanged('status') && $transaction->status === 'paid';

        if ($amountIncreased || $statusChangedToPaid) {
            $amount = $amountIncreased 
                ? ($transaction->paid_amount - $transaction->getOriginal('paid_amount'))
                : $transaction->price; // If status changed to paid without amount tracking, assume full price? Or just don't double count? 
                // Let's assume if status changed to 'paid' and amount didn't change, we use the full price/remaining balance?
                // Simplification for now: Use the paid_amount if set, otherwise use price.
            
            if (!$amountIncreased && $statusChangedToPaid) {
                 $amount = $transaction->paid_amount > 0 ? $transaction->paid_amount : $transaction->price;
            }

            // Create Journal Header
            $journal = $transaction->journal()->create([
                'date' => now(),
                'description' => 'Payment Packet ' . $transaction->packet->name,
                'reference_number' => 'JRN-' . time() . '-' . $transaction->id,
            ]);

            // Default CoA Codes (Assumed)
            $bankCoa = \App\Models\ChartOfAccount::where('code', '1101')->first();
            $unearnedRevCoa = \App\Models\ChartOfAccount::where('code', '2101')->first();

            if ($bankCoa && $unearnedRevCoa) {
                // Debit Bank
                $journal->details()->create([
                    'chart_of_account_id' => $bankCoa->id,
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => 'Payment Received',
                ]);

                // Credit Unearned Revenue
                $journal->details()->create([
                    'chart_of_account_id' => $unearnedRevCoa->id,
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => 'Unearned Revenue',
                ]);
            }

            // --- USER LOGIC TEST ---
            // 2. Inventory Deduction (Logic: 1 Packet = 1 Suitcase, 1 Ihram)
            // Simplified: We deduct 1 'Suitcase' per Transaction for now. 
            // Real world: PacketComponent check.
            $suitcase = \App\Models\Inventory::where('item_name', 'Suitcase')->first();
            if ($suitcase && $suitcase->stock >= 1) {
                $suitcase->decrement('stock', 1);
            }

            // 3. Commission Creation (Logic: Rp 500k per transaction)
            if ($transaction->agent_id) { // Assuming Transaction has agent_id
                 \App\Models\Commission::create([
                    'agent_id' => $transaction->agent_id,
                    'transaction_id' => $transaction->id,
                    'amount' => 500000, // Fixed 500k
                    'status' => 'pending',
                    'description' => 'Commission for ' . $transaction->code
                 ]);
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
