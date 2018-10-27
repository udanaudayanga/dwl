<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit84a7c81bfcd086f29f343af6de7a676f
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit84a7c81bfcd086f29f343af6de7a676f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit84a7c81bfcd086f29f343af6de7a676f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
