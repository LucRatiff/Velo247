<?php

namespace App\Service;

use App\Entity\TakenUsernames;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Contrôle la bonne modification du profil utilisateur
 */
class UserEditionValidation
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private ManagerRegistry $registry;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $registry)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->registry = $registry;
    }
    
    public function fieldIsValid(string $field, string $value): bool
    {
        switch ($field) {
            case 'name':
                $takenName = $this->registry->getRepository(TakenUsernames::class)->findOneBy(['name' => $value]);
                
                if ($takenName) {
                    $date = $takenName->getExpirationDate();
                    if ($date == 0 || $date > (new \DateTime('now'))->getTimestamp()) {
                        return false;
                    }
                }
            case 'email':
                //TODO tester l'email
                break;
        }
        
        return true;
    }
    
    /**
     * Réserve le nom d'un utilisateur, soit indéfiniment (si le nom est actif), soit pour 1 an (si supprimé)
     * @param string $name
     * @param string|null $oldName si précisé, c'est le nom inactif
     * @return string
     */
    public function reserveName(string $name, ?string $oldName = null): void
    {
        $manager = $this->registry->getManager();
        $manager->persist((new TakenUsernames())->setName($name)->setExpirationDate(0));
        
        if ($oldName != null) {
            $manager->persist((new TakenUsernames())->setName($oldName)
                    ->setExpirationDate((new \DateTime('now'))->add(new \DateInterval('P1Y'))->getTimestamp()));
        }
        
        $manager->flush();
    }
    
    public function hashPassword(User $user, string $password): string
    {
        return $this->userPasswordHasher->hashPassword($user,$password);
    }
}
