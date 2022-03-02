<?php
namespace App\Admin;

use App\Entity\Classes;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class StudentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('first_name', TextType::class)->add('last_name', TextType::class)->add('date_of_birth', TextType::class)->add('imageFile', VichImageType::class)->add('class', EntityType::class, [
            'class' => Classes::class,
            'choice_label' => 'name',
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('firstName')->add('lastName')->add('class.name', null, ['label' => 'Class']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('first_name')->addIdentifier('last_name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('first_name')->add('last_name')->add('date_of_birth')->add('imageFile')->add('class.name', null, ['label' => 'Class']);
    }
}