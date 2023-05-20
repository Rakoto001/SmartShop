<?php

namespace App\DataFixtures;

use App\Entity\About;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Roles;
use App\Entity\Articles;
use App\Entity\Booking;
use App\Entity\Category;
// use App\Services\ArticeService;
use App\Entity\Comments;
use App\Services\UserService;
use App\Services\ArticleService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $userService;
    private $hashPassword;

    public function __construct(UserService $_userService, UserPasswordEncoderInterface $_hashPassword)
    {
        $this->userService  = $_userService;
        $this->hashPassword = $_hashPassword; 
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR:fr');

        // $user = $this->articleService->findOne(5);
        // dd($user);
        $number = 12;
        $password = '123456';
        $faker = Factory::create('FR:fr');
        $user =  new User();
        $roles = new Roles();

        $userAdminRole = $roles->setTitle('ROLE_ADMIN');
        $userRole      = $roles->setTitle('USER_ROLE');


        // $user->setUsername('admin')
        //      ->setEmail('admin@gmail.com')
        //      ->setGender('male')
        //      ->setLastname('admin');
        // $user->setPassword($this->hashPassword->encodePassword($user, $password))
        //      ->addRole($roles->setTitle('ROLE_ADMIN'))
        //      ->setAvatar('https://randomuser.me/api/portraits/');


        // $manager->persist($user);


        

        // //faker User
        // for ($fakeUser = 1; $fakeUser<$number; $fakeUser++) {
        //     $user  = new User;
        //     $user->setUsername('personne'.$fakeUser)
        //          ->setEmail('personn'.$fakeUser.'@gmail.com')
        //          ->setLastname('lastName'.$fakeUser)
        //          ->setGender('Female')
        //          ->setStatus(1)
        //          ->setPassword($this->hashPassword->encodePassword($user, $password))
        //          ->addRole($userAdminRole)
        //          ->setAvatar('https://randomuser.me/api/portraits/');

        //     $manager->persist($user);
        //     $users[] = $user;

        // }

        // //for roles 
        // for($fakeRole=1; $fakeRole<10; $fakeRole++){
        //     $role = new Roles;
        //     $role->setUsers($users[$fakeRole])

        //          ->setTitle('ROLE_USER');
        //     $manager->persist($role);
        // }
        

        
        // // for category
        // for($fakeCategory=1; $fakeCategory<=3; $fakeCategory++){
        //     $category = new Category;
        //     $category->setName('category'.$fakeCategory)
        //              ->setDescription($faker->sentence())
        //              ->setAvatar('https://randomuser.me/api/portraits/')
        //              ->setStatus(mt_rand(0,1));
            
        //     $manager->persist($category);

        //     $categories[] = $category;

        // }







        // // //faker Articles
        // for ($fakeArticle = 1; $fakeArticle<$number; $fakeArticle++) {
        //     $article = new Articles();
        //     $user = $users[mt_rand(0, count($users)-1)];
        //     $name = '<p>'. join('</p><p>', $faker->paragraphs(1) )  . '<p>';
        //     $content = '<p>'. join('</p><p>', $faker->paragraphs(5) )  . '<p>';
        //     $article->setName($name)
        //             ->setContent($content)
        //             ->setStatus(1)
        //             ->setPrice(mt_rand(10,150))
        //             ->setCoverImage('https://randomuser.me/api/portraits/')
        //             ->setCreatedBy($user)
        //             ->setCategory($categories[mt_rand(1,2)]);

        //     //gestion des réservation
        //     for($indexBooking = 0; $indexBooking<mt_rand(0,5); $indexBooking++){
        //         $booking = new Booking();
        //         $userBooker = $users[mt_rand(0, count($users)-1)];
        //         $booking->setBooker($userBooker)
        //                 ->setArticle($article)
        //                 ->setAmount(mt_rand(1,100));

        //         $manager->persist($booking);

        //         //gestion des commentaires => les commentairesz se fait au niveau de la reservation
        //          //gestion des commentaires

        //         for($indexComment = 0; $indexComment<mt_rand(0,5); $indexComment++){
        //             $comment = new Comments;
        //             $author  = $users[mt_rand(0, count($users)-1)];
        //             $comment->setContent($faker->sentence())
        //                     ->setRating(mt_rand(1,5));
        //             $comment->setArticle($article);
        //             //ny nanao reservation ihany no afaka manao oment
        //             $comment->setAuthor($userBooker);
                    
        //             $manager->persist($comment);

        //     }

        //     }

        //     $manager->persist($article);

        // }

        //  //for about smart

        //  $about = new About;
        //  $about->setName('Smart Shop MADAGASCAR')
        //        ->setUrl('C:\Users\Pharaon-PC\Desktop\new Doc symfony\[PHP - POO] - Injection de dépendance (+ solution existence utilisateur).mkv')
        //        ->setLogo('https://randomuser.me/api/portraits/')
        //        ->setDescription($faker->sentence())
        //        ->setService('Tout ce qui est smart technology')
        //        ->setContact('0346655980');
        //  $manager->persist($about);

        $manager->flush();

    }
}
