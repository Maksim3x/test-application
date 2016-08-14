<?php

namespace Subbotin\MainBundle\Form;

use Subbotin\MainBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GroupSharesType extends AbstractType
{
	private $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Заголовок группы'))
            ->add('shares', null, array(
            	'label' => 'Список акций',
	            'class' => 'Subbotin\MainBundle\Entity\Shares',
	            'query_builder' => function(EntityRepository $er) {
		            return $er->createQueryBuilder('u')
			            ->where('u.user = :user')
			            ->setParameter('user', $this->user->getId())
		            ;
	            }
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Subbotin\MainBundle\Entity\GroupShares'
        ));
    }
}
