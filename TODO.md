# TODO: the rest of the Cin7 V2 reference

This file lives on `feature/saloon` only: delete it before `feature/saloon` merges into `main`.

Every resource of the reference, by its name `reference/<group>/<resource>` (the page
`https://dearinventory.docs.apiary.io/#reference/<group>/<resource>`), grouped by reference group and
ranked High, Medium or Low. Pick groups, or single resources, for an issue, then ask Claude Code to
follow the cin7-models skill for them by name: `docs/skills/cin7-models/SKILL.md`.

Each resource gives its API path and operations, then the models its section documents, each with
the class it becomes: ticked when the class exists.
Each tier ends with the shared models (the reference's Other Models) its groups use.

A resource is ticked once every operation has its request class. The ticks come from the code:
refresh them with `python3 docs/skills/cin7-models/scripts/todo.py`, and move a group between
tiers in that script's HIGH and LOW lists.

## High

Purchase, supplier and me, the maintainer's choice, with every shared model they use.

### `reference/purchase/**` Purchase (17 resources, 45 operations, 12 left)

- [x] `purchase-list` · `purchaseList` · GET
  - [x] Purchase List: `PurchaseListData`
- [x] `purchase-credit-note-list` · `purchaseCreditNoteList` · GET
  - [x] Purchase Credit Note List: `PurchaseCreditNoteListData`
- [ ] `purchase` · `purchase` · GET POST PUT DELETE
  - [ ] Purchase: `PurchaseData`
  - [x] product fields: `trait HasProductFields`
  - [ ] Purchase POST/PUT body: `PurchasePostData`
  - [ ] Purchase POST/PUT body: `PurchasePutData`
- [x] `purchase-order` · `purchase/order` · GET POST
  - [x] Purchase Order: `PurchaseOrderData`
- [x] `purchase-stock-received` · `purchase/stock` · GET POST
  - [x] Purchase Stock Received: `PurchaseStockData`
- [ ] `purchase-invoice` · `purchase/invoice` · GET POST
  - [ ] Purchase Invoice: `PurchaseInvoiceData`
- [ ] `purchase-credit-note` · `purchase/creditnote` · GET POST
  - [ ] Purchase Credit Note: `PurchaseCreditNoteData`
- [x] `purchase-payments` · `purchase/payment` · GET POST PUT DELETE
  - [x] Purchase Payments: `PurchasePaymentData`
- [ ] `purchase-manual-journals` · `purchase/manualJournal` · GET POST
  - [ ] Purchase Manual Journal: `PurchaseManualJournalData`
- [ ] `purchase-attachments` · `purchase/attachment` · GET POST DELETE
  - [ ] Purchase Attachments: `PurchaseAttachmentsData`
  - [ ] Purchase Attachments POST body: `PurchaseAttachmentPostData`
- [ ] `advanced-purchase` · `advanced-purchase` · GET POST PUT DELETE
  - [ ] AdvancedPurchase: `AdvancedPurchaseData`
  - [x] product fields: `trait HasProductFields`
  - [ ] Purchase POST/PUT body: `AdvancedPurchasePostData`
  - [ ] Purchase POST/PUT body: `AdvancedPurchasePutData`
- [ ] `advanced-purchase-stock-received` · `advanced-purchase/stock` · GET POST PUT DELETE
  - [ ] AdvancedPurchaseStock: `AdvancedPurchaseStockData`
- [ ] `advanced-purchase-put-away` · `advanced-purchase/put-away` · GET POST
  - [ ] AdvancedPurchasePutAway: `AdvancedPurchasePutAwayData`
- [ ] `advanced-purchase-invoice` · `advanced-purchase/invoice` · GET POST DELETE
  - [ ] AdvancedPurchaseInvoice: `AdvancedPurchaseInvoicesData`
  - [ ] AdvancedPurchasePartialInvoiceModel: `AdvancedPurchasePartialInvoiceData`
- [ ] `advanced-purchase-credit-note` · `advanced-purchase/creditnote` · GET POST DELETE
  - [ ] AdvancedPurchaseCreditNote: `AdvancedPurchaseCreditNotesData`
  - [ ] AdvancedPurchasePartialCreditNoteModel: `AdvancedPurchasePartialCreditNoteData`
- [ ] `advanced-purchase-payments` · `advanced-purchase/payment` · GET POST PUT DELETE
  - [ ] AdvancedPurchasePayments: `AdvancedPurchasePaymentData`
- [ ] `advanced-purchase-manual-journals` · `advanced-purchase/manualJournal` · GET POST
  - [ ] Purchase Manual Journal: `AdvancedPurchaseManualJournalsData`
  - [ ] AdvancedPurchasePartialMAnJModel: `AdvancedPurchasePartialManualJournalData`

### `reference/other-models/**` Shared models for purchase, supplier and me

Each is built with the first resource here that uses it; a ticked one is built, and moves to
`src/Data/Other/` when a second family uses it.

- [x] DimensionUnitAvailableValues: `enum WeightUnit`, `enum DimensionUnit` · used by me, product, purchase, advanced-purchase, sale
- [x] AddressModel: `AddressData` · used by purchase, advanced-purchase, sale
- [ ] PurchaseShippingAddressModel: `PurchaseShippingAddressData` · used by purchase, advanced-purchase
- [x] AdditionalAttributeModel: `AdditionalAttributeData` · used by purchase, advanced-purchase, sale
- [x] SalePaymentLineModel: `SalePaymentLineData` · used by purchase, advanced-purchase, sale
- [x] AttachmentLineModel: `AttachmentLineData` · used by journal, money-operation, bank-transfer, product, product-attachments, product-family, product-family-attachments, purchase, purchase-attachments, advanced-purchase, sale, sale-attachments, stock-adjustment, stock-take
- [x] InventoryMovementLineModel: `InventoryMovementLineData` · used by purchase, advanced-purchase, sale
- [ ] PurchaseManualJournalModel: `PurchaseManualJournalData` · used by purchase
- [ ] PurchaseManualJournalLineModel: `PurchaseManualJournalLineData` · used by purchase, purchase-manual-journals, advanced-purchase, advanced-purchase-manual-journals
- [x] PurchaseOrderModel: `PurchaseOrderData` · used by purchase, advanced-purchase
- [x] PurchaseOrderLineModel: `PurchaseOrderLineData` · used by purchase, purchase-order, advanced-purchase
- [x] PurchaseAdditionalChargeModel: `PurchaseAdditionalChargeData` · used by purchase, purchase-order, advanced-purchase
- [x] PurchaseStockModel: `PurchaseStockData` · used by purchase
- [x] PurchaseStockLineModel: `PurchaseStockLineData` · used by purchase, purchase-stock-received
- [ ] PurchaseUnStockLineModel: `PurchaseUnStockLineData` · used by purchase, purchase-credit-note, advanced-purchase, advanced-purchase-credit-note
- [ ] PurchaseInvoiceModel: `PurchaseInvoiceData` · used by purchase
- [ ] PurchaseCreditNoteModel: `PurchaseCreditNoteData` · used by purchase
- [ ] PurchaseInvoiceLineModel: `PurchaseInvoiceLineData` · used by purchase, purchase-invoice, purchase-credit-note, advanced-purchase, advanced-purchase-invoice, advanced-purchase-credit-note
- [ ] PurchaseInvoiceAdditionalChargeModel: `PurchaseInvoiceAdditionalChargeData` · used by purchase, purchase-invoice, purchase-credit-note, advanced-purchase, advanced-purchase-invoice, advanced-purchase-credit-note
- [ ] PurchasePaymentLineModel: `PurchasePaymentLineData` · used by purchase, advanced-purchase
- [ ] AdvancedPurchaseStockModel: `AdvancedPurchaseStockData` · used by purchase, advanced-purchase
- [ ] AdvancedPurchaseStockLineModel: `AdvancedPurchaseStockLineData` · used by purchase, purchase-stock-received, advanced-purchase, advanced-purchase-stock-received
- [ ] AdvancedPurchasePutAwayModel: `AdvancedPurchasePutAwayData` · used by purchase, advanced-purchase
- [ ] AdvancedPurchasePutAwayLineModel: `AdvancedPurchasePutAwayLineData` · used by purchase, purchase-stock-received, advanced-purchase, advanced-purchase-put-away
- [ ] AdvancedPurchaseInvoiceModel: `AdvancedPurchaseInvoiceData` · used by purchase, advanced-purchase
- [ ] AdvancedPurchaseCreditNoteModel: `AdvancedPurchaseCreditNoteData` · used by purchase, advanced-purchase
- [ ] AdvancedPurchaseManualJournalModel: `AdvancedPurchaseManualJournalData` · used by purchase, advanced-purchase

## Medium

Every group not ranked yet: move a group up or down as issues are planned.

### `reference/attribute-set/**` Attribute Set (1 resource, 4 operations)

- [ ] `attribute-set` · `ref/attributeset` · GET POST PUT DELETE
  - [ ] Attribute Set: `AttributeSetData`

### `reference/bank-accounts/**` Bank Accounts (1 resource, 1 operation)

- [ ] `bank-accounts` · `ref/account/bank` · GET
  - [ ] Bank Accounts: `BankAccountData`

### `reference/brand/**` Brand (1 resource, 4 operations)

- [ ] `brand` · `ref/brand` · GET POST PUT DELETE
  - [ ] Brand: `BrandData`

### `reference/carrier/**` Carrier (1 resource, 4 operations)

- [ ] `carrier` · `ref/carrier` · GET POST PUT DELETE
  - [ ] Carrier: `CarrierData`

### `reference/chart-of-accounts/**` Chart of Accounts (1 resource, 4 operations)

- [ ] `chart-of-accounts` · `ref/account` · GET POST PUT DELETE
  - [ ] Chart of Accounts: `AccountData`

### `reference/customer/**` Customer (3 resources, 7 operations, 1 left)

- [x] `customer` · `customer` · GET POST PUT
  - [x] Customer: `CustomerData`
- [ ] `customer-default-template` · `ref/customer/templates` · GET POST DELETE
  - [ ] Customer Default Template: `CustomerDefaultTemplateData`
- [x] `customer-credits` · `ref/customer/credits` · GET
  - [x] Customer Credits: `CustomerCreditData`

### `reference/fixed-asset-type/**` Fixed Asset Type (1 resource, 3 operations)

- [ ] `fixed-asset-type` · `ref/fixedassettype` · GET POST PUT
  - [ ] Fixed Asset Types: `FixedAssetTypeData`

### `reference/inventory-write-off/**` Inventory Write-Off (2 resources, 5 operations)

- [ ] `inventory-write-off-list` · `inventoryWriteOffList` · GET
  - [ ] Inventory Write-Off List: `InventoryWriteOffListData`
- [ ] `inventory-write-off` · `inventoryWriteOff` · GET POST PUT DELETE
  - [ ] Inventory Write-Off: `InventoryWriteOffData`
  - [ ] Inventory Write-Off POST/PUT body: `InventoryWriteOffPostData`
  - [ ] Inventory Write-Off POST/PUT body: `InventoryWriteOffPutData`

### `reference/journal/**` Journal (1 resource, 4 operations)

- [ ] `journal` · `journal` · GET POST PUT DELETE
  - [ ] Journal: `JournalData`

### `reference/location/**` Location (1 resource, 4 operations)

- [ ] `location` · `ref/location` · GET POST PUT DELETE
  - [ ] Location: `LocationData`

### `reference/money-task/**` Money Task (3 resources, 9 operations, 1 left)

- [x] `money-task-list` · `moneyTaskList` · GET
  - [x] Money Task List: `MoneyTaskListData`
- [x] `money-operation` · `moneyOperation` · GET POST PUT DELETE
  - [x] Money Task: `MoneyTaskData`
- [ ] `bank-transfer` · `bankTransfer` · GET POST PUT DELETE
  - [ ] Bank Transfer: `BankTransferData`

### `reference/payment-term/**` Payment Term (1 resource, 4 operations)

- [ ] `payment-term` · `ref/paymentterm` · GET POST PUT DELETE
  - [ ] Payment Term: `PaymentTermData`

### `reference/price-tiers/**` Price Tiers (1 resource, 1 operation)

- [ ] `price-tiers` · `ref/priceTier` · GET
  - [ ] Price Tier: `PriceTierData`

### `reference/product/**` Product (3 resources, 7 operations, 2 left)

- [x] `product` · `product` · GET POST PUT
  - [x] Product: `ProductData`
- [ ] `product-attachments` · `product/attachments` · GET POST DELETE
  - [ ] Product Attachments POST body: `ProductAttachmentPostData`
- [ ] `product-availability` · `ref/productavailability` · GET
  - [ ] Product Availability: `ProductAvailabilityData`

### `reference/product-categories/**` Product Categories (1 resource, 4 operations)

- [ ] `product-category` · `ref/category` · GET POST PUT DELETE
  - [ ] Product Category: `ProductCategoryData`

### `reference/product-family/**` Product Family (2 resources, 6 operations)

- [ ] `product-family` · `productFamily` · GET POST PUT
  - [ ] Product Family: `ProductFamilyData`
- [ ] `product-family-attachments` · `productFamily/attachments` · GET POST DELETE
  - [ ] Product Family Attachments POST body: `ProductFamilyAttachmentPostData`

### `reference/product-markup-prices/**` Product Markup Prices (1 resource, 2 operations)

- [ ] `markup-prices` · `ref/markupprices` · GET PUT
  - [ ] Markup Prices: `MarkupPricesData`
  - [ ] MarkupPriceLineModel: `MarkupPriceLineData`

### `reference/reference-books/**` Reference Books (6 resources, 19 operations)

- [ ] `custom-prices` · `custom-prices` · POST PUT DELETE
  - uses ProductPriceModel
- [ ] `deals` · `reference/deals` · GET POST PUT
  - [ ] ProductDeal: `ProductDealData`
  - [ ] ProductDealCustomerModel: `ProductDealCustomerData`
  - [ ] ProductDealTagModel: `ProductDealTagData`
  - [ ] ProductDealDiscountModel: `ProductDealDiscountData`
  - [ ] ProductDealDiscountBrandModel: `ProductDealDiscountBrandData`
  - [ ] ProductDealDiscountProductModel: `ProductDealDiscountProductData`
  - [ ] ProductDealDiscountTagModel: `ProductDealDiscountTagData`
  - [ ] ProductDealDiscountCategoryModel: `ProductDealDiscountCategoryData`
- [ ] `product-discounts` · `reference/discount` · GET POST PUT
  - [ ] ProductDiscountRuleModel: `ProductDiscountRuleData`
  - [ ] DiscountLineModel: `DiscountLineData`
- [ ] `product-suppliers` · `product-suppliers` · GET POST PUT DELETE
  - uses ProductSupplierModel, ProductSupplierOptionsModel, ProductSupplierOptionsIntervalModel
- [ ] `ship-zones` · `reference/shipZones` · GET POST PUT DELETE
  - [ ] ShippingZoneModel: `ShippingZoneData`
  - [ ] ShipZoneAppliesToModel: `ShipZoneAppliesToData`
  - [ ] ShipZoneConditionModel: `ShipZoneConditionData`
- [ ] `ship-zones-enabled` · `reference/shipZonesEnabled` · GET PUT

### `reference/stock/**` Stock (7 resources, 17 operations)

- [ ] `stock-adjustment-list` · `stockadjustmentList` · GET
  - [x] product fields: `trait HasProductFields`
  - [ ] Stock Adjustment List: `StockAdjustmentListData`
- [ ] `stock-adjustment` · `stockadjustment` · GET POST PUT DELETE
  - [ ] Stock Adjustment: `StockAdjustmentData`
  - [ ] Stock Adjustment POST/PUT body: `StockAdjustmentPostData`
  - [ ] Stock Adjustment POST/PUT body: `StockAdjustmentPutData`
- [ ] `stock-take-list` · `stockTakeList` · GET
  - [ ] Stock Take List: `StockTakeListData`
- [ ] `stock-take` · `stocktake` · GET POST PUT DELETE
  - [ ] Stock Take: `StockTakeData`
- [ ] `stock-transfer-list` · `stockTransferList` · GET
  - [ ] Stock Transfer List: `StockTransferListData`
- [ ] `stock-transfer` · `stockTransfer` · GET POST PUT DELETE
  - [ ] Stock Transfer: `StockTransferData`
- [ ] `stock-transfer-order` · `stockTransfer/order` · GET POST
  - [ ] Stock Transfer Order: `StockTransferOrderData`

### `reference/templates/**` Templates (1 resource, 1 operation)

- [ ] `templates` · `ref/templates` · GET
  - [ ] Templates: `TemplateData`

### `reference/transactions/**` Transactions (1 resource, 1 operation)

- [ ] `transactions` · `transactions` · GET
  - [ ] Transactions: `TransactionData`

### `reference/unit-of-measure/**` Unit of Measure (1 resource, 4 operations)

- [ ] `unit-of-measure` · `ref/unit` · GET POST PUT DELETE
  - [ ] Unit of Measure: `UnitOfMeasureData`

### `reference/webhooks/**` Webhooks (1 resource, 4 operations)

- [ ] `webhooks` · `webhooks` · GET POST PUT DELETE
  - [ ] Webhooks: `WebhookData`

### `reference/other-models/**` Shared models for the groups above

Each is built with the first resource here that uses it; a ticked one is built, and moves to
Built with the first resource that uses it.

- [x] SaleShippingAddressModel: `SaleShippingAddressData` · used by sale, sale-fulfilment, sale-fulfilment-ship
- [x] SaleQuoteModel: `SaleQuoteData` · used by sale
- [x] SaleQuoteLineModel: `SaleQuoteLineData` · used by sale, sale-quote
- [x] SaleOrderModel: `SaleOrderData` · used by sale
- [x] SaleOrderLineModel: `SaleOrderLineData` · used by sale, sale-order
- [x] SaleFulfilmentModel: `SaleFulfilmentData` · used by sale, sale-fulfilment
- [x] SaleFulfilmentPickPackModel: `SaleFulfilmentPickPackData` · used by sale, sale-fulfilment
- [x] SaleFulfilmentPickPackLineModel: `SaleFulfilmentPickPackLineData` · used by sale, sale-fulfilment, sale-fulfilment-pick, sale-fulfilment-pack, sale-credit-note
- [x] SaleFulfilmentShipModel: `SaleFulfilmentShipData` · used by sale, sale-fulfilment
- [x] SaleFulfilmentShipLineModel: `SaleFulfilmentShipLineData` · used by sale, sale-fulfilment, sale-fulfilment-ship
- [x] SaleInvoiceModel: `SaleInvoiceData` · used by sale
- [x] SaleAdditionalChargeModel: `SaleAdditionalChargeData` · used by sale, sale-quote, sale-order
- [x] SaleInvoiceAdditionalChargeModel: `SaleInvoiceAdditionalChargeData` · used by sale, sale-invoice, sale-credit-note
- [x] SaleInvoiceLineModel: `SaleInvoiceLineData` · used by sale, sale-invoice, sale-credit-note
- [x] SaleCreditNoteModel: `SaleCreditNoteData` · used by sale
- [x] SaleManualJournalModel: `SaleManualJournalData` · used by sale
- [x] SaleManualJournalLineModel: `SaleManualJournalLineData` · used by sale, sale-manual-journals
- [ ] ProductFamilyProductLineModel: `ProductFamilyProductLineData` · used by product-family
- [x] SaleTransactionLineModel: `SaleTransactionLineData` · used by sale
- [ ] ExistingStockLineModel: `ExistingStockLineData` · used by stock-adjustment, stock-take
- [ ] NewStockLineModel: `NewStockLineData` · used by stock-adjustment, stock-take
- [x] TransactionStockLineModel: `TransactionStockLineData` · used by disassembly, finished-goods, inventory-write-off, money-operation, bank-transfer, stock-adjustment, stock-take
- [ ] StockTransferLineModel: `StockTransferLineData` · used by stock-transfer
- [x] PriceTierModel: no class: a map of tier names to prices · used by product
- [x] ProductSupplierModel: `ProductSupplierData` · used by product, product-suppliers
- [x] ProductSupplierOptionsModel: `ProductSupplierOptionData` · used by product, product-suppliers
- [x] ProductSupplierOptionsIntervalModel: `ProductSupplierOptionIntervalData` · used by product, product-suppliers
- [x] ReorderLevelModel: `ReorderLevelData` · used by product
- [x] BillOfMaterialProductModel: `BillOfMaterialProductData` · used by product
- [x] BillOfMaterialServiceModel: `BillOfMaterialServiceData` · used by product
- [x] ProductMovementModel: `ProductMovementData` · used by product
- [x] ErrorModel: `ErrorData` · used by disassembly, finished-goods, inventory-write-off
- [ ] AttributeSetLineModel: `AttributeSetLineData` · used by attribute-set
- [ ] InventoryWriteOffLineModel: `InventoryWriteOffLineData` · used by inventory-write-off
- [x] TaxComponentModel: `TaxComponentData` · used by tax
- [x] MoneyTaskLineModel: `MoneyTaskLineData` · used by money-operation
- [x] SupplierAddressModel: `CustomerAddressData` · used by customer, supplier, lead
- [x] SupplierContactModel: `CustomerContactData` · used by customer, supplier, lead
- [x] ChildCustomerModel: `ChildCustomerData` · used by customer
- [x] ProductPriceModel: `ProductPriceData` · used by customer, product, custom-prices
- [ ] JournalLineModel: `JournalLineData` · used by journal
- [ ] IDNameModel: `IdNameData` · used by stock-take
- [ ] StockTransferOrderModel: `StockTransferOrderData` · used by stock-transfer
- [ ] StockTransferOrderLineModel: `StockTransferOrderLineData` · used by stock-transfer, stock-transfer-order

## Low

CRM, disassembly, finished goods and production, the maintainer's choice.

### `reference/disassembly/**` Disassembly (3 resources, 6 operations)

- [ ] `disassembly-list` · `disassemblyList` · GET
  - [ ] Disassembly List: `DisassemblyListData`
- [ ] `disassembly` · `disassembly` · GET POST DELETE
  - [ ] Disassembly: `DisassemblyData`
  - [ ] Disassembly POST body: `DisassemblyPostData`
- [ ] `disassembly-order` · `disassembly/order` · GET POST
  - [ ] Disassembly Order: `DisassemblyOrderData`

### `reference/finished-goods/**` Finished Goods (4 resources, 9 operations)

- [ ] `finished-goods-list` · `finishedGoodsList` · GET
  - [ ] Finished Goods List: `FinishedGoodsListData`
- [ ] `finished-goods` · `finishedGoods` · GET POST PUT DELETE
  - [ ] Finished Goods: `FinishedGoodsData`
  - [ ] Finished Goods POST body: `FinishedGoodsPostData`
  - [ ] Finished Goods PUT body: `FinishedGoodsPutData`
- [ ] `finished-goods-order` · `finishedGoods/order` · GET POST
  - [ ] Finished Goods Order: `FinishedGoodsOrderData`
- [ ] `finished-goods-pick` · `finishedGoods/pick` · GET POST
  - [ ] Finished Goods Pick: `FinishedGoodsPickData`

### `reference/production/**` Production (10 resources, 46 operations)

- [ ] `factory-calendar` · `production/factoryCalendar` · GET POST PUT
  - [ ] FactoryCalendar: `FactoryCalendarData`
  - [ ] FactoryCalendarDay: `FactoryCalendarDayData`
  - [ ] FactoryCalendarSpecialDay: `FactoryCalendarSpecialDayData`
- [ ] `product-production-bom` · `production/productionBOM` · GET POST PUT DELETE
  - [ ] ProductionBOM: `ProductionBomData`
  - [ ] ProductionBOMOperation: `ProductionBomOperationData`
  - [ ] ProductionBOMResource: `ProductionBomResourceData`
  - [ ] ProductionBOMComponent: `ProductionBomComponentData`
  - [ ] ProductionBOMAttachment: `ProductionBomAttachmentData`
  - [ ] ProductionBOMNote: `ProductionBomNoteData`
  - [ ] ProductionBOMOperationLink: `ProductionBomOperationLinkData`
  - [ ] ProductionBOMOperationProduct: `ProductionBomOperationProductData`
- [ ] `product-family-production-bom` · `production/productionBOM` · GET POST PUT DELETE
  - [ ] Production BOM Operation: `ProductFamilyProductionBomOperationData`
  - [ ] ProductionBOMVariationComponent: `ProductionBomVariationComponentData`
- [ ] `production-order` · `production/order` · GET POST PUT POST POST POST POST PUT DELETE GET POST GET
  - [ ] Production Order: `ProductionOrderData`
  - [ ] Production Order Operation: `ProductionOrderOperationData`
  - [ ] ProductionOrderOperationAttachment: `ProductionOrderOperationAttachmentData`
  - [ ] ProductionOrderComponent: `ProductionOrderComponentData`
  - [ ] ProductionOrderOperationNote: `ProductionOrderOperationNoteData`
  - [ ] ProductionOrderResource: `ProductionOrderResourceData`
  - [ ] ProductionOrderOperationLink: `ProductionOrderOperationLinkData`
  - [ ] ProductionOrderOperationProduct: `ProductionOrderOperationProductData`
  - [ ] ProductionOrderSourceTask: `ProductionOrderSourceTaskData`
- [ ] `production-order-list` · `production/orderList` · GET
  - [ ] Production Order List Item: `ProductionOrderListData`
  - [ ] ProductionOrderListSourceTask: `ProductionOrderListSourceTaskData`
- [ ] `production-run` · `production/order/run` · POST GET PUT PUT PUT PUT PUT PUT PUT PUT PUT
  - [ ] Production Run: `ProductionRunData`
  - [ ] ProductionRunOperation: `ProductionRunOperationData`
  - [ ] ProductionRunOperationComponent: `ProductionRunOperationComponentData`
  - [ ] ProductionRunOperationResource: `ProductionRunOperationResourceData`
  - [ ] ProductionRunOperationResourceCost: `ProductionRunOperationResourceCostData`
  - [ ] ProductionRunOperationProduct: `ProductionRunOperationProductData`
  - [ ] ProductionRunOperationCoManTask: `ProductionRunOperationCoManTaskData`
  - [ ] ProductionRunOperationCoManLine: `ProductionRunOperationCoManLineData`
  - [ ] ProductionRunOperationAttachment: `ProductionRunOperationAttachmentData`
  - [ ] ProductionRunOperationNote: `ProductionRunOperationNoteData`
  - [ ] ProductionRunPendingOutput: `ProductionRunPendingOutputData`
  - [ ] ProductionRunOutput: `ProductionRunOutputData`
  - [ ] ProductionRunManualJournal: `ProductionRunManualJournalData`
  - [ ] ProductionRunTraceability: `ProductionRunTraceabilityData`
- [ ] `resource-list` · `production/resourceList` · GET
- [ ] `resource` · `production/resource` · GET POST PUT DELETE
  - [ ] Resource: `ResourceData`
  - [ ] ResourceCapacity: `ResourceCapacityData`
  - [ ] CustomWorkingDay: `CustomWorkingDayData`
  - [ ] ResourceUnit: `ResourceUnitData`
  - [ ] ResourceCost: `ResourceCostData`
  - [ ] ResourceRemark: `ResourceRemarkData`
  - [ ] ResourceAttachment: `ResourceAttachmentData`
- [ ] `suspend-reason` · `production/suspendReason` · GET PUT
  - [ ] SuspendReason: `SuspendReasonData`
- [ ] `work-centers` · `production/workcenters` · GET POST PUT DELETE
  - [ ] WorkCenter: `WorkCenterData`
  - [ ] WorkCenterLocation: `WorkCenterLocationData`
  - [ ] WorkCenterSupplier: `WorkCenterSupplierData`

### `reference/crm/**` CRM (6 resources, 16 operations)

- [ ] `lead` · `crm/lead` · GET POST PUT
  - [ ] Lead: `LeadData`
- [ ] `opportunity` · `crm/opportunity` · GET POST PUT
  - [ ] Opportunity: `OpportunityData`
  - [ ] Opportunity Line: `OpportunityLineData`
  - [ ] Opportunity Opportunity Additional Charge: `OpportunityAdditionalChargeData`
- [ ] `task` · `crm/task` · GET POST PUT
  - [ ] Task: `TaskData`
- [ ] `task-category` · `crm/taskcategory` · GET POST PUT
  - [ ] Task Category: `TaskCategoryData`
- [ ] `workflow` · `crm/workflow` · GET POST PUT
  - [ ] Workflow: `WorkflowData`
  - [ ] WorkflowStep: `WorkflowStepData`
- [ ] `start-a-workflow` · `crm/workflowstart` · POST

### `reference/other-models/**` Shared models for the low groups only

Each is built with the first resource here that uses it; a ticked one is built, and moves to
Built with the first resource that uses it.

- [ ] FinishedGoodsOrderLineModel: `FinishedGoodsOrderLineData` · used by finished-goods, finished-goods-order
- [ ] FinishedGoodsPickLineModel: `FinishedGoodsPickLineData` · used by finished-goods, finished-goods-pick
- [ ] DisassemblyPickLineModel: `DisassemblyPickLineData` · used by disassembly
- [ ] DisassemblyOrderLineModel: `DisassemblyOrderLineData` · used by disassembly, disassembly-order
- [ ] DisassemblyOrderServiceLineModel: `DisassemblyOrderServiceLineData` · used by disassembly

## Done

Every resource in these groups is in.

### `reference/me/**` Me (3 resources, 9 operations)

- [x] `me` · `me` · GET
  - [x] ME: `MeData`
  - [x] RoundingTableModel: `RoundingTableData`
- [x] `me-address` · `me/addresses` · GET POST PUT DELETE
  - [x] Me Address: `MeAddressData`
- [x] `me-contact` · `me/contacts` · GET POST PUT DELETE
  - [x] Me Contact: `MeContactData`

### `reference/sale/**` Sale (14 resources, 38 operations)

- [x] `sale-list` · `saleList` · GET
  - [x] Sale List: `SaleListData`
- [x] `sale-credit-note-list` · `saleCreditNoteList` · GET
  - [x] Sale Credit Note List: `SaleCreditNoteListData`
- [x] `sale` · `sale` · GET POST PUT DELETE
  - [x] Sale: `SaleData`
  - [x] product fields: `trait HasProductFields`
  - [x] Sale POST/PUT body: `SalePostData`
  - [x] Sale POST/PUT body: `SalePutData`
- [x] `sale-quote` · `sale/quote` · GET POST
  - [x] Sale Quote: `SaleQuoteData`
- [x] `sale-order` · `sale/order` · GET POST
  - [x] Sale Order: `SaleOrderData`
- [x] `sale-fulfilment` · `sale/fulfilment` · GET POST DELETE
  - [x] Sale Fulfilment: `SaleFulfilmentsData`
- [x] `sale-fulfilment-pick` · `sale/fulfilment/pick` · GET POST PUT
  - [x] Sale Fulfilment Pick: `SaleFulfilmentPickData`
- [x] `sale-fulfilment-pack` · `sale/fulfilment/pack` · GET POST PUT
  - [x] Sale Fulfilment Pack: `SaleFulfilmentPackData`
- [x] `sale-fulfilment-ship` · `sale/fulfilment/ship` · GET POST PUT
  - [x] Sale Fulfilment Ship: `SaleFulfilmentShipData`
- [x] `sale-invoice` · `sale/invoice` · GET POST PUT DELETE
  - [x] Invoice Available Fields: `SaleInvoicesData`
  - [x] SaleInvoicePartialModel: `SaleInvoicePartialData`
  - [x] Sale Invoice POST body: `SaleInvoicePostData`
- [x] `sale-credit-note` · `sale/creditnote` · GET POST DELETE
  - [x] Credit Note Available Fields: `SaleCreditNotesData`
  - [x] SaleCreditNotePartialModel: `SaleCreditNotePartialData`
  - [x] Sale Credit Note POST body: `SaleCreditNotePostData`
- [x] `sale-payments` · `sale/payment` · GET POST PUT DELETE
  - [x] Sale Payment Line Partial: `SalePaymentLinePartialData`
- [x] `sale-manual-journals` · `sale/manualJournal` · GET POST
  - [x] Sale Manual Journal: `SaleManualJournalData`
- [x] `sale-attachments` · `sale/attachment` · GET POST DELETE
  - [x] Sale Attachments: `SaleAttachmentsData`
  - [x] Sale Attachments POST body: `SaleAttachmentPostData`

### `reference/supplier/**` Supplier (2 resources, 4 operations)

- [x] `supplier` · `supplier` · GET POST PUT
  - [x] Supplier: `SupplierData`
- [x] `supplier-deposits` · `ref/supplier/deposits` · GET
  - [x] Supplier Deposits: `SupplierDepositData`

### `reference/tax/**` Tax (1 resource, 3 operations)

- [x] `tax` · `ref/tax` · GET POST PUT
  - [x] Tax: `TaxData`
