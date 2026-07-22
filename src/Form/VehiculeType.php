<?php

namespace App\Form;

use App\Entity\Transport;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Validator\NoSpam;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(max: 255),
                    new Regex(pattern: '/^[\p{L} \-\'\’]+$/u', message: 'Le nom contient des caractères non autorisés.'),
                    new NoSpam(),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire.'),
                    new Length(max: 255),
                    new Regex(pattern: '/^[\p{L} \-\'\’]+$/u', message: 'Le prénom contient des caractères non autorisés.'),
                    new NoSpam(),
                ]
            ])
            ->add('raison', TextType::class, [
                'label' => false,
                'required' => false,
                 'constraints' => [
                    new NotBlank(message: 'La raison sociale est obligatoire.'),
                    new Length(max: 255),
                    new NoSpam(),
                ],
                'attr' => [
                    'placeholder' => 'Raison sociale...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ]
            ])
            ->add('activite', ChoiceType::class, [
                'label' => false,
                 'required' => false,
                'placeholder' => 'Démarrage d\'ctivité ?',
                'attr' => [
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'

                ],
                'choices' => [

                    'Oui' => 'oui',
                    'Non' => 'non'


                ],
                'constraints' => [
                    new NotBlank(message: 'L activité est obligatoire.'),
                    new Length(max: 255),
                    new NoSpam(),
                ],
            ])
            ->add('ancienne', ChoiceType::class, [
                'label' => false,
                 'required' => false,
                'placeholder' => 'Ancienne assurance résilié?',
                'attr' => [
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'

                ],
                'choices' => [

                    'Oui' => 'oui',
                    'Non' => 'non'


                ],
            ])
            ->add('motif', ChoiceType::class, [
                'label' => false,
                'required' => false,
                'placeholder' => 'Motif résiliation ?',
                'attr' => [
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'

                ],
                'choices' => [

                    'Sinistre' => 'sinistre',
                    'Non paiement' => 'non paiement',
                    'Suspension de paiement' => 'suspension de paiement',
                    'Fausse declaration' => 'fausse declaration',
                    'Echéance' => 'echeance',



                ],
            ])

            // ->add('assurer', ChoiceType::class, [
            //     'label' => false,
            //     'placeholder' => 'Véhicule assuré actuellement ?',
            //     'choices' => [
            //         'Oui' => 'oui',
            //         'Non' => 'non'
            //     ],
            //     'attr' => [
            //         'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
            //     ],
            //     'constraints' => [
            //         new Assert\NotBlank(message: 'Veuillez indiquer si le véhicule est assuré')
            //     ]
            // ])
            // ->add('type', ChoiceType::class, [
            //     'label' => false,
            //     'placeholder' => 'type transport ?',
            //     'choices' => [
            //         'Marchandises' => 'marchandises',
            //         'Personne' => 'personne'
            //     ],
            //     'attr' => [
            //         'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400',
            //         'id' => 'transport-type'
            //     ],
            //     'constraints' => [
            //         new Assert\NotBlank(['message' => 'Veuillez sélectionner un type de transport'])
            //     ]
            // ])
            // ->add('souhAssurer', ChoiceType::class, [
            //     'label' => false,
            //     'placeholder' => 'Vous souhaitez assurer ?',
            //     'choices' => [
            //         'Véhicule' => 'vehicule',
            //         'Marchandise transportées' => 'marchandise transportées',
            //         'Véhicule + M/ses transportées' => 'véhicule + M/ses trans',
            //         'Transport personne à titre onéreux' => 'transp personne onéreux',
            //         'RC pro' => 'rc pro',
            //         'Transport personne à titre onéreux + RC PRO' => 'transp personne onéreux + RC PRO',
            //     ],
            //     'attr' => [
            //         'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400',
            //         'id' => 'souh-assurer'
            //     ],
            //     'required' => false,
            //     'constraints' => [
            //         new Assert\NotBlank(['message' => 'Veuillez sélectionner ce que vous souhaitez assurer'])
            //     ]
            // ])

            ->add('codepostal', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Code Postal...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new Length(max: 6 , maxMessage: 'Le code postal ne peut pas dépasser {{ limit }} caractères.'),
                    new Assert\NotBlank(message: 'Le code postal est requis'),
                    new Assert\Regex(pattern: '/^[0-9]{5}$/', message: 'Le code postal doit contenir 5 chiffres')
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                 'constraints' => [
                    new NotBlank(message: 'L email est obligatoire.'),
                    new Email(message: 'Email invalide.'),
                    new NoSpam(),
                ],
            ])
            ->add('tele', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone...',
                    'class' => 'flex-1 px-3 py-2 border border-gray-200 rounded-r-lg bg-light focus:bg-surface transition-all duration-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                ],
                'constraints' => [
                    new NotBlank(message: 'Le téléphone est obligatoire.'),
                    new Regex(pattern: '/^0[1-9]([0-9]{2} ?){4}$/', message: 'Téléphone invalide (ex: 0612345678 ou 06 12 34 56 78).'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transport::class,
        ]);
    }
}
