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

        //$genres = ['male', 'female'];
        $genre = $faker->randomElement($genres);
        $avatar = 'https://randomuser.me/api/portraits/';
        $avatarId = $faker->numberBetween(1, 99) . '.jpg';
        //$avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
        $avatar .= 'men/' . $avatarId;

        $hash = $this->encoder->encodePassword($user, 'password');

        $user->setPrenom('Lior')
            ->setNom('Chamla')
            ->setEmail('lior@symfony.fr')
            ->setIntroduction('Un texte long')
            ->setHash($hash)
            ->setAvatar($avatar)
            ->addUserRole($adminRole);

        $manager->persist($user);

/*** Groupe 01 ***************************************************************************/
        /////////////////////////
        // Table User Figure
        /////////////////////////
            $user = new User();
//
            $introduction = 'Je suis un passioné de snowboard.';
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;

            $genres = ['male', 'female'];
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
                
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setIntroduction($introduction)
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);

            /////////////////////////
            // Groupe 01
            /////////////////////////
            $groupe = new Groupe();
//
            $groupeName = 'Contre rotation (anciennement école française)';

            $groupe->setGroupeName($groupeName);

            $manager->persist($groupe);

            /////////////////////////
            // Table Figure
            /////////////////////////
            $figure = new Figure();
//
            $figureName = 'Position frontside ou côté orteils';
            $groupeName = $groupeName;
//
            $imageDefaut = "/images/Snowboarding-Tricks-01.jpg";
//
            $description = 'Le snowbordeur est en appui sur les orteils. À l\'origine, on signifiait qu\'il était face à la pente (de l\'anglais front (face) et side (côté)), mais dans de nombreux cas c\'est faux, à tel point que maintenant les anglophones utilisent parfois une autre terminologie. La position en appui sur les orteils peut s\'appeler toeside (de toe : orteil et side : côté).';
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

                /////////////////////////
                // Table Commentaire
                /////////////////////////
                $commentaire = new Commentaire();
                $description = 'Pas mal comme figure.';
                $date = $faker->dateTime($max = 'now', $timezone = null);

                /////////////////////////
                // Table User Commentaire
                /////////////////////////
                $genres = ['male', 'female'];
                $genre = $faker->randomElement($genres);

                $userCommentaire = new User();
                $introduction = 'J\'aime faire des commentaires.';
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

            /////////////////////////
            // Table Document
            /////////////////////////
            $document = new Document();
            $boolean = mt_rand(1,2);

//
            $URL = "/images/Snowboarding-Tricks-01.jpg";
                            
            $description = 'Figure habituelle.';

            $document->setUrl($URL)
                     ->setBooleanImageVideo($boolean);

            if ($boolean == 2) {
                $docuImage = 'https://i.ytimg.com/vi/Vq06eZ9rQ34/hq720.jpg';
                $document->setFigureVideo($figure)
                         ->setDocuImage($docuImage);
            } else {
                $document->setFigurePicture($figure);
            }

            $manager->persist($document);
    
/*** Groupe 02 ***************************************************************************/
        /////////////////////////
        // Table User Figure
        /////////////////////////
            $user = new User();
//
            $introduction = 'J\'ai fais de snowboard à Courchevel.';
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;

            $genres = ['male', 'female'];
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
    
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setIntroduction($introduction)
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);

            /////////////////////////
            // Groupe 02
            /////////////////////////
            $groupe = new Groupe();
//
            $groupeName = 'Pré-rotation (anciennement école suisse)';

            $groupe->setGroupeName($groupeName);

            $manager->persist($groupe);

            /////////////////////////
            // Table Figure
            /////////////////////////
            $figure = new Figure();
//
            $figureName = 'Position backside ou côté talons';
            $groupeName = $groupeName;
//
            $imageDefaut = "/images/Snowboarding-Tricks-02.jpg";
//
            $description = 'Le snowbordeur est en appui sur les talons. À l\'origine, on signifiait qu\'il était dos à la pente (de l\'anglais back (dos) et side (côté)), mais dans de nombreux cas c\'est faux, à tel point que maintenant les anglophones utilisent parfois une autre terminologie.';
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

                /////////////////////////
                // Table Commentaire
                /////////////////////////
                $commentaire = new Commentaire();
                $description = 'Pas mal comme figure.';
                $date = $faker->dateTime($max = 'now', $timezone = null);

                /////////////////////////
                // Table User Commentaire
                /////////////////////////
                $genres = ['male', 'female'];
                $genre = $faker->randomElement($genres);

                $userCommentaire = new User();
                $introduction = 'J\'aime faire des commentaires.';
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

            /////////////////////////
            // Table Document
            /////////////////////////
            $document = new Document();
            $boolean = mt_rand(1,2);

//
            $URL = "/images/Snowboarding-Tricks-02.jpg";

            $description = 'Figure habituelle.';

            $document->setUrl($URL)
                     ->setBooleanImageVideo($boolean);

            if ($boolean == 2) {
                $docuImage = 'https://i.ytimg.com/vi/Vq06eZ9rQ34/hq720.jpg';
                $document->setFigureVideo($figure)
                         ->setDocuImage($docuImage);
            } else {
                $document->setFigurePicture($figure);
            }

            $manager->persist($document);
    

/*** Groupe 03 ***************************************************************************/
        /////////////////////////
        // Table User Figure
        /////////////////////////
        $user = new User();
//
            $introduction = 'J\'adore le snowboard.';
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;

            $genres = ['male', 'female'];
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
    
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setIntroduction($introduction)
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);

            /////////////////////////
            // Groupe 01
            /////////////////////////
            $groupe = new Groupe();
//
            $groupeName = 'Co-rotation';

            $groupe->setGroupeName($groupeName);

            $manager->persist($groupe);

            /////////////////////////
            // Table Figure
            /////////////////////////
            $figure = new Figure();
//
            $figureName = 'Carre toe side ou carre pointe de pieds (anciennement frontside) ';
            $groupeName = $groupeName;
//
            $imageDefaut = "/images/Snowboarding-Tricks-03.jpg";
//
            $description = 'c\'est la carre qui se trouve du côté des orteils.';
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

                /////////////////////////
                // Table Commentaire
                /////////////////////////
                $commentaire = new Commentaire();
                $description = 'Pas mal comme figure.';
                $date = $faker->dateTime($max = 'now', $timezone = null);

                /////////////////////////
                // Table User Commentaire
                /////////////////////////
                $genres = ['male', 'female'];
                $genre = $faker->randomElement($genres);

                $userCommentaire = new User();
                $introduction = 'J\'aime faire des commentaires.';
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

            /////////////////////////
            // Table Document
            /////////////////////////
            $document = new Document();
            $boolean = mt_rand(1,2);

//
            $URL = "/images/Snowboarding-Tricks-03.jpg";

            $description = 'Figure habituelle.';

            $document->setUrl($URL)
                     ->setBooleanImageVideo($boolean);

            if ($boolean == 2) {
                $docuImage = 'https://i.ytimg.com/vi/Vq06eZ9rQ34/hq720.jpg';
                $document->setFigureVideo($figure)
                         ->setDocuImage($docuImage);
            } else {
                $document->setFigurePicture($figure);
            }

            $manager->persist($document);
    
/*** Groupe 04 ***************************************************************************/
        /////////////////////////
        // Table User Figure
        /////////////////////////
            $user = new User();
//
            $introduction = 'J\'ai fais de snowboard à Courchevel.';
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;

            $genres = ['male', 'female'];
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
    
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($prenom)
                ->setNom($nom)
                ->setEmail($email)
                ->setIntroduction($introduction)
                ->setHash($hash)
                ->setAvatar($avatar);

            $manager->persist($user);

            /////////////////////////
            // Groupe 04
            /////////////////////////
            $groupe = new Groupe();
//
            $groupeName = 'Synthèse';

            $groupe->setGroupeName($groupeName);

            $manager->persist($groupe);

            /////////////////////////
            // Table Figure
            /////////////////////////
            $figure = new Figure();
//
            $figureName = 'Carre heelside ou carre talon (anciennement backside)';
            $groupeName = $groupeName;
//
            $imageDefaut = "/images/Snowboarding-Tricks-04.jpeg";
//
            $description = 'c\'est la carre qui se trouve du côté des talons. Back et front sont utilisés pour les rotations.';
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

                /////////////////////////
                // Table Commentaire
                /////////////////////////
                $commentaire = new Commentaire();
                $description = 'Pas mal comme figure.';
                $date = $faker->dateTime($max = 'now', $timezone = null);

                /////////////////////////
                // Table User Commentaire
                /////////////////////////
                $genres = ['male', 'female'];
                $genre = $faker->randomElement($genres);

                $userCommentaire = new User();
                $introduction = 'J\'aime faire des commentaires.';
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

            /////////////////////////
            // Table Document
            /////////////////////////
            $document = new Document();
            $boolean = mt_rand(1,2);

//
            $URL = "https://www.youtube.com/embed/Ey5elKTrUCk";

            $description = 'Figure habituelle.';

            $document->setUrl($URL)
                     ->setBooleanImageVideo($boolean);

            if ($boolean == 2) {
                $docuImage = 'https://i.ytimg.com/vi/Vq06eZ9rQ34/hq720.jpg';
                $document->setFigureVideo($figure)
                         ->setDocuImage($docuImage);
            } else {
                $document->setFigurePicture($figure);
            }

            $manager->persist($document);
    
        
/******************************************************************************/
        for($i = 1; $i <= 5; $i++) {
            /////////////////////////
            // Table User Figure
            /////////////////////////
            $user = new User();
            $introduction = $faker->sentence(3);
            $prenom = $faker->firstNameMale;
            $nom = $faker->lastName;
            $email = $faker->email;

            $genres = ['male', 'female'];
            $genre = $faker->randomElement($genres);
            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1, 99) . '.jpg';
            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $avatarId;
    
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

                for($j = 1; $j <= mt_rand(8,9); $j++) {
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

                    for($k = 1; $k <= mt_rand(21,25); $k++) {
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

                        $document->setBooleanImageVideo($boolean);

                        if ($boolean == 1) {
                            $num = mt_rand(1, 6);
                            if ($num == 1) {$URL = "/images/Snowboarding-Tricks-01.jpg";}
                            if ($num == 2) {$URL = "/images/Snowboarding-Tricks-02.jpg";}
                            if ($num == 3) {$URL = "/images/Snowboarding-Tricks-03.jpg";}
                            if ($num == 4) {$URL = "/images/Snowboarding-Tricks-04.jpeg";}
                            if ($num == 5) {$URL = "/images/Snowboarding-Tricks-05.jpg";}
                            if ($num == 6) {$URL = "/images/Snowboarding-Tricks-06.jpg";}

                            $document->setUrl($URL)
                                     ->setFigurePicture($figure);
                        } else {
                            $num = mt_rand(1, 6);
                            if ($num == 1) {$URL = "https://www.youtube.com/embed/SQyTWk7OxSI";}
                            if ($num == 2) {$URL = "https://www.youtube.com/embed/8AWdZKMTG3U";}
                            if ($num == 3) {$URL = "https://www.youtube.com/embed/G9qlTInKbNE";}
                            if ($num == 4) {$URL = "https://www.youtube.com/embed/Ey5elKTrUCk";}
                            if ($num == 5) {$URL = "https://www.youtube.com/embed/qsd8uaex-Is";}
                            if ($num == 6) {$URL = "https://www.youtube.com/embed/V9xuy-rVj9w";}

                            if ($num == 1) {$docuImage = "https://i.ytimg.com/vi/SQyTWk7OxSI/hq720.jpg";}
                            if ($num == 2) {$docuImage = "https://i.ytimg.com/vi/8AWdZKMTG3U/hq720.jpg";}
                            if ($num == 3) {$docuImage = "https://i.ytimg.com/vi/G9qlTInKbNE/hq720.jpg";}
                            if ($num == 4) {$docuImage = "https://i.ytimg.com/vi/Ey5elKTrUCk/hq720.jpg";}
                            if ($num == 5) {$docuImage = "https://i.ytimg.com/vi/qsd8uaex-Is/hq720.jpg";}
                            if ($num == 6) {$docuImage = "https://i.ytimg.com/vi/V9xuy-rVj9w/hq720.jpg";}

                            $document->setUrl($URL)
                                     ->setFigureVideo($figure)
                                     ->setDocuImage($docuImage);
                        }

                        $manager->persist($document);
                    }
                }
            }
        }
/******************************************************************************/

        $manager->flush();
    }
}
