<?php

use App\Jobs\UpdateUserBalanceJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class BalanceUpdateTest extends TestCase
{
    /**
     * 测试余额更新操作
     * 验证队列任务的正确调度
     */
    public function test_concurrent_updates()
    {
        // 创建测试用户
        $user = User::factory()->create(['balance' => 100]);
        // 模拟队列环境
        Bus::fake();

        // 模拟5个并发请求
        for ($i = 0; $i < 5; $i++) {
            $this->get("/update-balance/{$user->id}/10");
        }

        // 验证作业正确分发
        Bus::assertDispatchedTimes(UpdateUserBalanceJob::class, 5);
    }
}