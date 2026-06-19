<?php

namespace App\Service;

use App\Entity\Professionel;
use App\Validator\NoSpam;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DevisService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
    ) {}

    public function createProfessionel(FormInterface $form): Professionel
    {
        $entity = $form->getData();
        $this->handleAncienneAssurance($entity);

        $violations = $this->validator->validate($entity, null, ['Default']);

        $spamViolations = new ConstraintViolationList();
        $otherViolations = new ConstraintViolationList();
        foreach ($violations as $violation) {
            $constraint = $violation->getConstraint();
            if ($constraint instanceof NoSpam) {
                $spamViolations->add($violation);
            } else {
                $otherViolations->add($violation);
            }
        }

        foreach ($spamViolations as $violation) {
            $form->get($violation->getPropertyPath())?->addError(
                new \Symfony\Component\Form\FormError($violation->getMessage())
            );
        }

        foreach ($otherViolations as $violation) {
            $form->get($violation->getPropertyPath())?->addError(
                new \Symfony\Component\Form\FormError($violation->getMessage())
            );
        }

        if (count($spamViolations) > 0) {
            throw new \RuntimeException('SPAM_DETECTED');
        }

        if (count($otherViolations) > 0) {
            throw new \RuntimeException('VALIDATION_FAILED');
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    private function handleAncienneAssurance(object $entity): void
    {
        if (method_exists($entity, 'getAncienne') && method_exists($entity, 'setMotif')) {
            $ancienne = $entity->getAncienne();
            if ($ancienne !== 'OUI') {
                $entity->setMotif(null);
            }
        }
    }
}