<?php

namespace Espo\Modules\BackupMode\Classes\ConsoleCommands;

use Espo\Core\Console\Command;
use Espo\Core\Console\Command\Params;
use Espo\Core\Console\IO;
use Espo\Core\Utils\Config;

/**
 * @noinspection PhpUnused
 */
class EnableBackMode implements Command
{
    public function __construct(
        private Config $config,
        private Config\ConfigWriter $configWriter
    ) {}

    public function run(Params $params, IO $io): void
    {
        $actualParams = [
            'maintenanceMode' => $this->config->get('maintenanceMode'),
            'cronDisabled' => $this->config->get('cronDisabled'),
            'useCache' => $this->config->get('useCache'),
        ];

        $save = false;

        if (!$actualParams['maintenanceMode']) {
            $this->configWriter->set('maintenanceMode', true);

            $save = true;
        }

        if (!$actualParams['cronDisabled']) {
            $this->configWriter->set('cronDisabled', true);

            $save = true;
        }

        if ($actualParams['useCache']) {
            $this->configWriter->set('useCache', false);

            $save = true;
        }

        if ($save) {
            $this->configWriter->save();
            $io->writeLine("Backup mode enabled.");
            return;
        }

        $io->writeLine("All config parameters have already been set.");
    }
}
