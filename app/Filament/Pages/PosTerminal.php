<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Modules\Catalog\Models\Product;
use App\Modules\Pos\Services\PosService;
use App\Modules\Sales\Models\Sale;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use UnitEnum;

class PosTerminal extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|UnitEnum|null $navigationGroup = 'POS';

    protected static ?string $navigationLabel = 'POS Terminal';

    protected static ?string $title = 'POS Terminal';

    protected string $view = 'filament.pages.pos-terminal';

    public ?int $saleId = null;

    public string $barcode = '';

    /** @var array<int, array{product_id: int, name: string, quantity: int, unit_price_minor: int, line_total_minor: int}> */
    public array $cart = [];

    public function mount(PosService $pos): void
    {
        $branchId = Auth::user()?->branch_id;
        if ($branchId === null) {
            return;
        }

        $sale = $pos->startSale((int) $branchId, cashierId: Auth::id());
        $this->saleId = $sale->id;
    }

    public function searchProduct(): void
    {
        if (trim($this->barcode) === '') {
            return;
        }

        $product = Product::query()
            ->where('barcode', $this->barcode)
            ->orWhere('sku', $this->barcode)
            ->first();

        if ($product === null) {
            Notification::make()->title('Product not found')->warning()->send();

            return;
        }

        $this->addToCart((int) $product->id, (int) $product->selling_price_minor, $product->name);
        $this->barcode = '';
    }

    public function addToCart(int $productId, int $unitPriceMinor, string $name): void
    {
        foreach ($this->cart as $index => $line) {
            if ($line['product_id'] === $productId) {
                $this->cart[$index]['quantity']++;
                $this->cart[$index]['line_total_minor'] = $this->cart[$index]['quantity'] * $unitPriceMinor;
                $this->syncCartToSale();

                return;
            }
        }

        $this->cart[] = [
            'product_id' => $productId,
            'name' => $name,
            'quantity' => 1,
            'unit_price_minor' => $unitPriceMinor,
            'line_total_minor' => $unitPriceMinor,
        ];

        $this->syncCartToSale();
    }

    public function completeSale(PosService $pos): void
    {
        if ($this->saleId === null) {
            return;
        }

        /** @var Sale $sale */
        $sale = Sale::query()->findOrFail($this->saleId);
        $completed = $pos->complete($sale);

        Notification::make()
            ->title('Sale completed')
            ->body("Invoice {$completed->invoice_number}")
            ->success()
            ->send();

        $branchId = Auth::user()?->branch_id;
        if ($branchId !== null) {
            $sale = $pos->startSale((int) $branchId, cashierId: Auth::id());
            $this->saleId = $sale->id;
        }

        $this->cart = [];
    }

    #[Computed]
    public function cartTotal(): int
    {
        return array_sum(array_column($this->cart, 'line_total_minor'));
    }

    private function syncCartToSale(): void
    {
        if ($this->saleId === null) {
            return;
        }

        /** @var Sale $sale */
        $sale = Sale::query()->find($this->saleId);
        if ($sale === null || $sale->status !== Sale::STATUS_DRAFT) {
            return;
        }

        $sale->items()->delete();

        foreach ($this->cart as $line) {
            $sale->items()->create([
                'product_id' => $line['product_id'],
                'quantity' => $line['quantity'],
                'unit_price_minor' => $line['unit_price_minor'],
                'line_total_minor' => $line['line_total_minor'],
            ]);
        }

        $subtotal = (int) $sale->items()->sum('line_total_minor');
        $sale->update([
            'subtotal_minor' => $subtotal,
            'total_minor' => $subtotal,
        ]);
    }
}
