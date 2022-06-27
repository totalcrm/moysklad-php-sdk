<?php

namespace TotalCRM\MoySklad\Registers;

use TotalCRM\MoySklad\Entities\AbstractEntity;
use TotalCRM\MoySklad\Entities\Account;
use TotalCRM\MoySklad\Entities\Assortment;
use TotalCRM\MoySklad\Entities\Audit\Audit;
use TotalCRM\MoySklad\Entities\Audit\AuditEvent;
use TotalCRM\MoySklad\Entities\Cashier;
use TotalCRM\MoySklad\Entities\ContactPerson;
use TotalCRM\MoySklad\Entities\Contract;
use TotalCRM\MoySklad\Entities\Counterparty;
use TotalCRM\MoySklad\Entities\Country;
use TotalCRM\MoySklad\Entities\Currency;
use TotalCRM\MoySklad\Entities\Discount;
use TotalCRM\MoySklad\Entities\Documents\AbstractDocument;
use TotalCRM\MoySklad\Entities\Documents\Cash\AbstractCash;
use TotalCRM\MoySklad\Entities\Documents\Cash\CashIn;
use TotalCRM\MoySklad\Entities\Documents\Cash\CashOut;
use TotalCRM\MoySklad\Entities\Documents\CommissionReports\AbstractCommissionReport;
use TotalCRM\MoySklad\Entities\Documents\CommissionReports\CommissionReportIn;
use TotalCRM\MoySklad\Entities\Documents\CommissionReports\CommissionReportOut;
use TotalCRM\MoySklad\Entities\Documents\Factures\AbstractFacture;
use TotalCRM\MoySklad\Entities\Documents\Factures\FactureIn;
use TotalCRM\MoySklad\Entities\Documents\Factures\FactureOut;
use TotalCRM\MoySklad\Entities\Documents\Inventory;
use TotalCRM\MoySklad\Entities\Documents\Movements\AbstractMovement;
use TotalCRM\MoySklad\Entities\Documents\Movements\Demand;
use TotalCRM\MoySklad\Entities\Documents\Movements\Enter;
use TotalCRM\MoySklad\Entities\Documents\Movements\Loss;
use TotalCRM\MoySklad\Entities\Documents\Movements\Supply;
use TotalCRM\MoySklad\Entities\Documents\Movements\Move;
use TotalCRM\MoySklad\Entities\Documents\Payments\PaymentOut;
use TotalCRM\MoySklad\Entities\Documents\Payments\PaymentIn;
use TotalCRM\MoySklad\Entities\Documents\Orders\AbstractOrder;
use TotalCRM\MoySklad\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\MoySklad\Entities\Documents\Orders\PurchaseOrder;
use TotalCRM\MoySklad\Entities\Documents\Positions\AbstractPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\CustomerOrderPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\InventoryPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\DemandPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\EnterPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\LossPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\MovePosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\SalesReturnPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\PurchaseReturnPosition;
use TotalCRM\MoySklad\Entities\Documents\Positions\SupplyPosition;
use TotalCRM\MoySklad\Entities\Documents\PriceLists\PriceList;
use TotalCRM\MoySklad\Entities\Documents\PriceLists\PriceListRow;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingMaterial;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingPlanFolder;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingPlanMaterial;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingPlanProduct;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingProduct;
use TotalCRM\MoySklad\Entities\Documents\Templates\CustomTemplate;
use TotalCRM\MoySklad\Entities\Products\Components\AbstractComponent;
use TotalCRM\MoySklad\Entities\Products\Components\BundleComponent;
use TotalCRM\MoySklad\Entities\Documents\Processings\AbstractProcessing;
use TotalCRM\MoySklad\Entities\Documents\Processings\Processing;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingOrder;
use TotalCRM\MoySklad\Entities\Documents\Processings\ProcessingPlan;
use TotalCRM\MoySklad\Entities\Documents\Retail\AbstractRetail;
use TotalCRM\MoySklad\Entities\Documents\Retail\RetailDemand;
use TotalCRM\MoySklad\Entities\Documents\Retail\RetailSalesReturn;
use TotalCRM\MoySklad\Entities\Documents\RetailDrawer\AbstractRetailDrawer;
use TotalCRM\MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashIn;
use TotalCRM\MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashOut;
use TotalCRM\MoySklad\Entities\Documents\RetailShift;
use TotalCRM\MoySklad\Entities\Documents\Returns\AbstractReturn;
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
use TotalCRM\MoySklad\Entities\Misc\Publication;
use TotalCRM\MoySklad\Entities\Misc\State;
use TotalCRM\MoySklad\Entities\Misc\Webhook;
use TotalCRM\MoySklad\Entities\Misc\PriceType;
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
use TotalCRM\MoySklad\Entities\Bonustransaction;
use TotalCRM\MoySklad\Entities\Bonusprogram;
use TotalCRM\MoySklad\Utils\AbstractSingleton;

/**
 * Class EntityRegistry
 * @package TotalCRM\MoySklad\Registers
 */
class EntityRegistry extends AbstractSingleton
{
    protected static $instance = null;
    public array $entities = [
        AbstractEntity::class,
        AbstractDocument::class,
        PaymentIn::class,
        PaymentOut::class,
        AbstractOrder::class,
        CustomerOrder::class,
        PurchaseOrder::class,
        Assortment::class,
        Counterparty::class,
        Organization::class,
        AbstractProduct::class,
        Product::class,
        Bundle::class,
        Service::class,
        Employee::class,
        Group::class,
        Uom::class,
        Account::class,
        ContactPerson::class,
        State::class,
        AbstractPosition::class,
        LossPosition::class,
        EnterPosition::class,
        MovePosition::class,
        CustomerOrderPosition::class,
        InventoryPosition::class,
        DemandPosition::class,
        SupplyPosition::class,
        SalesReturnPosition::class,
        PurchaseReturnPosition::class,
        AbstractComponent::class,
        BundleComponent::class,
        Country::class,
        Webhook::class,
        ProductFolder::class,
        Consignment::class,
        Variant::class,
        AbstractMovement::class,
        Enter::class,
        Move::class,
        Attribute::class,
        Publication::class,
        Store::class,
        Characteristics::class,
        CompanySettings::class,
        CustomEntity::class,
        CustomTemplate::class,
        Cashier::class,
        Contract::class,
        Discount::class,
        ExpenseItem::class,
        Project::class,
        RetailStore::class,
        Currency::class,
        Loss::class,
        Demand::class,
        Supply::class,
        AbstractCash::class,
        CashIn::class,
        CashOut::class,
        AbstractRetail::class,
        RetailSalesReturn::class,
        RetailDemand::class,
        AbstractRetailDrawer::class,
        RetailDrawerCashIn::class,
        RetailDrawerCashOut::class,
        AbstractReturn::class,
        PurchaseReturn::class,
        SalesReturn::class,
        AbstractFacture::class,
        FactureIn::class,
        FactureOut::class,
        Inventory::class,
        RetailShift::class,
        AbstractCommissionReport::class,
        CommissionReportIn::class,
        CommissionReportOut::class,
        AbstractProcessing::class,
        Processing::class,
        ProcessingOrder::class,
        ProcessingPlan::class,
        ProcessingPlanFolder::class,
        PriceType::class,
        PriceList::class,
        PriceListRow::class,
        Audit::class,
        AuditEvent::class,
        ProcessingPlanMaterial::class,
        ProcessingPlanProduct::class,
        ProcessingProduct::class,
        ProcessingMaterial::class,
        Bonustransaction::class,
        Bonusprogram::class
    ];
    public array $entityNames = [];

    protected function __construct()
    {
        foreach ($this->entities as $i => $e) {
            $this->entityNames[$e::$entityName] = $e;
        }
    }

    public function bootEntities(): void
    {
        foreach ($this->entities as $e) {
            $e::boot();
        }
    }
}
