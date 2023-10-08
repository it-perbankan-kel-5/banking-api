<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use function Laravel\Prompts\error;

class TransferController extends Controller
{
    public function transfer(Request $request) {
        $sender = request()->user();

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'transfer_amount' => 'required',
            'pin' => 'required'
        ]);

        if ($validator->fails()) {
            error($validator->errors());
            return response()->json(['error' => $validator->errors()], 400);
        }

        $receiver = User::query()->where('email', $request->email);

        if ($receiver->count() == 0)
            return response()->json([
                'error' => "The account that you've tried to transfer is doesn't exist. Please check again"
            ], 400);

        if($sender->balance < $request->transfer_amount)
            return response()->json([
                'error' => 'Your balance is less than transfer amount.'
            ], 400);

        if($sender->card_pin != $request->pin)
            return response()->json([
                'error' => 'Wrong pin. Please Check again'
            ], 400);

        try {
            DB::transaction(function () use ($sender, $receiver, $request) {
                $receiverId = $receiver->get('id')[0]->id;
                $receiverBalance = $receiver->get('balance')[0]->balance;

                $result = Transfer::query()->insertGetId(['user_id' => $sender->id]);
                $transfer = Transfer::query()->findOrFail($result);
                $transfer->user()->syncWithPivotValues([$receiverId], [
                    'date' => date('Y-m-d'),
                    'time' => date('h:i:s'),
                    'amount' => $request->transfer_amount
                ]);

                User::withoutTimestamps(function () use ($sender, $request, $receiverBalance, $receiverId) {
                    $ids = [$sender->id, $receiverId];
                    $balances = [
                        $sender->balance - $request->transfer_amount,
                        $receiverBalance + $request->transfer_amount
                    ];

                    for ($i = 0; $i < (sizeof($ids)+sizeof($balances))/2; $i++)
                        User::query()->where('id', $ids[$i])->update(['balance' => $balances[$i]]);
                });
            });

            return response()->json([
                'status' => "success",
                'email_receiver' => $request->email,
                'transfer_amount' => $request->transfer_amount
            ]);
        } catch (Throwable $t) {
            error($t->getMessage());
            return response()->json([
                'error' => $t->getMessage()
            ], 500);
        }
    }
}
