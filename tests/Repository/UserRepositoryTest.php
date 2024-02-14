<?php

namespace App\tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
  public function testGetUser(){
    $userRepository = static::getContainer()->get(UserRepository::class);
    
    $user = $userRepository->find(['id' => 1]);

    $this->assertSame(1, $user->getId());
  }
}