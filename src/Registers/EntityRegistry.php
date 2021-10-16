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
use TotalCRM\Entities\Documents\Movements\Loss;
use TotalCRM\Entities\Documents\Movements\Supply;
use TotalCRM\Entities\Documents\Movements\Move;
use TotalCRM\Entities\Documents\Payments\PaymentOut;
use TotalCRM\Entities\Documents\Payments\PaymentIn;
use TotalCRM\Entities\Documents\Orders\AbstractOrder;
use TotalCRM\Entities\Documents\Orders\CustomerOrder;
use TotalCRM\Entities\Documents\Orders\PurchaseOrder;
use TotalCRM\Entities\Documents\Positions\AbstractPosition;
use TotalCRM\Entities\Documents\Positions\CustomerOrderPosition;
use TotalCRM\Entities\Documents\Positions\InventoryPosition;
use TotalCRM\Entities\Documents\Positions\DemandPosition;
use TotalCRM\Entities\Documents\Positions\EnterPosition;
use TotalCRM\Entities\Documents\Positions\LossPosition;
use TotalCRM\Entities\Documents\Positions\MovePosition;
use TotalCRM\Entities\Documents\Positions\SalesReturnPosition;
use TotalCRM\Entities\Documents\Positions\PurchaseReturnPosition;
use TotalCRM\Entities\Documents\Positions\SupplyPosition;
use TotalCRM\Entities\Documents\PriceLists\PriceList;
use TotalCRM\Entities\Documents\PriceLists\PriceListRow;
use TotalCRM\Entities\Documents\Processings\ProcessingMaterial;
use TotalCRM\Entities\Documents\Processings\ProcessingPlanFolder;
use TotalCRM\Entities\Documents\Processings\ProcessingPlanMaterial;
use TotalCRM\Entities\Documents\Processings\ProcessingPlanProduct;
use TotalCRM\Entities\Documents\Processings\ProcessingProduct;
use TotalCRM\Entities\Documents\Templates\CustomTemplate;
use TotalCRM\Entities\Products\Components\AbstractComponent;
use TotalCRM\Entities\Products\Components\BundleComponent;
use TotalCRM\Entities\Documents\Processings\AbstractProcessing;
use TotalCRM\Entities\Documents\Processings\Processing;
use TotalCRM\Entities\Documents\Processings\ProcessingOrder;
use TotalCRM\Entities\Documents\Processings\ProcessingPlan;
use TotalCRM\Entities\Documents\Retail\AbstractRetail;
use TotalCRM\Entities\Documents\Retail\RetailDemand;
use TotalCRM\Entities\Documents\Retail\RetailSalesReturn;
use TotalCRM\Entities\Documents\RetailDrawer\AbstractRetailDrawer;
use TotalCRM\Entities\Documents\RetailDrawer\RetailDrawerCashIn;
use TotalCRM\Entities\Documents\RetailDrawer\RetailDrawerCashOut;
use TotalCRM\Entities\Documents\RetailShift;
use TotalCRM\Entities\Documents\Returns\AbstractReturn;
use TotalCRM\Entities\Documents\Returns\PurchaseReturn;
use TotalCRM\Entities\Documents\Returns\SalesReturn;
use TotalCRM\Entities\Employee;
use TotalCRM\Entities\ExpenseItem;
use TotalCRM\Entities\Folders\ProductFolder;
use TotalCRM\Entities\Group;
use TotalCRM\Entities\Misc\Attribute;
use TotalCRM\Entities\Misc\Characteristics;
use TotalCRM\Entities\Misc\CompanySettings;
use TotalCRM\Entities\Misc\CustomEntity;
use TotalCRM\Entities\Misc\Publication;
use TotalCRM\Entities\Misc\State;
use TotalCRM\Entities\Misc\Webhook;
use TotalCRM\Entities\Organization;
use TotalCRM\Entities\Products\AbstractProduct;
use TotalCRM\Entities\Products\Bundle;
use TotalCRM\Entities\Products\Consignment;
use TotalCRM\Entities\Products\Product;
use TotalCRM\Entities\Products\Service;
use TotalCRM\Entities\Products\Variant;
use TotalCRM\Entities\Project;
use TotalCRM\Entities\RetailStore;
use TotalCRM\Entities\Store;
use TotalCRM\Entities\Uom;
use TotalCRM\Entities\Bonustransaction;
use TotalCRM\Entities\Bonusprogram;
use TotalCRM\Utils\AbstractSingleton;

/**
 * Map of entity name => representing class
 * Class EntityRegistry
 * @package MoySklad\Registries
 */
class EntityRegistry extends AbstractSingleton
{
    protected static $instance = null;
    public $entities = [
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
    public $entityNames = [];

    protected function __construct()
    {
        foreach ($this->entities as $i => $e) {
            $this->entityNames[$e::$entityName] = $e;
        }
    }

    public function bootEntities()
    {
        foreach ($this->entities as $e) {
            $e::boot();
        }
    }
}
