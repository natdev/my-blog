<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
     {
                $this->encoder = $encoder;
             }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 0; $i <=10 ; $i++){
            $user = new User();
            $user->setPseudo('paul '.$i);
            $user->setEmail(sprintf('nat%d@gmail.com',$i));
            $user->setRoles(array('ROLE_ADMIN'));
            $user->setPassword($this->encoder->encodePassword($user,'31Natador@?'));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
