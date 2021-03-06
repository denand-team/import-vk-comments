<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2884b3fcc9b59941b88882dfea52d7c0
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'VK\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'VK\\' => 
        array (
            0 => __DIR__ . '/..' . '/vkcom/vk-php-sdk/src/VK',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2884b3fcc9b59941b88882dfea52d7c0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2884b3fcc9b59941b88882dfea52d7c0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
