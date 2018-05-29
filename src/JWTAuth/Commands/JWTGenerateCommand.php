<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-14 17:02
 */
namespace Notadd\Foundation\JWTAuth\Commands;

use Illuminate\Support\Str;
use Notadd\Foundation\Console\Abstracts\Command;
use Symfony\Component\Yaml\Yaml;

/**
 * Class JWTGenerateCommand.
 */
class JWTGenerateCommand extends Command
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setDescription('Set the JWTAuth secret key used to sign the tokens');
        $this->setName('jwt:generate');
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $key = Str::random(32);
        $file = $this->container->environmentFilePath();
        $this->file->exists($file) || touch($file);
        $data = collect(Yaml::parse($this->file->get($file)));
        $data->put('JWT_SECRET', $key);
        $this->file->put($file, Yaml::dump($data->toArray()));
        $config = [
            'curve_name'       => 'prime256v1',
            'digest_alg'       => 'sha512',
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ];
        $this->output->writeln('<info>当前 PHP 版本为：' . PHP_VERSION .'</info>');
        if (version_compare(PHP_VERSION, '7.1.0') > 0) {
            $this->output->writeln('<info>PHP 7.1 及以上版本将自动创建秘钥对：</info>');
            $this->output->writeln('');
            $res = openssl_pkey_new($config);
            openssl_pkey_export($res, $privateKey);
            $publicKey = openssl_pkey_get_details($res);
            $publicKey = $publicKey['key'];
            file_put_contents($this->container->storagePath() . '/privateKey.pem', $privateKey);
            file_put_contents($this->container->storagePath() . '/publicKey.pem', $publicKey);
            $this->output->writeln('<comment>私钥：</comment>');
            $this->output->writeln('<comment>' . $privateKey . '</comment>');
            $this->output->writeln('<comment>公钥：</comment>');
            $this->output->writeln('<comment>' . $publicKey . '</comment>');
        } else {
            $this->output->writeln('<info>PHP 7.1 以下版本，请自行创建密钥对！</info>');
        }
        $this->container['config']['jwt.secret'] = $key;

        return $this->output->writeln("<info>秘钥初始化成功！</info>");
    }
}
