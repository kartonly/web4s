<?php

declare(strict_types=1);

namespace App\Core\Order\Command;

use App\Core\Order\Document\Order;
use App\Core\Order\Repository\OrderRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateOrder extends Command
{
    protected static $defaultName = 'app:core:create-order';

    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        parent::__construct();

        $this->orderRepository = $orderRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new order.')
            ->setHelp('This command allows you to create a order...')
            ->addOption('title', null, InputOption::VALUE_REQUIRED, 'title')
            ->addOption('about', null, InputOption::VALUE_REQUIRED, 'about')
            ->addOption('status', null, InputOption::VALUE_REQUIRED, 'status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($order = $this->orderRepository->findOneBy(['title' => $input->getOption('title')])) {
            $output->writeln(
                [
                    'Order already exists!',
                    '============',
                    $this->formatOrderLine($order),
                ]
            );

            return Command::SUCCESS;
        }

        $order = new Order(
            $input->getOption('title'),
            $input->getOption('about'),
            $input->getOption('status')
        );
        $order->setTitle($input->getOption('title'));
        $order->setAbout($input->getOption('about'));
        $order->setStatus($input->getOption('status'));

        $this->orderRepository->save($order);

        $output->writeln(
            [
                'Order is created!',
                '============',
                $this->formatOrderLine($order),
            ]
        );

        return Command::SUCCESS;
    }

    private function formatOrderLine(Order $order): string
    {
        return sprintf(
            'id: %s, title: %s, about: %s, status: %s',
            $order->getId(),
            $order->getTitle(),
            $order->getAbout(),
            $order->getStatus(),
        );
    }

}