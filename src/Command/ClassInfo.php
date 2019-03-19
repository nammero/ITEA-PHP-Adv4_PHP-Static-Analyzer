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

use Greeflas\StaticAnalyzer\Analyzer\ClassInfoAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClassInfo - output information about class to the terminal
 */
class ClassInfo extends Command
{
    /**
     * Command properties.
     */
    protected function configure()
    {
        $this
            ->setName('show-class-info')
            ->setDescription('Shows information about classes/interfaces/traits authors')
            ->addArgument(
                'fullClassName',
                InputArgument::REQUIRED,
                'A class name for information'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullClassName = $input->getArgument('fullClassName');

        $analyzer = new ClassInfoAnalyzer($fullClassName);
        $infoData = $analyzer->analyze();
        $properties = $infoData->getClassProperties();
        $methods = $infoData->getClassMethods();

        $output->writeln(\sprintf(
            '<info>Class: %s is %s</info>',
            $fullClassName,
            $infoData->getClassType()
        ));
        $output->writeln('<info>Properties:</info>');

        foreach ($properties as $propertyName => $count) {
            $output->writeln(\sprintf(
                '<info>      %s: %d</info>',
                $propertyName,
                $count
            ));
        }

        $output->writeln('<info>Methods:</info>');

        foreach ($methods as $mathodName => $count) {
            $output->writeln(\sprintf(
                '<info>      %s: %d</info>',
                $mathodName,
                $count
            ));
        }
    }
}

