<?php

namespace App\Command;

use App\Entity\Cat;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * cette commande permet de générer les chatons d'origine, à partir de l'api d'unsplash
 * pas beaucoup testée !
 * S'exécute avec :
 * php bin/console app:cats
 */
class CatsCommand extends Command
{
    protected static $defaultName = 'app:cats';

    private $client;
    private $em;

    //injection de mes dépendances
    public function __construct(HttpClientInterface $client, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->client = $client;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('generates cats in db, download images')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //utile pour écrire dans la console
        $io = new SymfonyStyle($input, $output);

        //photos de chatons random
        $url = "https://api.unsplash.com/photos/random?query=kitten&client_id=f4b599bb478f23fe8c8fb588020abce521b3b7d93388627bd2d98980ac07e0ba&orientation=landscape&count=30&featured=true";
        //HTTP Client pour faire la requête
        $response = $this->client->request('GET', $url);
        //convertie le json en array
        $content = $response->toArray();

        //pour générer des données bidons (en français)
        $faker = Factory::create('fr_FR');

        foreach($content as $pic) {
            //crée des chatons
            $cat = new Cat();
            $name = $faker->unique()->firstName();
            $cat->setName($name);
            //juste pour avoir des noms d'image sans espace ni accent, uniques
            $filename = sha1($pic['urls']['regular']) . ".jpeg";
            $cat->setFilename($filename);
            //download le fichier image
            file_put_contents('public/img/' . $filename, file_get_contents($pic['urls']['regular']));

            $cat->setDescription($faker->paragraphs($faker->randomDigitNotNull(), true));
            $cat->setPrice($faker->numberBetween(10, 100));
            $cat->setDateCreated($faker->dateTimeBetween("- 1 year"));

            $this->em->persist($cat);
            $io->text($cat->getName());
        }

        //on flush une seule fois pour tous les chatons, plus rapide !
        $this->em->flush();

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
