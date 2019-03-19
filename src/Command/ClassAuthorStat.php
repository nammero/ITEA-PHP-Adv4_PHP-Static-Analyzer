<?php

/*
 * This file is part of the "default-project" package.
 *
 * (c) Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Greeflas\StaticAnalyzer\Command;

use Greeflas\StaticAnalyzer\Analyzer\ClassAuthor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ClassAuthorStat extends Command
{
    protected function configure()
    {
        $this
            ->setName('class-author-stat')
            ->setDescription('Shows statistic about classes/interfaces/traits authors')
            ->addArgument(
                'projectDir',
                InputArgument::REQUIRED,
                'Root path of project directory'
            )
            ->addArgument(
                'developerEmail',
                InputArgument::REQUIRED,
                'Email of needed developer'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectDir = $input->getArgument('projectDir');
        $email = $input->getArgument('developerEmail');

        $analyzer = new ClassAuthor($projectDir, $email);
        $count = $analyzer->analyze();

        $output->writeln(\sprintf(
            '<info>Developer %s created %d of classes/interfaces/traits</info>',
            $email,
            $count
        ));
    }
}
