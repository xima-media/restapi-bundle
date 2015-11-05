<?php
namespace Xima\RestApiBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Xima\RestApiBundle\Helper\Utility;

/**
 * Class ApiUserAdmin
 *
 * @author Steve Lenz <steve.lenz@xima.de>, XIMA Media GmbH
 * @package Xima\RestApiBundle\Admin
 */
class ApiUserAdmin extends Admin
{

    /**
     * The translation domain
     *
     * @var string
     */
    protected $translationDomain = 'XimaRestApiBundle';

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     */
    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('isActive')
            ->add('email')
            ->add('key')
            ->add('website')
            ->add('comment')
            ->add('roles')
            ->add('createdAt')
            ->add('updatedAt');
    }

    /**
     * @return mixed
     */
    public function getNewInstance()
    {
        $entity = parent::getNewInstance();
        $entity->setKey(Utility::generateUniqueHash());

        return $entity;
    }

    /**
     * Fields to be shown on filter forms
     *
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('isActive');
    }

    /**
     * Fields to be shown on lists
     *
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username')
            ->add('isActive')
            ->add('email')
            ->add('updatedAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show'   => array(),
                    'edit'   => array(),
                    'delete' => array(),
                )
            ));
    }

    /**
     * Fields to be shown on create/edit forms
     *
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('isActive')
            ->add('username')
            ->add('email')
            ->add('website')
            ->add('comment');
    }

}
