<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Figure;
use App\Entity\Groupe;
use App\Entity\Document;
use App\Entity\Commentaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        /////////////////////////
        // Table User Admin
        /////////////////////////
        $user = new User();
        $avatar = $faker->imageURL(60,40);
        $hash = $this->encoder->encodePassword($user, 'password');

        $user->setPrenom('Lior')
            ->setNom('Chamla')
            ->setEmail('lior@symfony.fr')
            ->setIntroduction('Un texte long')
            ->setHash($hash)
            ->setAvatar($avatar)
            ->addUserRole($adminRole);

        $manager->persist($user);

        for($i = 1; $i <= 11; $i++) {
            /////////////////////////
            // Table User Figure
            /////////////////////////
            $user = new User();
            $introduction = $faker->sentence(3);
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;
            $avatar = $faker->imageURL(60,40);
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setIntroduction($introduction)
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);

            for($m = 1; $m <= mt_rand(1,2); $m++) {
                    /////////////////////////
                    // Table Groupe
                    /////////////////////////
                    $groupe = new Groupe();
                    $groupeName = $faker->sentence(2);

                    $groupe->setGroupeName($groupeName);

                    $manager->persist($groupe);

                    for($j = 1; $j <= mt_rand(2,3); $j++) {
                    /////////////////////////
                    // Table Figure
                    /////////////////////////
                    $figure = new Figure();
                    $figureName = $faker->sentence(1);
                    $groupeName = $faker->sentence(2);

                    $num = mt_rand(1, 6);
                    if ($num == 1) {$imageDefaut = "/images/Snowboarding-Tricks-01.jpg";}
                    if ($num == 2) {$imageDefaut = "/images/Snowboarding-Tricks-02.jpg";}
                    if ($num == 3) {$imageDefaut = "/images/Snowboarding-Tricks-03.jpg";}
                    if ($num == 4) {$imageDefaut = "/images/Snowboarding-Tricks-04.jpeg";}
                    if ($num == 5) {$imageDefaut = "/images/Snowboarding-Tricks-05.jpg";}
                    if ($num == 6) {$imageDefaut = "/images/Snowboarding-Tricks-06.jpg";}
                    
                    $description = $faker->paragraph(5);
                    $creationDate = $faker->dateTime('-3 years', $timezone = null);
                    $modifDate = $faker->dateTime($creationDate, $timezone = null);


                    $figure->setFigureName($figureName)
                        ->setDescription($description)
                        ->setAuthorId($user)
                        ->setImageDefaut($imageDefaut)
                        ->setCreationDate($creationDate)
                        ->setModifDate($modifDate)
                        ->setgroupe($groupe);

                    $manager->persist($figure);

                    for($k = 1; $k <= mt_rand(3,12); $k++) {
                        /////////////////////////
                        // Table Commentaire
                        /////////////////////////
                        $commentaire = new Commentaire();
                        $description = $faker->paragraph(5);
                        $date = $faker->dateTime($max = 'now', $timezone = null);

                        /////////////////////////
                        // Table User Commentaire
                        /////////////////////////
                        $genres = ['male', 'female'];
                        $genre = $faker->randomElement($genres);

                        $userCommentaire = new User();
                        $introduction = $faker->sentence(3);
                        $prenom = $faker->firstName($genre);
                        $nom = $faker->lastName;
                        $email = $faker->email;

                        $picture = 'https://randomuser.me/api/portraits/';
                        $pictureId = $faker->numberBetween(1, 99) . '.jpg';

                        $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

                        $hash = $this->encoder->encodePassword($user, 'password');

                        $userCommentaire->setPrenom($prenom)
                            ->setNom($nom)
                            ->setEmail($email)
                            ->setIntroduction($introduction)
                            ->setHash($hash)
                            ->setAvatar($picture);

                        $manager->persist($userCommentaire);

                        $commentaire->setCreationDate($date)
                            ->setContent($description)
                            ->setAuthor($userCommentaire)
                            ->setFigure($figure);

                        $manager->persist($commentaire);
                    }
    
                    for($l = 1; $l <= mt_rand(1,5); $l++) {
                        /////////////////////////
                        // Table Document
                        /////////////////////////
                        $document = new Document();
                        $boolean = mt_rand(1,2);

                        $num = mt_rand(1, 6);
                        if ($num == 1) {$URL = "/images/Snowboarding-Tricks-01.jpg";}
                        if ($num == 2) {$URL = "/images/Snowboarding-Tricks-02.jpg";}
                        if ($num == 3) {$URL = "/images/Snowboarding-Tricks-03.jpg";}
                        if ($num == 4) {$URL = "/images/Snowboarding-Tricks-04.jpeg";}
                        if ($num == 5) {$URL = "/images/Snowboarding-Tricks-05.jpg";}
                        if ($num == 6) {$URL = "/images/Snowboarding-Tricks-06.jpg";}
                        
                        $description = $faker->sentence(2);

                        $document->setUrl($URL)
                            ->setCaption($description)
                            ->setBooleanImageVideo($boolean);

                        if ($boolean == 1) {
                            $document->setFigureVideo($figure);
                        } else {
                            $document->setFigurePicture($figure);
                        }

                        $manager->persist($document);
                    }
                }
            }
        }

        $manager->flush();
    }
}
