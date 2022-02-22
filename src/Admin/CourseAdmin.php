<?php
namespace App\Admin;

use App\Entity\Classes;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class CourseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class)->add('description', TextareaType::class)->add('class', EntityType::class, [
            'class' => Classes::class,
            'choice_label' => 'name',
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name')->add('description')->add('class.name', null, ['label' => 'Class']);
    }
}