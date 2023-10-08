<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;
use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function user_balance_details() {
        try {
            $users = request()->user();
            $income = $this->total_user_income();
            $outcome = $this->total_user_outcome();

            return response()->json([
                'fname' => $users->first_name,
                'lname' => $users->last_name,
                'email' => $users->email,
                'card_number' => $users->card_number,
                'balance' => $users->balance,
                'income' => $income,
                'outcome' => $outcome
            ], 200);
        } catch (Throwable $t) {
            error($t->getMessage());
            return response()->json([
               'error' => $t->getMessage()
            ], 400);
        }

    }

    public function get_user_income_details() {
        try {
            $result = DB::table('users', 'u')
                ->join('transfer as t', 'u.id', '=', 't.user_id')
                ->join('transfer_detail as td', 't.id', '=', 'td.transfer_id')
                ->where('td.user_id', '=', request()->user()->id)
                ->get(['u.first_name', 'u.last_name', 'u.email',
                    'td.date', 'td.time', 'td.amount as transfer_amount']);

            return response()->json([
                $result
            ]);
        } catch (Throwable $t) {
            error($t->getMessage());
            return response()->json([
                'error' => $t->getMessage()
            ], 400);
        }
    }

    public function get_user_outcome_details() {
        try {
            $result = DB::table('users', 'u')
                ->join('transfer_detail as td', 'u.id', '=', 'td.user_id')
                ->join('transfer as t', 't.id', '=', 'td.transfer_id')
                ->where('t.user_id', '=', request()->user()->id)
                ->get(['u.first_name', 'u.last_name', 'u.email',
                    'td.date', 'td.time', 'td.amount as transfer_amount']);

            return response()->json([
                $result
            ]);
        } catch (Throwable $t) {
            error($t->getMessage());
            return response()->json([
                'message' => $t->getMessage()
            ], 400);
        }
    }

    public function get_user_transactions() {
        try {
            $result = DB::table('users', 'u')
                ->join('transfer_detail as td', 'u.id', '=', 'td.user_id')
                ->join('transfer as t', 't.id', '=', 'td.transfer_id')
                ->where('t.user_id', '=', request()->user()->id)
                ->get(['u.first_name', 'u.last_name', 'u.email',
                    'td.date', 'td.time', 'td.amount as transfer_amount']);

            return response()->json([
                $result
            ]);
        } catch (Throwable $t) {
            error($t->getMessage());
            return response()->json([
                'message' => $t->getMessage()
            ], 400);
        }
    }

    private function total_user_income() {
        $accountId = request()->user()->id;
        $user = User::query()->findOrFail($accountId); // sender

        $income = 0;

        foreach ($user->transfer as $transfer) {
            $income += $transfer->pivot->amount;
        }

        return $income;
    }

    private function total_user_outcome() {
        $result = DB::table('users', 'u')
            ->join('transfer_detail', 'u.id', '=', 'transfer_detail.user_id')
            ->join('transfer', 'transfer.id', '=', 'transfer_detail.transfer_id')
            ->where('transfer.user_id', '=', request()->user()->id)
            ->get(['transfer_detail.amount']);

        $outcome = 0;

        foreach ($result as $res)
            $outcome += $res->amount;

        return $outcome;
    }
}
