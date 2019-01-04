<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getSql')) {
    /**
     * 调试输出 SQL 语句
     * 使用方法:
     * 在查询方法前调用即可
     * getSql();
     * Eloquent
     * 源:https://laravel-china.org/articles/5593/debug-output-sql-statement-in-laravel-55
     */
    function getSql()
    {
        DB::listen(function ($sql) {
            dump($sql);
            $singleSql = $sql->sql;
            if ($sql->bindings) {
                foreach ($sql->bindings as $replace) {
                    $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                    $singleSql = preg_replace('/\?/', $value, $singleSql, 1);
                }
                dump($singleSql);
            } else {
                dump($singleSql);
            }
        });
    }
}

if (!function_exists('path_url')) {
    function path_url($path)
    {
        if (!empty($path) && strpos($path, '://') === false) {
            return config('app.url').Storage::url($path);
        } else {
            return $path;
        }
    }
}
