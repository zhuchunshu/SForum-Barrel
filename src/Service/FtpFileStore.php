<?php

declare(strict_types=1);
/**
 * This file is part of zhuchunshu.
 * @link     https://github.com/zhuchunshu
 * @document https://github.com/zhuchunshu/SForum
 * @contact  laravel@88.com
 * @license  https://github.com/zhuchunshu/SForum/blob/master/LICENSE
 */
namespace App\Plugins\Barrel\src\Service;

use App\Plugins\Core\src\Annotation\FileStoreAnnotation;
use App\Plugins\Core\src\Handler\FileStoreInterface;
use App\Plugins\Core\src\Service\FileStoreService;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Illuminate\Support\Str;
use Lazzard\FtpClient\Config\FtpConfig;
use Lazzard\FtpClient\Connection\FtpConnection;
use Lazzard\FtpClient\Connection\FtpSSLConnection;
use Lazzard\FtpClient\Exception\ConfigException;
use Lazzard\FtpClient\Exception\ConnectionException;
use Lazzard\FtpClient\FtpClient;
use Lazzard\FtpClient\FtpWrapper;

#[FileStoreAnnotation]
class FtpFileStore implements FileStoreInterface
{
    public function name(): string
    {
        return 'FTP';
    }

    public function handler(): string
    {
        return \App\Plugins\Barrel\src\Handler\FtpHandler::class;
    }

    public function view(): string
    {
        return 'Barrel::ftp';
    }

    public function save(UploadedFile $file, $folder, $file_prefix = null, $move = false, $path = null): array
    {
        $client = $this->ftp();

        // 移动文件
        if ($move === true && file_exists($path)) {
            $filename = (new \SplFileInfo($path))->getFilename();
            // 目录名
            $folder_name = '/' . get_options('upload_ftp_dir', 'upload') . "/{$folder}/" . date('Ym/d', time());

            // 创建远程目录
            if (! $client->isDir($folder_name)) {
                $client->createDir($folder_name);
            }

            // ftp path
            $ftp_path = $folder_name . '/' . $filename;
            $upload = $client->asyncUpload($path, $ftp_path, function ($state) {
                // do something
            }, true, FtpWrapper::BINARY);
            removeFiles($path);
            if ($upload) {
                $ftp_url = get_options('upload_ftp_url') . $ftp_path;
                return (new FileStoreService())->response(true, $ftp_path, $ftp_url);
            }

            return (new FileStoreService())->response();
        }

        // 不移动文件
        if (! $file_prefix) {
            $file_prefix = Str::random();
        }
        // 获取后缀名
        $extension = strtolower(@$file->getExtension()) ?: 'png';

        // 拼接文件名
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
        // 目录名
        $folder_name = '/' . get_options('upload_ftp_dir', 'upload') . "/{$folder}/" . date('Ym/d', time());
        // 创建远程目录
        if (! $client->isDir($folder_name)) {
            $client->createDir($folder_name);
        }

        // ftp path
        $ftp_path = $folder_name . '/' . $filename;
        $upload = $client->asyncUpload($file->getRealPath(), $ftp_path, function ($state) {
            // do something
        }, true, FtpWrapper::BINARY);
        if ($upload) {
            $ftp_url = get_options('upload_ftp_url') . $ftp_path;
            return (new FileStoreService())->response(true, $ftp_path, $ftp_url);
        }

        return (new FileStoreService())->response();
    }

    /**
     * @throws \Throwable
     * @throws ConnectionException
     * @throws ConfigException
     */
    private function ftp(): FtpClient
    {
        if (! extension_loaded('ftp')) {
            throw new \RuntimeException('FTP extension not loaded.');
        }
        // 主机名
        $host = get_options('upload_ftp_host');
        // 用户名
        $username = get_options('upload_ftp_username');
        // 密码
        $password = get_options('upload_ftp_password');
        // 端口号
        $port = (int) get_options('upload_ftp_port', 21);
        if (get_options('upload_ftp_ssl', '关闭') === '开启') {
            $connection = new FtpSSLConnection($host, $username, $password, $port);
        } else {
            $connection = new FtpConnection($host, $username, $password, $port);
        }
        $connection->open();

        $config = new FtpConfig($connection);
        $config->setPassive(true);

        return new FtpClient($connection);
    }
}
