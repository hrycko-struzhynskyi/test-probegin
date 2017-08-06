<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use AppBundle\Entity\Message;

class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text')->add('destructWay', ChoiceType::class, array(
            'choices' => array(
                'Hour' => Message::DESTRUCT_TIME,
                'Load' => Message::DESTRUCT_READ
            )
        ))->add('destructOption', IntegerType::class,array('label' => 'Hour after message will be destroyed'))
        ->add('password', PasswordType::class);

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('access_key');

                $data = $event->getData();
                $key = substr(md5($data['text'] . microtime()), 0, 10);
                $data['access_key'] = $key;
                $event->setData($data);
            }
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_message';
    }


}
