<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\DB;

class UpdateUserBalanceJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected float $amount)
    {

    }

    /**
     * 定义队列中间件
     * 使用 WithoutOverlapping 保证同一用户的余额更新操作唯一性
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->user->id))
                ->expireAfter(60)    // 锁定最大持续时间（秒）
                ->releaseAfter(5)    // 重试间隔时间（秒）
        ];
    }

    /**
     * 使用数据库事务 + 行级锁保证数据一致性
     */
    public function handle(): void
    {
        DB::transaction(function () {
            // 锁定用户记录防止并发修改
            $this->user->lockForUpdate();
            // 原子性更新余额
            $this->user->increment('balance', $this->amount);
        });
    }
}
