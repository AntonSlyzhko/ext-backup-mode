<?php

namespace Espo\Modules\BackupMode\Classes\ConsoleCommands;

use Espo\Core\Console\Command;
use Espo\Core\Console\Command\Params;
use Espo\Core\Console\IO;
use Espo\Core\Utils\Config;

/**
 * @noinspection PhpUnused
 */
class DisableBackMode implements Command
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

        if ($actualParams['maintenanceMode']) {
            $this->configWriter->set('maintenanceMode', false);

            $save = true;
        }

        if ($actualParams['cronDisabled']) {
            $this->configWriter->set('cronDisabled', false);

            $save = true;
        }

        if (!$actualParams['useCache']) {
            $this->configWriter->set('useCache', true);

            $save = true;
        }

        if ($save) {
            $this->configWriter->save();
            $io->writeLine("Backup mode disabled.");
            return;
        }

        $io->writeLine("All config parameters have already been set.");
    }
}
