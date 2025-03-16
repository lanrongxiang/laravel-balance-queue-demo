<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUserBalanceJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BalanceController extends Controller
{
    /**
     * 处理余额更新请求
     *
     * @param User  $user
     * @param float $amount 变更金额
     *
     * @return JsonResponse 立即返回队列接收响应
     */
    public function updateBalance(User $user, float $amount): JsonResponse
    {
        UpdateUserBalanceJob::dispatch($user, $amount)->onQueue('balance_updates');
        return response()->json(['status' => '更新余额成功']);
    }
}