# 使用 Laravel 队列功能实现用户余额更新。通过队列、数据库事务和 Supervisor 进程管理



## 功能特性

- ​**队列任务**：使用 Laravel 队列处理余额更新任务。
- ​**唯一性控制**：通过 `WithoutOverlapping` 中间件保证同一用户的余额更新任务唯一性。
- ​**数据库事务**：使用事务和行级锁确保数据一致性。
- ​**Supervisor 管理**：使用 Supervisor 守护队列进程，确保任务持续运行。
- ​**完整测试**：包含单元测试和功能测试，覆盖并发更新场景。

## 代码实现

### 1. 队列任务 (`app/Jobs/UpdateUserBalance.php`)
### 2. 执行更新余额 (`app/Http/Controllers/BalanceController.php`)
### 3. Supervisor 配置
```code
[program:balance-queue]
command=php /var/www/artisan queue:work --queue=balance_updates --sleep=3 --tries=3
user=root
autostart=true
autorestart=true
numprocs=1
stdout_logfile=/var/log/balance-queue.log
