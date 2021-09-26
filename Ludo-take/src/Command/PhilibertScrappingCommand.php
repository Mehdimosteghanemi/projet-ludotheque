<?php

namespace App\Command;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhilibertScrappingCommand extends Command
{
    protected static $defaultName = 'game:scrapping';
    protected static $defaultDescription = 'Add a short description for your command';

    private $gameRepository;
    private $sluggerInterface;
    private $manager;
    private $client;
    private $crawler;
    private $io;
    public function __construct(EntityManagerInterface $manager, SluggerInterface $sluggerInterface, GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->sluggerInterface = $sluggerInterface;
        $this->manager = $manager;
        $this->client = new Client;
        $this->crawler = $this->client->request('GET', 'https://www.philibertnet.com/fr/');

        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('argument', InputArgument::OPTIONAL, 'number or name of game you want to scrapp')
            // ->addArgument('argument2', InputArgument::OPTIONAL, 'number of the link you want the search clicking')
            ->addOption('new', '-w', InputOption::VALUE_NONE, 'to doesn\'t include the game you already have on the adding count')
            ->addOption('search', '-s', InputOption::VALUE_NONE, 'write a game name into the string to check if the website got this and add the first result to BDD')
            ->addOption('searchexact', '-S', InputOption::VALUE_NONE, 'same than search but if the title you give is not the same propose the 10 first and let you choose')
            ->addOption('auto', '-a', InputOption::VALUE_NONE, 'skip automaticaly the choice')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        // If we don't have argument write an explaination text into the command and returne the command failed
        if ( !$input->getArgument('argument') ) {
            $this->io->writeln('');
            $this->io->writeln('<fg=green>This command need argument to work. You can write <fg=blue>php bin/console game:scrapping <int></> to scrap the number of argument games.</>') ;
            $this->io->writeln('<fg=green>Adding the <fg=red>[-w|--new]</> option to ignore the game who is already on database like this :</>');
            $this->io->writeln('<fg=blue>php bin/console game:scrapping <integer> -w</>');
            $this->io->writeln('');
            $this->io->writeln('<fg=yellow>More info with <fg=red>[-h|--help]</> option</>');
            $this->io->writeln('');
            return Command::FAILURE;
        }
        $useCommandWithSearchExact = false;
        $useCommandWithSearch = false;
        $numberIsChangeTo50 = false;
        // check if the argument is an integer or not
        if (is_numeric($input->getArgument('argument'))) {
           $numberToScrapp = $input->getArgument('argument');
           $gameYouSearch = false;
           if ( $numberToScrapp > 50 ) {
               $numberToScrapp = 50;
               $numberIsChangeTo50 = true;
           }
        } else {
            $gameYouSearch = $input->getArgument('argument'); 
            if (!$input->getOption('search')){
                if (!$input->getOption('searchexact')) {
                    $this->io->warning('You write : ' . $gameYouSearch . '.');
                    $this->io->writeln('');
                    $useCommandWithSearchExact = $this->io->confirm('<fg=green>use command : </>' . '<fg=blue>php bin/console --searchexact ' . $gameYouSearch . "</>", true);

                    if (!$useCommandWithSearchExact) {
                        $useCommandWithSearch = $this->io->confirm('<fg=green>use command : </>' . '<fg=blue>php bin/console --search ' . $gameYouSearch . "</>", true);
                        if (!$useCommandWithSearch) {
                            return Command::FAILURE;
                        }
                        
                    }
                    
            
            }
                }
                
        };
        
        $linkPrincipal = $this->crawler->selectLink('Jeux de société')->link();

        $informationArray = [
            'number of page' => 0,
            'number of game in the page' => 0,
            'number of game running' => 0,
            'number of game you got' => 0,
            'number of ignored game' => 0,
            'game was in the database' => false,
            'link principal' => $linkPrincipal,
            'game you search' => $gameYouSearch,
            'exact search' => false,
        ];

        if ($input->getOption('auto')) {
            $informationArray['skip auto'] = true;
        } else {
            $informationArray['skip auto'] = false;
        }

        if (!$gameYouSearch) {
                while ($numberToScrapp > $informationArray['number of game running']) {
                    $informationArray = $this->clickAndDrag($informationArray, $input->getOption('new'));
                }
        };

        if ($input->getOption('search') || $input->getOption('searchexact') || $useCommandWithSearch || $useCommandWithSearchExact) {
            if ($input->getOption('searchexact') || $useCommandWithSearchExact) {
                $informationArray['exact search'] = true;
            }
            if ( $this->searchingGame($informationArray) ) {
                return Command::SUCCESS;
            };
        }

        if ($input->getOption('new')) {
            $this->io->success('you got ' . $informationArray['number of game you got'] . ' game of ' . $informationArray['number of game running']++ . ' asking | ' . $informationArray['number of ignored game'] . ' was ignored');
        } else {
            $this->io->success('you got ' . $informationArray['number of game you got'] . ' game of ' . $informationArray['number of game running']++ . ' asking');
        };

        if ( $numberIsChangeTo50 === true ) {
            $this->io->warning('We change the number of game to 50 to preserve resource');
        }
        
        if ($informationArray['number of game you got'] !== $informationArray['number of game running']++) {
            $this->io->info('use [-w|--new] to ignore the game already in the database');
        };
        
        return Command::SUCCESS;
    }

    /**
     * This ethod take game of a webpage who show a list of game who is at the selected place
     * if this game is not on the database
     *
     * @param array $informationArray
     */
    private function clickAndDrag($informationArray, bool $optionNew) : array
    {
        // if we don't use option search we back to the list
        if (!$this->crawler || $informationArray['game was in the database'] === false) {
            // Back to the list of game
            $this->crawler = $this->client->click($informationArray['link principal']);
        }
         
        // Select the game in the list according to his number
        $gameToClick = $this->crawler->filter('.s_title_block')->eq($informationArray['number of game in the page']);

        // Checked if the game exist
        if (count($gameToClick) === 1) {
            // take the text of link, with this text select the link and click on it
            $gameName = $gameToClick->children()->text();
            if ($informationArray['exact search'] === true && $gameName !== $informationArray['game you search']) {
                $informationArray['proposition of game'][] = $gameName;
                $informationArray['last proposition of game'] = $gameName;
                $informationArray['number of game in the page']++;
                return $informationArray;
            } else {
                $informationArray['last proposition of game'] = $gameName;
            }
            if ($this->gameNotOnData($gameName)) {
                // we check if the game name is clear and modify if we need
                $gameName = $this->titleIsItClear($gameName, $informationArray);
                if ( $gameName !== 'Skip this game please' && $this->gameNotOnData($gameName) ) {

                    // We are looking if the game is on the database before cliking on it
                    if ($this->gameNotOnData($gameName)) {
                        // Make the information to false for back to the list page at the next loop
                        $informationArray['game was in the database'] = false;

                        $boardGame = $this->addNewGame($gameName, $informationArray);

                        // We put it on the database and show a message
                        if ($this->putIntoData($boardGame)) {
                            $this->io->success('The game ' . $gameName . ' is collected');
                        } else {
                            // is when the title was ambigous and the user want to skip so nothing hapend
                            $this->io->warning('The game ' . $gameName . ' is uncollected');
                        };
                    }
                } else if ($gameName === 'Skip this game please'){
                    $this->io->warning('Game skip');
                } else {
                    $this->io->error('The game ' . $gameName . ' is already on the database');
                    $informationArray['game was in the database'] = true;
                }
            } else {
                    $this->io->error('The game ' . $gameName . ' is already on the database');
                    $informationArray['game was in the database'] = true;
            };

            $informationArray['number of game in the page']++;
            if ($optionNew && $informationArray['game was in the database'] === true || $gameName === 'Skip this game please') {
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
            $informationArray['link principal'] = $this->crawler->selectLink('Suivant')->link();
            $this->crawler = $this->client->click($informationArray['link principal']);
            // refresh to 0 the numberOfArticle to restart browse all the page
            $informationArray['number of game in the page'] = 0;
        }

        // we return a to say the work is find.
        return $informationArray;
    }

    /**
     * Method who search a game and take the first result on the bebsite
     *
     * @param array $informationArray
     */
    private function searchingGame($informationArray) : bool
    {
        // we found the search button
        $form = $this->crawler->selectButton('Rechercher')->form();
        // we click & submit the game than we are searching
        $this->crawler = $this->client->submit($form, ['search_query' => $informationArray['game you search']]);
        // we are setting the default in true for dont change the crawler in clickAndDragg() method
        $informationArray['game was in the database'] = true;
        $informationArray['last proposition of game'] = null;
        // we used the method to take the information
        while ( $informationArray['game you search'] !== $informationArray['last proposition of game'] ) {
            $informationArray = $this->clickAndDrag($informationArray, false);
            if ($informationArray['exact search'] === false || $informationArray['game you search'] === $informationArray['last proposition of game']) {
                return true;
            }
            if (count($informationArray['proposition of game']) === 5 && $informationArray['exact search'] === true) {
                $informationArray['proposition of game'][] = '<fg=red>No one, I want to exit</>';
                $informationArray['game you search'] = $this->io->choice('we don\'t found the title you were asking, please select the one you want', $informationArray['proposition of game'], $informationArray['proposition of game'][0]);
                if ( $informationArray['game you search'] === '<fg=red>No one, I want to exit</>' ) {
                    die;
                }
                $informationArray['last proposition of game'] = $informationArray['game you search'];

                

                foreach ($informationArray['proposition of game'] as $key => $value) {
                    if ($value === $informationArray['game you search']) {
                        $informationArray['number of game in the page'] = $key;
                        $informationArray = $this->clickAndDrag($informationArray, false);
                    }
                }
                
            }
        }
        return true;
    }

    /**
     * Method who transform the sting whit the information time of a game
     * into a median integer in minute
     */
    private function timeOfToInteger(string $timeOf) : int
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
            $this->io->error('error $timeOf it\'s writen : ' . $timeOf);
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
        // Pass every character into lower case
        $slug = strtolower($gameName);
        return $this->sluggerInterface->slug($slug);
    }

    private function titleIsItClear(string $gameName, array $informationArray) : string
    {
        if (strpos($gameName, '-')){

            if ( $informationArray['skip auto'] ){
                $newGameName = 'Skip this game please';
            } else {
               $newGameName = $this->io->choice('The title is not clear what do you prefer ?', [substr(strtok($gameName, '-'), 0, -1), $gameName, '<fg=blue>I want to write it by myself</>', '<fg=yellow>No one, I want to skip this one</>', '<fg=red>No one, I want to exit</>'], $gameName); 
            }
            if ( $newGameName === '<fg=yellow>No one, I want to skip this one</>' ) {
                $newGameName = 'Skip this game please';
            }
            if ( $newGameName === '<fg=blue>I want to write it by myself</>' ) {
                $newGameName = $this->io->ask("The origin game name was : <fg=red>$gameName</>, please write the game name you want");
            }
            if ( $newGameName === '<fg=red>No one, I want to exit</>' ) {
                die;
            }
        } else {
            $newGameName = $gameName;
        }
        return $newGameName;
    }

    /**
     * Method who add into database a new game with the information we give them
     * @param array $boardGame
     * @return boolean
     */
    private function putIntoData(array $boardGame) : bool
    {
        if ( $boardGame['name'] === '<fg=yellow>No one, I want to skip this one</>' ) {
            return false;
        }
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
     * @return array
     */
    private function addNewGame($gameName, array $informationArray) : array
    {
        $gameLink = $this->crawler->selectLink($gameName)->link();
        $this->crawler = $this->client->click($gameLink);
        
        // $this->io->info('take resume');
        $resumeJeux = $this->crawler->filter('#short_description_content')->text();
        // $this->io->info('take image');
        $image = $this->crawler->filter('#bigpic')->attr('src');
        // $this->io->info('take description');
        $description = $this->crawler->filter('#tab-description')->text();
        if ($this->crawler->filter('.nb_joueurs')->count() != 0) {
            // $this->io->info('take players');
            $players = $this->crawler->filter('.nb_joueurs')->text();
        } else {
            $players = null;
        };
        if ($this->crawler->filter('.duree_partie')->count() != 0) {
            // $this->io->info('take time of game');
            $timeOfRaw = $this->crawler->filter('.duree_partie')->text();
            // transform the timeOf information to integrer for stocking to the database
            $timeOf = $this->timeOfToInteger($timeOfRaw);
        } else {
            $timeOf = null;
        };

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
