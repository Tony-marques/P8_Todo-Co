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
            $user->setEmail($formData["email"])
            ->setPassword($formData["password"]["first"])
            ->setRoles($formData["roles"])
            ->setUsername($formData["username"]);

            $userInForm = new User();
            
            $form = $this->factory->create(UserType::class, $userInForm);
            $form->submit($formData);

            $this->assertTrue($form->isSynchronized());
            $this->assertEquals($user->getUsername(), $userInForm->getUsername());
            $this->assertEquals($user->getPassword(), $userInForm->getPassword());
            $this->assertEquals($user->getEmail(), $userInForm->getEmail());
            $this->assertEquals($user->getRoles(),$userInForm->getRoles());
    }
}