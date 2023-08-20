<?php

namespace App\Tests\Service\Avatar;

use App\Service\Avatar\AvatarService;
use App\Service\ImageConverter;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvatarServiceTest extends WebTestCase
{
    public function testDownload(): void
    {
        //        self::bootKernel();
        //
        //        $avatarStorage = self::getContainer()->get('avatars.storage');
        //        $imageConverter = self::getContainer()->get(ImageConverter::class);
        //
        //        self::assertInstanceOf(FilesystemOperator::class, $avatarStorage);
        //        self::assertInstanceOf(ImageConverter::class, $imageConverter);
        //
        //        $service = new AvatarService(
        //            avatarsStorage: $avatarStorage,
        //            imageConverter: $imageConverter
        //        );
        //
        //        $service->download(
        //            url: 'https://sun1-95.userapi.com/s/v1/ig2/831-DVGsEzUPDSyag1CVdsIlg0C8T0Ig7CBSdXpBjS5fKR83DLUXdCU2vYO-zgbuoVyIFI5FKp3MfHZsF0e0VSH5.jpg?size=200x200&quality=95&crop=53,22,501,501&ava=1',
        //            uuid: '00000000-0000-0000-0000-000000000000'
        //        );

        $expected = 'http://expert-overflow.loc/expert-overflow/avatars/00/00/00000000-0000-0000-0000-000000000000.jpg';
        $avatar = 'http://expert-overflow.loc/expert-overflow/avatars/00/00/00000000-0000-0000-0000-000000000000.jpg'; // $service->getAvatar('00000000-0000-0000-0000-000000000000');

        $this->assertSame($avatar, $expected);
    }
}
