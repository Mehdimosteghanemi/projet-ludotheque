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
        $crawler = $client->request('GET', 'https://www.philibertnet.com/fr/');
        $linkPrincipal = $crawler->selectLink('Jeux de société')->link();

        $informationArray = [
            'number of page' => 0,
            'number of game in the page' => 0,
            'number of game running' => 0,
            'number of game you got' => 0,
            'number of ignored game' => 0,
            'game was in the database' => false,
            'link principal' => $linkPrincipal,
        ];

        if (!isset($gameYouSearch)) {
            
                while ($numberToScrapp > $informationArray['number of game running']) {

                    $informationArray = $this->clickAndDrag($client, $io, $informationArray, $input->getOption('new'));

                }
        };

        if ($input->getOption('search')) {

            if ( $this->searchingGame($client, $io, $informationArray, $crawler, $gameYouSearch) ) {
                return Command::SUCCESS;
            };
        }

        if ($input->getOption('new')) {
            $io->success('you got ' . $informationArray['number of game you got'] . ' game of ' . $informationArray['number of game running']++ . ' asking | ' . $informationArray['number of ignored game'] . ' was ignored');
        } else {
            $io->success('you got ' . $informationArray['number of game you got'] . ' game of ' . $informationArray['number of game running']++ . ' asking');
        };

        if ($informationArray['number of game you got'] !== $informationArray['number of game running']++) {
            $io->info('use [--new]||[-a] to ignore the game already in the database');
        };

        return Command::SUCCESS;
    }

    /**
     * This ethod take game of a webpage who show a list of game who is at the selected place
     * if this game is not on the database
     *
     * @param Goute $client
     * @param SymfonyStyle $io
     * @param array $informationArray
     */
    private function clickAndDrag($client, $io, $informationArray, bool $optionNew, $crawler=false) : array
    {
        // if we don't use option search we back to the list
        if (!$crawler || $informationArray['game was in the database'] === false) {
            // Back to the list of game
            $crawler = $client->click($informationArray['link principal']);
        }
         
        // Select the game in the list according to his number
        $gameToClick = $crawler->filter('.s_title_block')->eq($informationArray['number of game in the page']);

        // Checked if the game exist
        if (count($gameToClick) === 1) {
            // take the text of link, with this text select the link and click on it
            $gameName = $gameToClick->children()->text();

            // We are looking if the game is on the database before cliking on it
            if ( $this->gameNotOnData($gameName) )
            {
                // Make the information to false for back to the list page at the next loop
                $informationArray['game was in the database'] = false;

                $boardGame = $this->addNewGame($gameName, $crawler, $client, $io);

                // We put it on the database and show a message
                if ( $this->putIntoData($boardGame) ) {
                    $io->success('The game ' . $gameName . ' is collected');
                };
            } else {
                $io->error('The game ' . $gameName . ' is already on the database');
                $informationArray['game was in the database'] = true;
            };

            $informationArray['number of game in the page']++;
            if ($optionNew && $informationArray['game was in the database'] === true) {
                $informationArray['number of ignored game']++;
            } else {
                $informationArray['number of game running']++;
            }

            if ($informationArray['game was in the database'] === false) {
                $informationArray['number of game you got']++;
            }

        // if the article number doesn't exist we change the selectLink to the next page of the website (each page have about 50 games) 
        } else {
            // we select "suivant"
            $informationArray['link principal'] = $crawler->selectLink('Suivant')->link();
            // refresh to 0 the numberOfArticle to restart browse all the page
            $informationArray['number of game in the page'] = 0;
        }

        // we return the updated informationArray.
        return $informationArray;
    }

    /**
     * Method who search a game and take the first result on the bebsite
     *
     * @param Goute $client
     * @param SymfonyStyle $io
     * @param array $informationArray
     * @param request $crawler
     * @param string $gameYouSearch
     */
    private function searchingGame($client, $io, $informationArray, $crawler, $gameYouSearch) : bool
    {
        // we found the search button
        $form = $crawler->selectButton('Rechercher')->form();
        // we click & submit the game than we are searching
        $crawler = $client->submit($form, ['search_query' => $gameYouSearch]);
        // we are setting the default in true for dont change the crawler in clickAndDragg() method
        $informationArray['game was in the database'] = true;
        // we used the method to take the information
        $this->clickAndDrag($client, $io, $informationArray, false, $crawler);
        
        return true;
    }

    /**
     * Method who transform the sting whit the information time of a game
     * into a median integer in minute
     */
    private function timeOfToInteger(string $timeOf, $io) : int
    {
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
        return $timeOf;
    }
    
    /**
     * Method wo search if the game is on the database
     */
    private function gameNotOnData(string $gameName) : bool
    {
        if ($this->gameRepository->findBy(['name' => $gameName])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Method to make slug whith the title like this :
     * Title of the Game = title_of_the_game
     */
    private function sluggingName(string $gameName) : string 
    { 
        // delete all character after the '-' 
        // The website we scrapping split the name before and other infomation like promotion after
        $slug = strtok($gameName, '-');
        // Past every character into lower case
        $slug = strtolower($slug);
        return $this->sluggerInterface->slug($slug);
    }

    /**
     * Method who add into database a new game with the information we give them
     * @param array $boardGame
     * @return boolean
     */
    private function putIntoData(array $boardGame) : bool
    {
        $game = new Game();
        $game->setName($boardGame['name']);
        $game->setDescription($boardGame['description']);
        $game->setImages($boardGame['images']);
        $game->setPlayers($boardGame['players']);
        $game->setTimeOf($boardGame['time_of']);
        $game->setSlug($boardGame['slug']);

        $this->manager->persist($game);
        $this->manager->flush();
        
        return true;
    }

    /**
     * Method who collect the information of a game in the game list
     * It click on it and take the date
     *
     * @param string $gameName
     * @param request $crawler
     * @param Goute $client
     * @param symfonyStyle $io
     * @return array
     */
    private function addNewGame($gameName, $crawler, $client, $io) : array
    {
        $gameLink = $crawler->selectLink($gameName)->link();
        $crawler = $client->click($gameLink);

        // After we are on the game page taking the infomation if they already exist
        // Uncomment io info to know what is the next step (useful if you are a bug)
        // $io->info('take name');
        $gameName = $crawler->filter('#product_name')->text();
        // $io->info('take resume');
        $resumeJeux = $crawler->filter('#short_description_content')->text();
        // $io->info('take image');
        $image = $crawler->filter('#bigpic')->attr('src');
        // $io->info('take description');
        $description = $crawler->filter('#tab-description')->text();
        if ($crawler->filter('.nb_joueurs')->count() != 0) {
            // $io->info('take players');
            $players = $crawler->filter('.nb_joueurs')->text();
        } else {
            $players = null;
        };
        if ($crawler->filter('.duree_partie')->count() != 0) {
            // $io->info('take time of game');
            $timeOfRaw = $crawler->filter('.duree_partie')->text();
            // transform the timeOf information to integrer for stocking to the database
            $timeOf = $this->timeOfToInteger($timeOfRaw, $io);
        }

        // create slug whit the game name
        $slug = $this->sluggingName($gameName);

        // Stock information than we scrapp into an array
        $boardGame = [
            'name' => $gameName,
            'resume' => $resumeJeux,
            'description' => $description,
            'images' => $image,
            'players' => $players,
            'time_of' => $timeOf,
            'slug' => $slug,
            // 'difficulty' => $difficulty,
        ];

        return $boardGame;
    }
}
