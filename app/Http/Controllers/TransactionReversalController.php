<?php

namespace App\Http\Controllers;

use App\Models\CustomerTransaction;
use App\Models\Transaction;
use App\Services\TransactionReversalService;
use Illuminate\Http\Request;

class TransactionReversalController extends Controller
{
    public function __construct(private TransactionReversalService $reversalService)
    {
    }

    public function reverseCustomerTransaction(Request $request, $transactionId)
    {
        if ($data =  $this->reversalService->reverseCustomerTransaction($transactionId)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
        
    }

    public function reverseBranchTransaction(Request $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        if ($data =  $this->reversalService->reverseBranchTransaction($transaction)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
        // return $this->reversalService->reverseBranchTransaction($transaction);
    }
}
