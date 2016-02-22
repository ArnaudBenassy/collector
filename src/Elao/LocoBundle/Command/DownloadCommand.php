<?php

/*
 * This file is part of the DRYVA project.
 *
 * Copyright (C) 2016 DRYVA
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\LocoBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadCommand extends ContainerAwareCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('translation:loco:download')
            ->setDescription('Download latest loco translation files');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('loco.handler')->download();
    }
}
