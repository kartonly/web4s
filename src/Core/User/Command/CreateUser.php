<?php

declare(strict_types=1);

namespace App\Core\User\Command;

use App\Core\User\Document\User;
use App\Core\User\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    protected static $defaultName = 'app:core:create-user';

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addOption('phone', null, InputOption::VALUE_REQUIRED, 'Phone')
            ->addOption('firstName', null, InputOption::VALUE_OPTIONAL, 'First name')
            ->addOption('lastName', null, InputOption::VALUE_OPTIONAL, 'Last name')
            ->addOption('roles', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Roles')
            ->addOption('apiToken', null, InputOption::VALUE_OPTIONAL, 'Api Token');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($user = $this->userRepository->findOneBy(['phone' => $input->getOption('phone')])) {
            $output->writeln(
                [
                    'User already exists!',
                    '============',
                    $this->formatUserLine($user),
                ]
            );

            return Command::SUCCESS;
        }

        $user = new User(
            $input->getOption('phone'),
            $input->getOption('roles'),
            $input->getOption('apiToken')
        );
        $user->setFirstName($input->getOption('firstName'));
        $user->setLastName($input->getOption('lastName'));

        $this->userRepository->save($user);

        $output->writeln(
            [
                'User is created!',
                '============',
                $this->formatUserLine($user),
            ]
        );

        return Command::SUCCESS;
    }

    private function formatUserLine(User $user): string
    {
        return sprintf(
            'id: %s, name: %s, lastname: %s, phone: %s, roles: %s, token: %s',
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPhone(),
            implode(',', $user->getRoles()),
            $user->getApiToken(),
        );
    }
}
