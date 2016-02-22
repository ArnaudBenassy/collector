<?php

/*
 * This file is part of the DRYVA project.
 *
 * Copyright (C) 2016 DRYVA
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\LocoBundle\Loco;

use Guzzle\Batch\BatchBuilder;
use Guzzle\Http\Client;

class Handler
{
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config    = $config;
        $this->client    = new Client('https://localise.biz/api/');
    }

    /**
     * Download loco files
     */
    public function download()
    {
        $batch = BatchBuilder::factory()
            ->transferRequests(10)
            ->autoFlushAt(10)
            ->build();

        foreach ($this->config as $config) {
            foreach ($config['locales'] as $localeKey => $localeValue) {
                if (!is_dir($config['target'])) {
                    mkdir($config['target'], 0777, true);
                }

                $query = [
                    'key'          => $config['key'],
                    'format'       => $config['format'],
                    'index'        => $config['index'],
                ];

                $url         = sprintf('export/locale/%s.%s?%s', $localeValue, $config['extension'], http_build_query($query));
                $savePath    = sprintf('%s/messages.%s.%s', $config['target'], $localeKey, $config['extension']);

                $batch->add($this->client->get($url, [], ['save_to' => $savePath]));
            }
        }

        $batch->flush();
    }

    public function create($missingTranslations)
    {
        $batch = BatchBuilder::factory()
            ->transferRequests(10)
            ->autoFlushAt(10)
            ->build();

        foreach ($this->config as $config) {
            foreach ($missingTranslations as $translation) {
                $query = [
                    'key' => $config['key'],
                ];

                $url = sprintf('assets?%s', http_build_query($query));

                $batch->add(
                    $this->client->post(
                        $url, null, [
                            'name' => $translation['id'],
                            'id' => $translation['id']
                        ]
                    )
                );
            }
        }

        $batch->flush();
    }
}
