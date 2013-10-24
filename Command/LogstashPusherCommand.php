<?php


namespace ServerGrove\LogstashPusher\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LogstashPusherCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('push')
            ->setDescription('Push a message')
            ->addArgument(
                'message',
                InputArgument::REQUIRED,
                'Message to send'
            )
            ->addOption('tags', 't', InputOption::VALUE_OPTIONAL)
            ->addOption('fields', 'f', InputOption::VALUE_OPTIONAL)
            ->addOption('host', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $input->getArgument('message');
        $tags = $input->getOption('tags');
        $fields = $input->getOption('fields');
        $host = $input->getOption('host');

        if (!file_exists('config/config.yml')) {
            throw new \InvalidArgumentException("No config file");
        }

        $config = \Symfony\Component\Yaml\Yaml::parse('config/config.yml');

        $redis = new \Predis\Client($config['redis']);

        $data = array(
           'message' => $message,
        );

        $data['host'] = $host ? $host : gethostname();

        if ($tags) {
            $data['tags'] = json_decode($tags, true);
        }

        if ($fields) {
            $data = array_merge($data, json_decode($fields, true));
        }

        $json =  json_encode($data);
        if (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity()) {
            $output->writeln(sprintf("Sending <info>%s</info>", $json));
        }

        $result = $redis->lpush($config['redis_key'], $json);

        return $result == 1 ? 0 : 1;
    }
}