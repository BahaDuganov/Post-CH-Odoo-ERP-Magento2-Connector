<?php

namespace Epoint\SwisspostCustomer\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Magento\Customer\Api\CustomerRepositoryInterface\Proxy as CustomerRepository;
use Epoint\SwisspostApi\Model\Api\Account\Proxy as AccountApiModel;
use \Magento\Framework\App\State as AppState;

class searchReadAccountCommand extends Command
{
    /**
     * Name argument
     */
    const CUSTOMER_ID_ARGUMENT = 'customer';

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * @var \Epoint\SwisspostApi\Model\Api\Account
     */
    private $accountApiModel;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * searchReadAccountCommand constructor.
     *
     * @param \Magento\Customer\Api\CustomerRepositoryInterface\Proxy $customerRepositoryInterface
     * @param \Epoint\SwisspostApi\Model\Api\Account\Proxy            $accountApiModel
     * @param \Magento\Framework\App\State                            $appState
     */
    public function __construct(
        CustomerRepository $customerRepositoryInterface,
        AccountApiModel $accountApiModel,
        AppState $appState
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->accountApiModel = $accountApiModel;
        $this->appState = $appState;
        parent::__construct();
    }

    /**
     * Implement configure method.
     */
    protected function configure()
    {
        $this->setName('epoint-swisspostapi:searchReadAccount')
            ->setDescription(__('Run searchReadAccount for a customer'))
            ->setDefinition(
                [
                    new InputArgument(
                        self::CUSTOMER_ID_ARGUMENT,
                        InputArgument::REQUIRED,
                        'Customer'
                    )]
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

        $customer_id = $input->getArgument(self::CUSTOMER_ID_ARGUMENT);
        if (!$customer_id) {
            throw new \Exception(__('Missing customer id.'));
        }
        $customer = $this->customerRepositoryInterface->getById($customer_id);
        if (!$customer || !$customer->getId()) {
            throw new \Exception(__('Missing customer.'));
        }
        $account = $this->accountApiModel->load($customer);
        print_r($account->getData());
    }
}
