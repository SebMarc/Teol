<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public $userList = [];
    public $productList = [];
    public $technicienList = [];

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        
        // Création d'un admin
        $user = new User ();
        $user   ->setEmail('admin@admin.com')
                ->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'admin');
        $user   ->setPassword($password)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setSociety('Teol')
                ->setPhone($faker->phoneNumber())
                ->setAdress($faker->streetAddress)
                ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                ->setCity($faker->city);
                //->setTechnicien(null);
               
      
        $manager->persist($user);

       
        

        // Création de 5 tech
        for ($i = 0 ; $i <=4 ; $i++) {
            $tech = new User();
            

            $tech       ->setFirstname($faker->firstName())
                        ->setRoles(['ROLE_TECH'])
                        ->setEmail(sprintf('tech%d@teol.fr', $i))
                        ->setLastname($faker->lastName())
                        ->setPhone($faker->phoneNumber());
              
            
            $password = $this->encoder->encodePassword($user, 'tech');
            $tech       ->setPassword($password)
                        ->setSociety('Teol')
                        ->setAdress($faker->streetAddress)
                        ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                        ->setCity($faker->city);
                        $technicienList [] = $tech;
                       
      
            $manager->persist($tech);
        }

        

        //Création de 100 Users
        for($i = 0 ; $i <=99 ; $i++) {
        $customer = new User();
        $customer   ->setEmail(sprintf('member%d@member.fr', $i))
                    ->setRoles(['ROLE_MEMBER']);
        $password = $this->encoder->encodePassword($user, 'member');
        $customer   ->setPassword($password)
                    ->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setSociety($faker->company())
                    ->setPhone($faker->phoneNumber())
                    ->setAdress($faker->streetAddress)
                    ->setPostalCode($faker->numberBetween(1000, 9000) * 10)
                    ->setCity($faker->city);
                    $randomtechnicien = $technicienList[mt_rand(0, count($technicienList) - 1)];
        $customer   ->setTechnicien($randomtechnicien)
                    ->setEncours($faker->numberBetween($min = -30000, $max = 5000));

                    
                    // Creation de 5 commandes
                    for($j = 0 ; $j <=4 ; $j++) {
                        $order = new Commande();
                        $stringnumber = strval($faker->numberBetween(1000, 9000));
                        $order  ->setNumber($stringnumber)
                                ->setAmount($faker->numberBetween(1000, 9000))                                
                                ->setTech($randomtechnicien)                        
                                ->setCustomer($customer);
                        
                        $manager->persist($order)
                        ;

                    }
                    $userList [] = $customer;
                    $manager->persist($customer)
                    ;
        }   

        


        // Creation de 5 Products
        for($i = 0 ; $i <=99 ; $i++) {
            $product = new Product();
            $product    ->setName(sprintf('Produit Teol %d', $i));
            $productList[] = $product;
            $manager->persist($product)
            ;

        }

       

        // Creation de 5 categories
        for($i = 0 ; $i <=4 ; $i++) {
            $category = new Category();
            $category   ->setName($faker->word)
                        ->setEnable(true);
            $manager->persist($category)
            ;
        }


        $manager->flush();
    }
}
