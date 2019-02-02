<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

trait VfsStreamTrait
{
    /**
     * @var vfsStreamDirectory
     */
    protected $driver;

    public function _before(): void
    {
        $this->driver = vfsStream::setup('root', 0777, [
            'dir1'      => [
                'dir-1-a' => [],
                'dir-1-b' => [
                    '.hidden'   => [
                        'template1.twig'        => 'text here',
                        'template-not.not.twig' => 'nope',
                        'text.txt'              => 'some-text',
                    ],
                    'dir-1-b-a' => [
                        'some'    => 'file',
                        'another' => 'here',
                    ],
                ],
            ],
            'dir-2'     => [],
            'file.txt'  => 'file text',
            'file.php'  => 'file php content',
            '.some'     => 'hidden file',
            'some.twig' => 'Template content',
        ]);
    }

    public function _after(): void
    {
        $this->driver = null;
    }
}