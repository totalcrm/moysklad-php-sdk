<?php

namespace TotalCRM\MoySklad\Components\MutationBuilders;

use Exception;
use TotalCRM\MoySklad\Components\Specs\LinkingSpecs;
use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Account;
use TotalCRM\MoySklad\Entities\Cashier;
use TotalCRM\MoySklad\Entities\ContactPerson;
use TotalCRM\MoySklad\Entities\Contract;
use TotalCRM\MoySklad\Entities\Counterparty;
use TotalCRM\MoySklad\Entities\Country;
use TotalCRM\MoySklad\Entities\Currency;
use TotalCRM\MoySklad\Entities\Discount;
use TotalCRM\MoySklad\Entities\Documents\Cash\CashIn;
use TotalCRM\MoySklad\Entities\Documents\Cash\CashOut;
use TotalCRM\MoySklad\Entities\Documents\Factures\FactureIn;
use TotalCRM\MoySklad\Entities\Documents\Factures\FactureOut;
use TotalCRM\MoySklad\Entities\Documents\Inventory;
use TotalCRM\MoySklad\Entities\Documents\Invoices\InvoiceIn;
use TotalCRM\MoySklad\Entities\Documents\Invoices\InvoiceOut;
use TotalCRM\MoySklad\Entities\Documents\Movements\Demand;
use TotalCRM\MoySklad\Entities\Documents\Movements\Enter;
use TotalCRM\MoySklad\Entities\Documents\Movements\Loss;
use TotalCRM\MoySklad\Entities\Documents\Movements\Move;
use TotalCRM\MoySklad\Entities\Documents\Movements\Supply;
use TotalCRM\MoySklad\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\MoySklad\Entities\Documents\Orders\PurchaseOrder;
use TotalCRM\MoySklad\Entities\Documents\Payments\PaymentIn;
use TotalCRM\MoySklad\Entities\Documents\Payments\PaymentOut;
use TotalCRM\MoySklad\Entities\Documents\Positions\CustomerOrderPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\InventoryPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\EnterPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\LossPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\MovePosition;
use TotalCRM\MoySklad\Entities\Documents\RetailShift;
use TotalCRM\MoySklad\Entities\Documents\PriceLists\PriceList;
use TotalCRM\MoySklad\Entities\Documents\Processings\Processing;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingOrder;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingPlan;
use TotalCRM\MoySklad\Entities\Documents\Retail\RetailDemand;
use TotalCRM\MoySklad\Entities\Documents\Retail\RetailSalesReturn;
use TotalCRM\MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashIn;
use TotalCRM\MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashOut;
use TotalCRM\MoySklad\Entities\Documents\Returns\PurchaseReturn;
use TotalCRM\MoySklad\Entities\Documents\Returns\SalesReturn;
use TotalCRM\MoySklad\Entities\Employee;
use TotalCRM\MoySklad\Entities\ExpenseItem;
use TotalCRM\MoySklad\Entities\Folders\ProductFolder;
use TotalCRM\MoySklad\Entities\Group;
use TotalCRM\MoySklad\Entities\Misc\Attribute;
use TotalCRM\MoySklad\Entities\Misc\Characteristics;
use TotalCRM\MoySklad\Entities\Misc\CompanySettings;
use TotalCRM\MoySklad\Entities\Misc\CustomEntity;
use TotalCRM\MoySklad\Entities\Misc\State;
use TotalCRM\MoySklad\Entities\Organization;
use TotalCRM\MoySklad\Entities\Products\AbstractProduct;
use TotalCRM\MoySklad\Entities\Products\Bundle;
use TotalCRM\MoySklad\Entities\Products\Consignment;
use TotalCRM\MoySklad\Entities\Products\Product;
use TotalCRM\MoySklad\Entities\Products\Service;
use TotalCRM\MoySklad\Entities\Products\Variant;
use TotalCRM\MoySklad\Entities\Project;
use TotalCRM\MoySklad\Entities\RetailStore;
use TotalCRM\MoySklad\Entities\Store;
use TotalCRM\MoySklad\Entities\Uom;
use TotalCRM\MoySklad\Entities\Bonusprogram;
use TotalCRM\MoySklad\Lists\EntityList;

/**
 * Class AbstractMutationBuilder
 * @package TotalCRM\MoySklad\Components\MutationBuilders
 */
abstract class AbstractMutationBuilder
{
    protected AbstractEntity $e;

    /**
     * AbstractMutationBuilder constructor.
     * @param AbstractEntity $entity
     */
    public function __construct(AbstractEntity $entity)
    {
        $this->e = $entity;
    }

    /**
     * @param Account $account
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addAccount(Account $account, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($account, $specs);
    }

    /**
     * @param Cashier $cashier
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCashier(Cashier $cashier, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($cashier, $specs);
    }

    /**
     * @param ContactPerson $contactPerson
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addContactPerson(ContactPerson $contactPerson, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($contactPerson, $specs);
    }

    /**
     * @param Contract $contract
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addContract(Contract $contract, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($contract, $specs);
    }

    /**
     * @param Counterparty $counterparty
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCounterparty(Counterparty $counterparty, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($counterparty, $specs, LinkingSpecs::create([
            "name" => "agent"
        ]));
    }

    /**
     * @param Country $country
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCountry(Country $country, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($country, $specs);
    }

    /**
     * @param Currency $currency
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCurrency(Currency $currency, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($currency, $specs);
    }

    /**
     * @param Discount $discount
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addDiscount(Discount $discount, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($discount, $specs);
    }

    /**
     * @param Employee $employee
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addEmployee(Employee $employee, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($employee, $specs);
    }

    /**
     * @param ExpenseItem $expenseItem
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addExpenseItem(ExpenseItem $expenseItem, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($expenseItem, $specs);
    }

    /**
     * @param Group $group
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addGroup(Group $group, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($group, $specs);
    }

    /**
     * @param Organization $organization
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addOrganization(Organization $organization, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($organization, $specs);
    }

    /**
     * @param Project $project
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProject(Project $project, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($project, $specs);
    }

    /**
     * @param RetailStore $retailStore
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailStore(RetailStore $retailStore, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($retailStore, $specs);
    }

    /**
     * @param Store $store
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addStore(Store $store, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($store, $specs);
    }

    /**
     * @param Uom $uom
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addUom(Uom $uom, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($uom, $specs);
    }

    /**
     * @param InvoiceIn $invoiceIn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addInvoiceIn(InvoiceIn $invoiceIn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($invoiceIn, $specs);
    }

    /**
     * @param InvoiceOut $invoiceOut
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addInvoiceOut(InvoiceOut $invoiceOut, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($invoiceOut, $specs);
    }

    /**
     * @param Demand $demand
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addDemand(Demand $demand, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($demand, $specs);
    }

    /**
     * @param Enter $enter
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addEnter(Enter $enter, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($enter, $specs);
    }

    /**
     * @param Loss $loss
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addLoss(Loss $loss, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($loss, $specs);
    }

    /**
     * @param Move $move
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addMove(Move $move, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($move, $specs);
    }

    /**
     * @param Supply $supply
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addSupply(Supply $supply, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($supply, $specs);
    }

    /**
     * @param CustomerOrder $customerOrder
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCustomerOrder(CustomerOrder $customerOrder, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($customerOrder, $specs, LinkingSpecs::create([
            'name' => 'customerOrder'
        ]));
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addPurchaseOrder(PurchaseOrder $purchaseOrder, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($purchaseOrder, $specs);
    }

    /**
     * @param Bonusprogram $bonusprogram
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addBonusprogram(Bonusprogram $bonusprogram, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($bonusprogram, $specs, LinkingSpecs::create([
            'name' => 'bonusProgram'
        ]));
    }

    /**
     * @param PaymentIn $paymentIn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addPaymentIn(PaymentIn $paymentIn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($paymentIn, $specs);
    }

    /**
     * @param PaymentOut $paymentOut
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addPaymentOut(PaymentOut $paymentOut, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($paymentOut, $specs);
    }

    /**
     * @param CustomerOrderPosition $customerOrderPosition
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCustomerOrderPosition(CustomerOrderPosition $customerOrderPosition, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($customerOrderPosition, $specs);
    }

    /**
     * @param InventoryPosition $inventoryPosition
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addInventoryPosition(InventoryPosition $inventoryPosition, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($inventoryPosition, $specs);
    }

    /**
     * @param EnterPosition $enterPosition
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addEnterPosition(EnterPosition $enterPosition, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($enterPosition, $specs);
    }

    /**
     * @param LossPosition $lossPosition
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addLossPosition(LossPosition $lossPosition, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($lossPosition, $specs);
    }

    /**
     * @param MovePosition $movePosition
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addMovePosition(MovePosition $movePosition, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($movePosition, $specs);
    }

    /**
     * @param RetailShift $retailShift
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailShift(RetailShift $retailShift, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($retailShift, $specs);
    }

    /**
     * @param ProductFolder $folder
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProductFolder(ProductFolder $folder, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($folder, $specs, LinkingSpecs::create([
            'name' => 'productFolder'
        ]));
    }

    /**
     * @param Attribute $attribute
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addAttribute(Attribute $attribute, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($attribute, $specs, LinkingSpecs::create([
            'multiple' => true,
            'name' => 'attributes'
        ]));
    }

    /**
     * @param Characteristics $characteristics
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCharacteristics(Characteristics $characteristics, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($characteristics, $specs);
    }

    /**
     * @param CompanySettings $companySettings
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCompanySettings(CompanySettings $companySettings, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($companySettings, $specs);
    }

    /**
     * @param CustomEntity $customEntity
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCustomEntity(CustomEntity $customEntity, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($customEntity, $specs);
    }

    /**
     * @param State $state
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addState(State $state, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($state, $specs);
    }

    /**
     * @param Consignment $consignment
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addConsignment(Consignment $consignment, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($consignment, $specs);
    }

    /**
     * @param Product $product
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProduct(Product $product, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($product, $specs);
    }

    /**
     * @param Bundle $bundle
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addBundle(Bundle $bundle, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($bundle, $specs);
    }

    /**
     * @param Service $service
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addService(Service $service, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($service, $specs);
    }

    /**
     * @param Variant $variant
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addVariant(Variant $variant, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($variant, $specs);
    }

    /**
     * @param CashIn $cashIn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCashIn(CashIn $cashIn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($cashIn, $specs);
    }

    /**
     * @param CashOut $cashOut
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCashOut(CashOut $cashOut, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($cashOut, $specs);
    }

    /**
     * @param RetailDemand $demand
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailDemand(RetailDemand $demand, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($demand, $specs);
    }

    /**
     * @param RetailSalesReturn $retailSalesReturn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailSalesReturn(RetailSalesReturn $retailSalesReturn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($retailSalesReturn, $specs);
    }

    /**
     * @param RetailDrawerCashIn $cashIn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailDrawerCashIn(RetailDrawerCashIn $cashIn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($cashIn, $specs);
    }

    /**
     * @param RetailDrawerCashOut $cashOut
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addRetailDrawerCashOut(RetailDrawerCashOut $cashOut, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($cashOut, $specs);
    }

    /**
     * @param SalesReturn $return
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addSalesReturn(SalesReturn $return, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($return, $specs);
    }

    /**
     * @param PurchaseReturn $return
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addPurchaseReturn(PurchaseReturn $return, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($return, $specs);
    }

    /**
     * @param FactureIn $factureIn
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addFactureIn(FactureIn $factureIn, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($factureIn, $specs);
    }

    /**
     * @param FactureOut $factureOut
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addFactureOut(FactureOut $factureOut, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($factureOut, $specs);
    }

    /**
     * @param Inventory $inventory
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addInventory(Inventory $inventory, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($inventory, $specs);
    }

    /**
     * @param Processing $processing
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProcessing(Processing $processing, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($processing, $specs);
    }

    /**
     * @param ProcessingPlan $plan
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProcessingPlan(ProcessingPlan $plan, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($plan, $specs);
    }

    /**
     * @param ProcessingOrder $order
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addProcessingOrder(ProcessingOrder $order, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($order, $specs);
    }

    /**
     * @param PriceList $list
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addPriceList(PriceList $list, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($list, $specs);
    }

    /**
     * @param EntityList $positions
     * @return $this
     */
    public function addPositionList(EntityList $positions): self
    {
        $positions->each(function (AbstractProduct $position) {
            $position->assortment = [
                'meta' => $position->getMeta()
            ];
            $this->e->links->link($position, LinkingSpecs::create([
                'multiple' => true,
                'name' => "positions",
                'excludedFields' => [
                    'id', 'meta'
                ]
            ]));
        });
        return $this;
    }

    /**
     * @param EntityList $positions
     * @return $this
     */
    public function addComponentsList(EntityList $positions): self
    {
        $positions->each(function (AbstractProduct $position) {
            $position->assortment = [
                'meta' => $position->getMeta()
            ];
            $this->e->links->link($position, LinkingSpecs::create([
                'multiple' => true,
                'name' => "components",
                'excludedFields' => [
                    'id', 'meta'
                ]
            ]));
        });
        return $this;
    }

    /**
     * @param Counterparty $counterparty
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addConsignee(Counterparty $counterparty, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($counterparty, $specs, LinkingSpecs::create([
            'name' => 'consignee'
        ]));
    }

    /**
     * @param Counterparty $counterparty
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addCarrier(Counterparty $counterparty, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($counterparty, $specs, LinkingSpecs::create([
            'name' => 'carrier'
        ]));
    }

    /**
     * @param Employee $employee
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addOwner(Employee $employee, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($employee, $specs, LinkingSpecs::create([
            "name" => "owner"
        ]));
    }

    /**
     * @param Store $store
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addTargetStore(Store $store, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($store, $specs, LinkingSpecs::create([
            'name' => 'targetStore'
        ]));
    }

    /**
     * @param Store $store
     * @param LinkingSpecs|null $specs
     * @return AbstractMutationBuilder
     * @throws Exception
     */
    public function addSourceStore(Store $store, LinkingSpecs $specs = null): AbstractMutationBuilder
    {
        return $this->simpleLink($store, $specs, LinkingSpecs::create([
            'name' => 'sourceStore'
        ]));
    }


    /**
     * @param AbstractEntity $linkedEntity
     * @param LinkingSpecs|null $specs
     * @param LinkingSpecs|null $defaultSpecs
     * @return $this
     * @throws Exception
     */
    protected function simpleLink(AbstractEntity $linkedEntity, LinkingSpecs $specs = null, LinkingSpecs $defaultSpecs = null): self
    {
        if (!$specs) {
            $newSpecs = LinkingSpecs::create([]);
        } else {
            $newSpecs = $specs;
        }
        if ($defaultSpecs) {
            $newSpecs = $defaultSpecs->mergeWith($newSpecs);
        }
        $this->e->links->link($linkedEntity, $newSpecs);
        return $this;
    }

    /**
     * @return mixed
     */
    abstract public function execute();
}
