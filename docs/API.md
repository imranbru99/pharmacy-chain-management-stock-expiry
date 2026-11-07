# API v1 (Sanctum)

Base path: `/api/v1`  
Authentication: `Authorization: Bearer {token}` (Laravel Sanctum personal access token)

## Products

`GET /api/v1/products`

Query: `q`, `per_page` (max 100)

## Stock by branch

`GET /api/v1/branches/{branch_id}/stock`

Returns paginated batches with product summary, ordered by `expires_at`.

## Sales (draft stub)

`POST /api/v1/sales`

```json
{
  "branch_id": 1,
  "customer_id": null,
  "items": [
    {"product_id": 1, "quantity": 2, "unit_price_minor": 1500}
  ]
}
```

Creates a draft sale and line items (POS completion flow is via Filament).
