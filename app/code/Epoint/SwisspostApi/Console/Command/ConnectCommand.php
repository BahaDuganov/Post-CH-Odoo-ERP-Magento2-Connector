<?php

namespace Epoint\SwisspostApi\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Epoint\SwisspostApi\Helper\Resource;
use \Magento\Framework\App\State as AppState;

class ConnectCommand extends Command
{
    /**
     * @var \Epoint\SwisspostApi\Helper\Resource
     */
    private $resource;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * ConnectCommand constructor.
     *
     * @param \Epoint\SwisspostApi\Helper\Resource $resource
     * @param \Magento\Framework\App\State         $appState
     */
    public function __construct(
        Resource $resource,
        AppState $appState
    ) {
        $this->resource = $resource;
        $this->appState = $appState;
        parent::__construct();
    }

    /**
     * Implement configure method.
     */
    protected function configure()
    {
        $this->setName('epoint-swisspostapi:connect')->setDescription(
            'Check API authentication parameters'
        );
    }

    /**
     * Execute command method.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Set area code.
        $this->appState->setAreaCode('adminhtml');

        if ($session_id = $this->resource->sessionAuthenticate()->get('session_id')) {
            $output->writeln(
                sprintf(
                    __('Swisspost API auth is working, session id: %s'),
                    $session_id
                )
            );
        } else {
            $output->writeln(__('Swisspost API auth is not working.'));
        }
    }
}
