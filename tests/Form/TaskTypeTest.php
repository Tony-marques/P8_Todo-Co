<?php

namespace App\tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testTaskForm()
        {
            $formData = [
                'title' => 'Exemple de titre',
                'content'=> 'Exemple de contenu'
            ];

            $task = (new Task())
            ->setTitle($formData['title'])
            ->setContent($formData['content']);
    
            $TaskInForm = new Task();
    
            $form = $this->factory->create(TaskType::class, $TaskInForm);
            $form->submit($formData);
    
            $this->assertTrue($form->isSynchronized());
            $this->assertEquals($task->getTitle(), $TaskInForm->getTitle());
            $this->assertEquals($task->getContent(), $TaskInForm->getContent());
    }
}