# API Documentation

## Overview
This API provides endpoints for managing affiliates, wallets, and transactions. It supports CRUD operations through RESTful methods (GET, POST, PUT, DELETE).

## Base URL
```
/en/api/
/es/api/
```

---
## Endpoints

### 1. Affiliate
#### `GET /api/affiliate/{id}`
Retrieve details about an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [
    {
      "affiliate_id": 123,
      "name": "John Doe"
    }
  ]
}
```

#### `POST /api/affiliate`
Create a new affiliate.
##### Request Body
- `partner_id` (integer, required) - ID of the partner.
- `email` (string, required) - Email of the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [{ "affiliate_id": 124 }]
}
```

#### `PUT /api/affiliate/{id}`
Update an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Request Body
- `code` (string, required) - The updated code for the affiliate.
##### Response
```json
{
  "status": "success",
  "items": [{ "affiliate_id": 123, "code": "new_code" }]
}
```

#### `DELETE /api/affiliate/{id}`
Delete an affiliate.
##### Request Parameters
- `id` (integer, required) - The ID of the affiliate.
##### Response
```json
{
  "status": "success"
}
```

---
### 2. Wallet
#### `GET /api/wallet/{id}`
Retrieve wallet details.
##### Request Parameters
- `id` (integer, required) - The ID of the wallet.
##### Response
```json
{
  "status": "success",
  "items": [{ "wallet_id": 456, "name": "default" }]
}
```

---
### 3. Transactions
#### `GET /api/tx/{id}`
Retrieve transaction details.
##### Request Parameters
- `id` (integer, required) - The ID of the transaction.
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 789, "amount": 100.00 }]
}
```

#### `POST /api/tx`
Create a transaction.
##### Request Body
- `partner_id` (integer, required)
- `email` (string, required)
- `amount` (float, required)
- `tx_type_cd` (string, required)
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 790, "amount": 150.00 }]
}
```

#### `PUT /api/tx/{id}`
Update a transaction.
##### Request Parameters
- `id` (integer, required)
##### Request Body
- `amount` (float, required)
##### Response
```json
{
  "status": "success",
  "items": [{ "tx_id": 789, "amount": 200.00 }]
}
```

#### `DELETE /api/tx/{id}`
Delete a transaction.
##### Request Parameters
- `id` (integer, required)
##### Response
```json
{
  "status": "success"
}
```

---
## Authentication
This API does not currently enforce authentication. However, future versions may require authentication tokens.

## Error Handling
Responses include an error message in case of failure:
```json
{
  "status": "error",
  "error": "invalid request 305"
}
```

## Method Override
This API supports method override using headers:
- `X-HTTP-Method-Override`
- `USER_METHOD_OVERRIDE`

