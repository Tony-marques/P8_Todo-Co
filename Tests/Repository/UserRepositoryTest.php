<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
  public function testGetUser(){
    $userRepository = static::getContainer()->get(UserRepository::class);
    
    $user = $userRepository->findOneBy(['username' => 'admin']);

    $this->assertSame("admin", $user->getUsername());
  }
}