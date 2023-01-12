<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $year = now()->year;
        if ($request->query('year')) {
            $year = $request->query('year');
        }
        $products = Product::all(['id']);
        $suppliers = Supplier::all(['id']);
        $types = Type::all(['id']);
        $users = User::role(['admin', 'warehouse'])->select('id')->get();
        $incomingTransactions = Transaction::where('status', true)->select(['id', 'created_at'])->whereYear('created_at', $year)->get();
        $outgoingTransactions = Transaction::where('status', false)->select(['id', 'created_at'])->whereYear('created_at', $year)->get();;
        $results = [
            [
                'title' => 'Products',
                'value' => count($products),
                'icon' => 'mdi mdi-folder',
                'link' => '/products'
            ],
            [
                'title' => 'Suppliers',
                'value' => count($suppliers),
                'icon' => 'mdi mdi-factory',
                'link' => '/suppliers'
            ],
            [
                'title' => 'Types',
                'value' => count($types),
                'icon' => 'mdi mdi-all-inclusive',
                'link' => '/types'
            ],
            [
                'title' => 'Users',
                'value' => count($users),
                'icon' => 'mdi mdi-account-group',
                'link' => '/users'
            ],
        ];
        $yearIncomingTransactions = [];
        $yearOutgoingTransactions  = [];
        for ($i = 1; $i <= 12; $i++) {
            $resultIncoming = 0;
            $resultOutgoing = 0;
            foreach ($incomingTransactions as $transaction) {
                if ((int) $transaction->created_at->format('m') === $i) {
                    $resultIncoming += 1;
                }
            }
            foreach ($outgoingTransactions as $transaction) {
                if ((int) $transaction->created_at->format('m') === $i) {
                    $resultOutgoing += 1;
                }
            }
            $yearIncomingTransactions[] = $resultIncoming;
            $yearOutgoingTransactions[] = $resultOutgoing;
        }
        return view('dashboard', [
            'results' => $results,
            'incomingTransactions' => $yearIncomingTransactions,
            'outgoingTransactions' => $yearOutgoingTransactions,
            'transactions' => [count($incomingTransactions), count($outgoingTransactions)]
        ]);
    }
}
