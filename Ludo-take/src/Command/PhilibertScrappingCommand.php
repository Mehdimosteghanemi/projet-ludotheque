<?php

namespace App\Command;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhilibertScrappingCommand extends Command
{
    protected static $defaultName = 'philibert:scrapping';
    protected static $defaultDescription = 'Add a short description for your command';

    private $gameRepository;
    private $sluggerInterface;
    private $manager;
    public function __construct(EntityManagerInterface $manager, SluggerInterface $sluggerInterface, GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->sluggerInterface = $sluggerInterface;
        $this->manager = $manager;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('argument', InputArgument::OPTIONAL, 'number of game you want to scrapp')
            ->addArgument('argument2', InputArgument::OPTIONAL, 'number of the link you want the search clicking')
            ->addOption('new', '-a', InputOption::VALUE_NONE, 'to doesn\'t include the game you already have on the adding count')
            ->addOption('search', '-s', InputOption::VALUE_NONE, 'write a game name into the string to check if the website got this and add to BDD')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $numberToScrapp = 1;
        if (is_numeric($input->getArgument('argument'))) {
           $numberToScrapp = $input->getArgument('argument'); 
        } else {
            $gameYouSearch = $input->getArgument('argument'); 
        };

        $client = new Client();
        $numberOfPage = 0;
        $numberOfArticle = 0;
        $numberOfGame = 0;
        $youGotTheGame = 0;
        $ignoredGame = 0;
        $nextPage = false;

        $boardGame = [];

        $crawler = $client->request('GET', 'https://www.philibertnet.com/fr/');
        $linkPrincipal = $crawler->selectLink('Jeux de société')->link();

        if (!isset($gameYouSearch)) {
            while ($numberToScrapp > $numberOfGame) {
                while ($nextPage === false && $numberToScrapp > $numberOfGame) {
                    $crawler = $client->click($linkPrincipal);
                    $titreLien = $crawler->filter('.s_title_block')->eq($numberOfArticle);
                    if (count($titreLien) === 1 && $numberOfArticle <= 5) {
                        $titreLien = $titreLien->children()->text();
                        $linkSecond = $crawler->selectLink($titreLien)->link();
                        $crawler = $client->click($linkSecond);
                        // $io->info('take name');
                        $gameTitle = $crawler->filter('#product_name')->text();
                        // $io->info('take resume');
                        $resumeJeux = $crawler->filter('#short_description_content')->text();
                        // $io->info('take image');
                        $image = $crawler->filter('#bigpic')->attr('src');
                        // $io->info('take description');
                        $description = $crawler->filter('#tab-description')->text();
                        // $io->info('take age');
                        // $age = $crawler->filter('.age')->text();
                        if ($crawler->filter('.nb_joueurs')->count() != 0) {
                            // $io->info('take players');
                            $players = $crawler->filter('.nb_joueurs')->text();
                        };
                        if ($crawler->filter('.duree_partie')->count() != 0) {
                            // $io->info('take time of game');
                            $timeOf = $crawler->filter('.duree_partie')->text();
                        }


                        if ($timeOf === 'moins de 30mn') {
                            $timeOf = 20;
                        } elseif ($timeOf === '30mn à 1h') {
                            $timeOf = 45;
                        } elseif ($timeOf === '1 à 2h') {
                            $timeOf = 90;
                        } elseif ($timeOf === '2 à 3h') {
                            $timeOf = 150;
                        } elseif ($timeOf === '2 à 3h, 3 à 4h') {
                            $timeOf = 180;
                        } elseif ($timeOf === '3 à 4h') {
                            $timeOf = 210;
                        } elseif ($timeOf === 20) {
                            $timeOf = 0;
                        } else {
                            $io->error('error $timeOf it\'s writen : ' . $timeOf);
                            return Command::FAILURE;
                        }

                        // $difficulty = $crawler->filter('.duree_partie')->text();
                        $boardGame[1] = [
                            'name' => $gameTitle,
                            'resume' => $resumeJeux,
                            'description' => $description,
                            // 'difficulty' => $difficulty,
                            'images' => $image,
                            // 'age' => $age,
                            'players' => $players,
                            'time_of' => $timeOf,
                        ];

                        if ($this->gameRepository->findBy(['name' => $boardGame[1]['name']])) {
                            $io->error('The game ' . $boardGame[1]['name'] . ' is already on the database');
                            $gameWasInBDD = true;
                        } else {
                            $io->success('The game ' . $boardGame[1]['name'] . ' is collected');
                            $gameWasInBDD = false;

                            $slug = strtok($boardGame[1]['name'], '-');
                            $slug = strtolower($slug);
                            $slug = $this->sluggerInterface->slug($slug);


                            $game = new Game();
                            $game->setName($boardGame[1]['name']);
                            $game->setDescription($boardGame[1]['description']);
                            $game->setImages($boardGame[1]['images']);
                            $game->setPlayers($boardGame[1]['players']);
                            $game->setTimeOf($boardGame[1]['time_of']);
                            $game->setSlug($slug);

                            $this->manager->persist($game);
                            $this->manager->flush();
                        }



                        $numberOfArticle ++;
                        if ($input->getOption('new') && $gameWasInBDD === true) {
                            $ignoredGame++;
                        } else {
                            $numberOfGame ++;
                        }

                        if ($gameWasInBDD === false) {
                            $youGotTheGame ++;
                        }
                    } else {
                        $linkNextPage = $crawler->selectLink('Suivant')->link();
                        $linkPrincipal = $linkNextPage;
                        $crawler = $client->click($linkNextPage);
                        $numberOfArticle = 0;
                        $nextPage = true;
                    }
                }
                $nextPage = false;
                $numberOfPage ++;
            }
        };

        if ($input->getOption('search')) {
            $form = $crawler->selectButton('Rechercher')->form();
            $crawler = $client->submit($form, ['search_query' => $gameYouSearch]);
            $titreLien = $crawler->filter('.s_title_block')->eq(0);
            $titreLien = $titreLien->children()->text();
                $linkSecond = $crawler->selectLink($titreLien)->link();
                $crawler = $client->click($linkSecond);
                // $io->info('take name');
                $gameTitle = $crawler->filter('#product_name')->text();
                // $io->info('take resume');
                $resumeJeux = $crawler->filter('#short_description_content')->text();
                // $io->info('take image');
                $image = $crawler->filter('#bigpic')->attr('src');
                // $io->info('take description');
                $description = $crawler->filter('#tab-description')->text();
                // $io->info('take age');
                // $age = $crawler->filter('.age')->text();
                if ($crawler->filter('.nb_joueurs')->count() != 0) {
                    // $io->info('take players');
                    $players = $crawler->filter('.nb_joueurs')->text();
                } else {
                    $players = null;
                }
                if ($crawler->filter('.duree_partie')->count() != 0) {
                    // $io->info('take time of game');
                    $timeOf = $crawler->filter('.duree_partie')->text();
                    if ($timeOf === 'moins de 30mn') {
                        $timeOf = 20;
                    } elseif ($timeOf === '30mn à 1h') {
                        $timeOf = 45;
                    } elseif ($timeOf === '1 à 2h') {
                        $timeOf = 90;
                    } elseif ($timeOf === '2 à 3h') {
                        $timeOf = 150;
                    } elseif ($timeOf === '2 à 3h, 3 à 4h') {
                        $timeOf = 180;
                    } elseif ($timeOf === '3 à 4h') {
                        $timeOf = 210;
                    } elseif ($timeOf === 20) {
                        $timeOf = 0;
                    } else {
                        $io->error('error $timeOf it\'s writen : ' . $timeOf);
                        return Command::FAILURE;
                    }
                } else {
                    $timeOf = null;
                }


                

                // $difficulty = $crawler->filter('.duree_partie')->text();
                $boardGame[1] = [
                    'name' => $gameTitle,
                    'resume' => $resumeJeux,
                    'description' => $description,
                    // 'difficulty' => $difficulty,
                    'images' => $image,
                    // 'age' => $age,
                    'players' => $players,
                    'time_of' => $timeOf,
                ];

                if ($this->gameRepository->findBy(['name' => $boardGame[1]['name']])) {
                    $io->error('The game ' . $boardGame[1]['name'] . ' is already on the database');
                    $gameWasInBDD = true;
                } else {
                    $io->success('The game ' . $boardGame[1]['name'] . ' is collected');
                    $gameWasInBDD = false;

                    $slug = strtok($boardGame[1]['name'], '-');
                    $slug = strtolower($slug);
                    $slug = $this->sluggerInterface->slug($slug);


                    $game = new Game();
                    $game->setName($boardGame[1]['name']);
                    $game->setDescription($boardGame[1]['description']);
                    $game->setImages($boardGame[1]['images']);
                    $game->setPlayers($boardGame[1]['players']);
                    $game->setTimeOf($boardGame[1]['time_of']);
                    $game->setSlug($slug);

                    $this->manager->persist($game);
                    $this->manager->flush();
                }
            return Command::SUCCESS;
        }

        if ($input->getOption('new')) {
            $io->success('you got ' . $youGotTheGame . ' game of ' . $numberOfGame++ . ' asking | ' . $ignoredGame . ' was ignored');
        } else {
            $io->success('you got ' . $youGotTheGame . ' game of ' . $numberOfGame++ . ' asking');
        };

        if ($youGotTheGame !== $numberOfGame++) {
            $io->info('use [--new]||[-a] to ignore the game already in the database');
        };

        return Command::SUCCESS;
    }
}
