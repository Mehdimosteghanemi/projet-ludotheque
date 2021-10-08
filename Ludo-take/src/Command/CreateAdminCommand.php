<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'create:admin';
    protected static $defaultDescription = 'Add a short description for your command';

    private $hasher;
    private $manager;
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $manager)
    {
        $this->hasher = $passwordHasher;
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'name of mail')
            ->addArgument('password', InputArgument::REQUIRED, 'the password you want')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
    

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->hasher->hashPassword(
            $user,
            $password
        ));
        $user->setRoles(["ROLE_SUPER_ADMIN"]);
        $user->setFirstname('Ylusse');
        $user->setLastname('21');
        $user->setDeliveryMode(1);
        $user->setAddressRoad('Ici');
        $user->setAddressNumber(42);
        $user->setAddressZipCode(75000);
        $user->setAddressCity('Paris');
        $user->setStatus(1);

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('You just create a new SUPER_ADMIN named ' . $email . ' !');

        return Command::SUCCESS;
    }
}
