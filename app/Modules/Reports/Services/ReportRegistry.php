<?php

declare(strict_types=1);

namespace App\Modules\Reports\Services;

final class ReportRegistry
{
    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return [
            'expiry_upcoming' => 'Upcoming Expiry',
            'expiry_expired' => 'Expired Stock',
            'sales_by_branch' => 'Sales by Branch',
            'sales_by_product' => 'Sales by Product',
            'sales_by_category' => 'Sales by Category',
            'sales_daily' => 'Daily Sales',
            'sales_monthly' => 'Monthly Sales',
            'stock_valuation' => 'Stock Valuation',
            'stock_low' => 'Low Stock',
            'stock_movement' => 'Stock Movement',
            'purchase_summary' => 'Purchase Summary',
            'supplier_ledger' => 'Supplier Ledger',
            'customer_ledger' => 'Customer Ledger',
            'profit_margin' => 'Profit Margin',
            'tax_summary' => 'Tax Summary',
            'expense_summary' => 'Expense Summary',
            'payroll_summary' => 'Payroll Summary',
            'attendance_summary' => 'Attendance Summary',
            'controlled_drugs' => 'Controlled Drug Register',
            'cold_chain' => 'Cold Chain Log',
            'online_orders' => 'Online Orders',
            'delivery_performance' => 'Delivery Performance',
            'prescription_fills' => 'Prescription Fills',
            'loyalty_points' => 'Loyalty Points',
        ];
    }

    public function count(): int
    {
        return count($this->all());
    }
}
