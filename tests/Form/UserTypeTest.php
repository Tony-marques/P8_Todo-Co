<?php

namespace App\tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTypeTest extends TypeTestCase
{

    public function testUserForm()
        {
            $formData = [
                'username' => 'Exemple de username',
                'password' => array(
                    'first' => '1234',
                    'second' => '1234',
                ),
                'email'=> 'Exemple d\'email',
                'roles' => array('ROLE_USER'),
            ];
            
            $user = new User();
            
            $form = $this->factory->create(UserType::class, $user);
            $form->submit($formData);

            // dd($user->getRoles());
            // dd($form->get("roles")->getData());
    
            $this->assertTrue($form->isSynchronized());
            $this->assertEquals($user->getUsername(), $form->get("username")->getData());
            $this->assertEquals($user->getPassword(), $form->get("password")->getData());
            $this->assertEquals($user->getEmail(), $form->get("email")->getData());
            $this->assertEquals($user->getRoles(), $form->get("roles")->getData());
    }
}