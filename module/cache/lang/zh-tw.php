<?php
$lang->cache->setting      = '緩存設置';
$lang->cache->clear        = '清除緩存';
$lang->cache->clearSuccess = '清除成功';
$lang->cache->status       = '緩存狀態';
$lang->cache->driver       = '緩存服務';
$lang->cache->namespace    = '命名空間';
$lang->cache->scope        = '服務範圍';
$lang->cache->memory       = '內存使用';
$lang->cache->usedMemory   = '總計 %s，已使用 %s';

$lang->cache->statusList[1] = '開啟';
$lang->cache->statusList[0] = '關閉';

$lang->cache->driverList['apcu']  = 'APCu';
$lang->cache->driverList['redis'] = 'Redis';

$lang->cache->scopeList['private'] = '本應用獨享';
$lang->cache->scopeList['shared']  = '多應用共享';

$lang->cache->apcu = new stdClass();
$lang->cache->apcu->notice     = '使用 APCu 緩存需要先加載 APCu 擴展。';
$lang->cache->apcu->notLoaded  = '請加載 APCu 擴展後再開啟數據緩存';
$lang->cache->apcu->notEnabled = '請啟用 apc.enabled 選項後再開啟數據緩存';

$lang->cache->redis = new stdClass();
$lang->cache->redis->host                 = 'Redis 主機';
$lang->cache->redis->port                 = 'Redis 連接埠';
$lang->cache->redis->username             = 'Redis 用戶名';
$lang->cache->redis->password             = 'Redis 密碼';
$lang->cache->redis->database             = 'Redis 資料庫';
$lang->cache->redis->serializer           = 'Redis 序列化器';
$lang->cache->redis->notice               = '使用 Redis 緩存需要先加載 Redis 擴展。';
$lang->cache->redis->notLoaded            = '請加載 Redis 擴展後再開啟數據緩存。';
$lang->cache->redis->igbinaryNotLoaded    = '請加載 igbinary 擴展後再開啟數據緩存。';
$lang->cache->redis->igbinaryNotSupported = 'Redis 未啟用 igbinary 支持。請更改序列化器。';

$lang->cache->redis->serializerList['php']      = 'PHP 內置序列化器';
$lang->cache->redis->serializerList['igbinary'] = 'igbinary';

$lang->cache->redis->tips = new stdClass();
$lang->cache->redis->tips->host       = '填寫域名或 IP 地址，無需填寫協議和連接埠號。';
$lang->cache->redis->tips->database   = '填寫 Redis 資料庫的編號，預設為 0。';
$lang->cache->redis->tips->serializer = '數據需要序列化後緩存。更改序列化器會清空緩存數據。';

$lang->cache->tips = new stdClass();
$lang->cache->tips->namespace = '命名空間用來防止不同應用間緩存數據衝突。啟用緩存後更改命名空間會清空緩存數據。';
$lang->cache->tips->scope     = '如果緩存服務只有本應用使用請選擇『本應用獨享』，否則選擇『多應用共享』。';
