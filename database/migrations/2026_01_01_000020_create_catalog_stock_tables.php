<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('country', 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('address')->nullable();
            $table->unsignedTinyInteger('performance_score')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('manufacturer_id')->nullable()->constrained('manufacturers')->nullOnDelete();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('strength')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('slug');
            $table->string('hsn_code')->nullable();
            $table->string('schedule', 20)->default('otc');
            $table->string('storage_condition')->nullable();
            $table->unsignedInteger('reorder_level')->default(0);
            $table->unsignedInteger('max_level')->nullable();
            $table->string('unit', 20)->default('strip');
            $table->unsignedInteger('units_per_pack')->default(1);
            $table->unsignedBigInteger('selling_price_minor')->default(0);
            $table->unsignedBigInteger('mrp_minor')->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('images')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'barcode']);
            $table->index(['tenant_id', 'sku']);
        });

        Schema::create('product_substitutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('substitute_product_id')->constrained('products')->cascadeOnDelete();

            $table->unique(['product_id', 'substitute_product_id']);
        });

        Schema::create('stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('batch_number');
            $table->date('manufactured_at')->nullable();
            $table->date('expires_at');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->unsignedBigInteger('purchase_price_minor')->default(0);
            $table->unsignedBigInteger('mrp_minor')->default(0);
            $table->string('status', 20)->default('available');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('expires_at');
            $table->index(['branch_id', 'product_id']);
            $table->unique(['branch_id', 'product_id', 'batch_number']);
        });

        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->nullOnDelete();
            $table->string('type', 32);
            $table->integer('quantity_delta');
            $table->integer('quantity_after');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['branch_id', 'product_id', 'created_at']);
        });

        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('from_branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('to_branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('status', 20)->default('requested');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained('stock_batches')->nullOnDelete();
            $table->unsignedInteger('quantity_requested')->default(0);
            $table->unsignedInteger('quantity_sent')->default(0);
            $table->unsignedInteger('quantity_received')->default(0);
        });

        Schema::create('product_recalls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('batch_number')->nullable();
            $table->text('reason');
            $table->string('status', 20)->default('active');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recalls');
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('stock_ledgers');
        Schema::dropIfExists('stock_batches');
        Schema::dropIfExists('product_substitutes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('manufacturers');
    }
};
