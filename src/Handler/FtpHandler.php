<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/SForum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/SForum/blob/master/LICENSE
 */
namespace App\Plugins\Barrel\src\Handler;

use App\Plugins\Core\src\Handler\Store\FileStoreHandlerInterface;

class FtpHandler implements FileStoreHandlerInterface
{
    public function handler(array $data, \Closure $next)
    {
        if (! arr_has($data, 'ftp') || ! is_array($data['ftp'])) {
            return $next($data);
        }
        foreach ($data['ftp'] as $key => $item) {
            set_options('upload_ftp_' . $key, $item);
        }
        return $next($data);
    }
}
